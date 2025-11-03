<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ComplaintModel;
use App\Models\PaymentModel;
use App\Models\RoomModel;
use App\Models\ComplaintReplyModel;

class Tenant extends BaseController
{
    protected $session;
    protected $userModel;
    protected $complaintModel;
    protected $paymentModel;
    protected $roomModel;
    protected $replyModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->userModel = new UserModel();
        $this->complaintModel = new ComplaintModel();
        $this->paymentModel = new PaymentModel();
        $this->roomModel = new RoomModel();
        $this->replyModel = new ComplaintReplyModel();
    }

    /**
     * Check if user is authenticated and has tenant role
     */
    private function checkAuth()
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'tenant') {
            return redirect()->to('/auth/login')->with('error', 'Access Denied');
        }
        return null;
    }

    /**
     * Tenant dashboard
     */
    public function dashboard()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $tenantId = $this->session->get('user_id');
        
        // Get tenant's room
        $room = $this->roomModel->where('tenant_id', $tenantId)->first();
        
        // Get payments count
        $payments = $this->paymentModel->where('tenant_id', $tenantId)->findAll();
        $payments_count = count($payments);
        
        // Get active complaints count (open + in_progress)
        $complaints = $this->complaintModel->getComplaintsByTenant($tenantId);
        $complaints_count = count(array_filter($complaints, fn($c) => $c['status'] === 'open' || $c['status'] === 'in_progress'));
        
        // Get parking slot if assigned
        $parking = null;
        if ($room) {
            $parkingModel = new \App\Models\ParkingModel();
            $parking = $parkingModel->where('tenant_id', $tenantId)->first();
        }
        
        // Get recent payments
        $recent_payments = array_slice($payments, 0, 5);

        $data = [
            'title' => 'Tenant Dashboard',
            'username' => $this->session->get('username'),
            'room' => $room,
            'payments_count' => $payments_count,
            'complaints_count' => $complaints_count,
            'parking' => $parking,
            'recent_payments' => $recent_payments
        ];

        return view('tenant/dashboard', $data);
    }

    /**
     * Manage complaints
     */
    public function complaints()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $tenantId = $this->session->get('user_id');
        $complaints = $this->complaintModel->getComplaintsByTenant($tenantId);
        
        // Get tenant's room
        $room = $this->roomModel->where('tenant_id', $tenantId)->first();

        $data = [
            'title' => 'Manage Complaints',
            'username' => $this->session->get('username'),
            'complaints' => $complaints,
            'room' => $room
        ];

        return view('tenant/complaints', $data);
    }

    /**
     * Submit new complaint
     */
    public function submitComplaint()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $tenantId = $this->session->get('user_id');
        
        // Get tenant's room
        $room = $this->roomModel->where('tenant_id', $tenantId)->first();
        
        if (!$room) {
            return redirect()->back()->with('error', 'You must be assigned to a room to submit complaints');
        }

        $rules = [
            'subject' => 'required|max_length[255]',
            'description' => 'required|max_length[5000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'tenant_id' => $tenantId,
            'room_id' => $room['id'],
            'subject' => $this->request->getPost('subject'),
            'description' => $this->request->getPost('description'),
            'status' => 'open'
        ];

        if ($this->complaintModel->insert($data)) {
            return redirect()->to('/tenant/complaints')->with('success', 'Complaint submitted successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to submit complaint');
    }

    /**
     * Get complaint replies (AJAX)
     */
    public function getReplies($complaintId)
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'tenant') {
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
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'tenant') {
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
     * Make payments
     */
    public function payments()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $tenantId = $this->session->get('user_id');
        $payments = $this->paymentModel->getPaymentsByTenant($tenantId);
        $totalPaid = $this->paymentModel->getTotalPaidByTenant($tenantId);

        $data = [
            'title' => 'Make Payments',
            'username' => $this->session->get('username'),
            'payments' => $payments,
            'totalPaid' => $totalPaid
        ];

        return view('tenant/payments', $data);
    }

    /**
     * Process payment (mock) - Returns JSON for AJAX
     */
    public function processPayment()
    {
        $redirect = $this->checkAuth();
        if ($redirect) {
            return $this->response->setJSON(['success' => false, 'message' => 'Authentication required']);
        }

        // Use isAJAX() to detect AJAX requests and check if it's POST
        if (!$this->request->isAJAX() && strtolower($this->request->getMethod()) !== 'post') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }

        $rules = [
            'amount' => 'required|decimal|greater_than[0]',
            'payment_method' => 'required|in_list[cash,bank_transfer,gcash,paymaya]',
            'notes' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Generate reference number
        $referenceNumber = 'PAY' . date('YmdHis') . rand(1000, 9999);

        $data = [
            'tenant_id' => $this->session->get('user_id'),
            'amount' => $this->request->getPost('amount'),
            'payment_method' => $this->request->getPost('payment_method'),
            'payment_date' => date('Y-m-d H:i:s'),
            'reference_number' => $referenceNumber,
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->paymentModel->insert($data)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment processed successfully',
                'reference_number' => $referenceNumber
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to process payment'
        ]);
    }
}
