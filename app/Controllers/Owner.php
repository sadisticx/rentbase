<?php

namespace App\Controllers;

use App\Models\RoomModel;
use App\Models\ParkingModel;
use App\Models\UserModel;
use App\Models\ComplaintModel;
use App\Models\ComplaintReplyModel;

class Owner extends BaseController
{
    protected $session;
    protected $roomModel;
    protected $parkingModel;
    protected $userModel;
    protected $complaintModel;
    protected $replyModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->roomModel = new RoomModel();
        $this->parkingModel = new ParkingModel();
        $this->userModel = new UserModel();
        $this->complaintModel = new ComplaintModel();
        $this->replyModel = new ComplaintReplyModel();
    }

    /**
     * Check if user is authenticated and has owner role
     */
    private function checkAuth()
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'owner') {
            return redirect()->to('/auth/login')->with('error', 'Access Denied');
        }
        return null;
    }

    /**
     * Owner dashboard
     */
    public function dashboard()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $ownerId = $this->session->get('user_id');

        // Get counts for dashboard
        $roomsCount = $this->roomModel->where('owner_id', $ownerId)->countAllResults();
        $tenantsCount = $this->userModel->where('role', 'tenant')
                                       ->where('owner_id', $ownerId)
                                       ->countAllResults();
        $parkingCount = $this->parkingModel->where('owner_id', $ownerId)->countAllResults();
        
        // Count complaints through rooms table (complaints don't have owner_id)
        $complaintsCount = $this->complaintModel
                                ->join('rooms', 'rooms.id = complaints.room_id')
                                ->where('rooms.owner_id', $ownerId)
                                ->whereIn('complaints.status', ['open', 'in_progress'])
                                ->countAllResults();

        $data = [
            'title' => 'Owner Dashboard',
            'username' => $this->session->get('username'),
            'rooms_count' => $roomsCount,
            'tenants_count' => $tenantsCount,
            'parking_count' => $parkingCount,
            'complaints_count' => $complaintsCount
        ];

        return view('owner/dashboard', $data);
    }

    /**
     * Manage rooms
     */
    public function rooms()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $ownerId = $this->session->get('user_id');
        $rooms = $this->roomModel->getRoomsByOwner($ownerId);
        // Only get tenants that belong to this owner
        $tenants = $this->userModel->where('role', 'tenant')
                                   ->where('owner_id', $ownerId)
                                   ->findAll();

        $data = [
            'title' => 'Manage Rooms',
            'username' => $this->session->get('username'),
            'rooms' => $rooms,
            'tenants' => $tenants
        ];

        return view('owner/rooms', $data);
    }

    /**
     * Add new room
     */
    public function addRoom()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'room_number' => 'required|max_length[20]',
            'details' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'room_number' => $this->request->getPost('room_number'),
            'details' => $this->request->getPost('details'),
            'owner_id' => $this->session->get('user_id')
        ];

        if ($this->roomModel->insert($data)) {
            return redirect()->to('/owner/rooms')->with('success', 'Room added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add room');
    }

    /**
     * Edit room
     */
    public function editRoom($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'room_number' => 'required|max_length[20]',
            'details' => 'permit_empty|max_length[1000]',
            'tenant_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'room_number' => $this->request->getPost('room_number'),
            'details' => $this->request->getPost('details'),
            'tenant_id' => $this->request->getPost('tenant_id') ?: null
        ];

        if ($this->roomModel->update($id, $data)) {
            return redirect()->to('/owner/rooms')->with('success', 'Room updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update room');
    }

    /**
     * Delete room
     */
    public function deleteRoom($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        if ($this->roomModel->delete($id)) {
            return redirect()->to('/owner/rooms')->with('success', 'Room deleted successfully');
        }

        return redirect()->to('/owner/rooms')->with('error', 'Failed to delete room');
    }

    /**
     * Manage tenants
     */
    public function tenants()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        // Get tenants with their profile information for this owner only
        $ownerId = $this->session->get('user_id');
        $tenants = $this->userModel->getTenantsWithProfiles($ownerId);

        $data = [
            'title' => 'Manage Tenants',
            'username' => $this->session->get('username'),
            'tenants' => $tenants
        ];

        return view('owner/tenants', $data);
    }

    /**
     * Add new tenant
     */
    public function addTenant()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'full_name' => 'required|max_length[100]',
            'email' => 'required|valid_email|is_unique[user_profiles.email]',
            'phone_number' => 'required|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert user
        $userData = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role' => 'tenant',
            'owner_id' => $this->session->get('user_id')
        ];

        $userId = $this->userModel->insert($userData);

        if ($userId) {
            // Insert profile
            $profileData = [
                'user_id' => $userId,
                'full_name' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'phone_number' => $this->request->getPost('phone_number')
            ];

            $db->table('user_profiles')->insert($profileData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to add tenant');
        }

        return redirect()->to('/owner/tenants')->with('success', 'Tenant added successfully');
    }

    /**
     * Edit tenant
     */
    public function editTenant($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'full_name' => 'required|max_length[100]',
            'email' => "required|valid_email|is_unique[user_profiles.email,user_id,{$id}]",
            'phone_number' => 'required|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Update user
        $userData = [
            'username' => $this->request->getPost('username')
        ];

        $this->userModel->update($id, $userData);

        // Update or insert profile
        $profileData = [
            'full_name' => $this->request->getPost('full_name'),
            'email' => $this->request->getPost('email'),
            'phone_number' => $this->request->getPost('phone_number')
        ];

        $existingProfile = $db->table('user_profiles')->where('user_id', $id)->get()->getRow();

        if ($existingProfile) {
            $db->table('user_profiles')->where('user_id', $id)->update($profileData);
        } else {
            $profileData['user_id'] = $id;
            $db->table('user_profiles')->insert($profileData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to update tenant');
        }

        return redirect()->to('/owner/tenants')->with('success', 'Tenant updated successfully');
    }

    /**
     * Delete tenant
     */
    public function deleteTenant($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        if ($this->userModel->delete($id)) {
            return redirect()->to('/owner/tenants')->with('success', 'Tenant deleted successfully');
        }

        return redirect()->to('/owner/tenants')->with('error', 'Failed to delete tenant');
    }

    /**
     * Manage parking
     */
    public function parking()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $ownerId = $this->session->get('user_id');
        $parkingSlots = $this->parkingModel->getSlotsByOwner($ownerId);
        // Only get tenants that belong to this owner
        $tenants = $this->userModel->where('role', 'tenant')
                                   ->where('owner_id', $ownerId)
                                   ->findAll();

        $data = [
            'title' => 'Manage Parking',
            'username' => $this->session->get('username'),
            'parking' => $parkingSlots,
            'tenants' => $tenants
        ];

        return view('owner/parking', $data);
    }

    /**
     * Add new parking slot
     */
    public function addParking()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'slot_number' => 'required|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'slot_number' => $this->request->getPost('slot_number'),
            'owner_id' => $this->session->get('user_id')
        ];

        if ($this->parkingModel->insert($data)) {
            return redirect()->to('/owner/parking')->with('success', 'Parking slot added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add parking slot');
    }

    /**
     * Edit parking slot
     */
    public function editParking($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'slot_number' => 'required|max_length[20]',
            'tenant_id' => 'permit_empty|integer'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'slot_number' => $this->request->getPost('slot_number'),
            'tenant_id' => $this->request->getPost('tenant_id') ?: null
        ];

        if ($this->parkingModel->update($id, $data)) {
            return redirect()->to('/owner/parking')->with('success', 'Parking slot updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update parking slot');
    }

    /**
     * Delete parking slot
     */
    public function deleteParking($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        if ($this->parkingModel->delete($id)) {
            return redirect()->to('/owner/parking')->with('success', 'Parking slot deleted successfully');
        }

        return redirect()->to('/owner/parking')->with('error', 'Failed to delete parking slot');
    }

    /**
     * View complaints
     */
    public function complaints()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $ownerId = $this->session->get('user_id');
        $complaints = $this->complaintModel->getComplaintsByOwner($ownerId);

        $data = [
            'title' => 'View Complaints',
            'username' => $this->session->get('username'),
            'complaints' => $complaints
        ];

        return view('owner/complaints', $data);
    }

    /**
     * Update complaint status
     */
    public function updateComplaintStatus($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $status = $this->request->getPost('status');
        
        if ($this->complaintModel->updateStatus($id, $status)) {
            return redirect()->to('/owner/complaints')->with('success', 'Complaint status updated');
        }

        return redirect()->back()->with('error', 'Failed to update complaint status');
    }

    /**
     * Get complaint replies (AJAX)
     */
    public function getReplies($complaintId)
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'owner') {
            return $this->response->setJSON(['error' => 'Unauthorized']);
        }
        
        $replies = $this->replyModel->getRepliesWithUsers($complaintId);
        
        // Format replies for display
        $formattedReplies = array_map(function($reply) {
            return [
                'username' => $reply['full_name'] ?? $reply['username'],
                'message' => $reply['message'],
                'created_at' => date('M d, Y H:i', strtotime($reply['created_at']))
            ];
        }, $replies);
        
        return $this->response->setJSON(['replies' => $formattedReplies]);
    }
    
    /**
     * Add reply to complaint (AJAX)
     */
    public function addReply($complaintId)
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'owner') {
            return $this->response->setJSON(['success' => false, 'error' => 'Unauthorized']);
        }
        
        $message = $this->request->getPost('reply_message');
        $userId = $this->session->get('user_id');
        
        $data = [
            'complaint_id' => $complaintId,
            'user_id' => $userId,
            'message' => $message
        ];
        
        $result = $this->replyModel->insert($data);
        
        if ($result) {
            return $this->response->setJSON(['success' => true]);
        } else {
            return $this->response->setJSON(['success' => false]);
        }
    }

    /**
     * Manage employees
     */
    public function employees()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        // Get employees with their profile information for this owner only
        $ownerId = $this->session->get('user_id');
        $employees = $this->userModel->getEmployeesWithProfiles($ownerId);

        $data = [
            'title' => 'Manage Employees',
            'username' => $this->session->get('username'),
            'employees' => $employees
        ];

        return view('owner/employees', $data);
    }

    /**
     * Add new employee
     */
    public function addEmployee()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password' => 'required|min_length[6]',
            'full_name' => 'permit_empty|max_length[100]',
            'email' => 'permit_empty|valid_email',
            'phone_number' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Insert user
        $userData = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role' => 'employee',
            'owner_id' => $this->session->get('user_id')  // Automatically assign to this owner
        ];

        $userId = $this->userModel->insert($userData);

        if ($userId && ($this->request->getPost('full_name') || $this->request->getPost('email') || $this->request->getPost('phone_number'))) {
            // Insert profile
            $profileData = [
                'user_id' => $userId,
                'full_name' => $this->request->getPost('full_name') ?: null,
                'email' => $this->request->getPost('email') ?: null,
                'phone_number' => $this->request->getPost('phone_number') ?: null
            ];

            $db->table('user_profiles')->insert($profileData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to add employee');
        }

        return redirect()->to('/owner/employees')->with('success', 'Employee added successfully and assigned to your account');
    }

    /**
     * Edit employee
     */
    public function editEmployee($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $rules = [
            'username' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'full_name' => 'permit_empty|max_length[100]',
            'email' => 'permit_empty|valid_email',
            'phone_number' => 'permit_empty|max_length[20]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        // Update user
        $userData = [
            'username' => $this->request->getPost('username')
        ];

        $this->userModel->update($id, $userData);

        // Update or insert profile
        $profileData = [
            'full_name' => $this->request->getPost('full_name') ?: null,
            'email' => $this->request->getPost('email') ?: null,
            'phone_number' => $this->request->getPost('phone_number') ?: null
        ];

        $existingProfile = $db->table('user_profiles')->where('user_id', $id)->get()->getRow();

        if ($existingProfile) {
            $db->table('user_profiles')->where('user_id', $id)->update($profileData);
        } else {
            $profileData['user_id'] = $id;
            $db->table('user_profiles')->insert($profileData);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Failed to update employee');
        }

        return redirect()->to('/owner/employees')->with('success', 'Employee updated successfully');
    }

    /**
     * Delete employee
     */
    public function deleteEmployee($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        if ($this->userModel->delete($id)) {
            return redirect()->to('/owner/employees')->with('success', 'Employee deleted successfully');
        }

        return redirect()->to('/owner/employees')->with('error', 'Failed to delete employee');
    }
}
