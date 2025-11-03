<?php

namespace App\Controllers;

use App\Models\ComplaintModel;
use App\Models\ComplaintReplyModel;

class Employee extends BaseController
{
    protected $session;
    protected $complaintModel;
    protected $replyModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->complaintModel = new ComplaintModel();
        $this->replyModel = new ComplaintReplyModel();
    }

    /**
     * Check if user is authenticated and has employee role
     */
    private function checkAuth()
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'employee') {
            return redirect()->to('/auth/login')->with('error', 'Access Denied');
        }
        return null;
    }

    /**
     * Employee dashboard
     */
    public function dashboard()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;

        $ownerId = $this->session->get('owner_id');
        
        // Get owner information
        $ownerName = null;
        if ($ownerId) {
            $userModel = new \App\Models\UserModel();
            $owner = $userModel->find($ownerId);
            $ownerName = $owner['username'] ?? null;
        }
        
        // Check if employee has owner assigned
        if (!$ownerId) {
            $data = [
                'title' => 'Employee Dashboard',
                'username' => $this->session->get('username'),
                'ownerName' => null,
                'totalComplaints' => 0,
                'openComplaints' => 0,
                'inProgressComplaints' => 0,
                'closedComplaints' => 0
            ];
            return view('employee/dashboard', $data);
        }
        
        // Get statistics
        $complaintsAll = $this->complaintModel->getComplaintsByOwner($ownerId);
        $openComplaints = count(array_filter($complaintsAll, fn($c) => $c['status'] === 'open'));
        $inProgressComplaints = count(array_filter($complaintsAll, fn($c) => $c['status'] === 'in_progress'));
        $closedComplaints = count(array_filter($complaintsAll, fn($c) => $c['status'] === 'closed'));

        $data = [
            'title' => 'Employee Dashboard',
            'username' => $this->session->get('username'),
            'ownerName' => $ownerName,
            'totalComplaints' => count($complaintsAll),
            'openComplaints' => $openComplaints,
            'inProgressComplaints' => $inProgressComplaints,
            'closedComplaints' => $closedComplaints
        ];

        return view('employee/dashboard', $data);
    }
    
    /**
     * View and manage complaints
     */
    public function complaints()
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;
        
        $ownerId = $this->session->get('owner_id');
        $username = $this->session->get('username');
        
        // Check if employee has owner assigned
        if (!$ownerId) {
            return view('employee/complaints', [
                'username' => $username,
                'complaints' => []
            ]);
        }
        
        $complaints = $this->complaintModel->getComplaintsByOwner($ownerId);
        
        return view('employee/complaints', [
            'username' => $username,
            'complaints' => $complaints
        ]);
    }
    
    /**
     * Update complaint status
     */
    public function updateComplaint($id)
    {
        $redirect = $this->checkAuth();
        if ($redirect) return $redirect;
        
        $status = $this->request->getPost('status');
        
        $this->complaintModel->update($id, ['status' => $status]);
        
        return redirect()->to('/employee/complaints')->with('success', 'Complaint status updated successfully!');
    }
    
    /**
     * Get complaint replies (AJAX)
     */
    public function getReplies($complaintId)
    {
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'employee') {
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
        if (!$this->session->get('user_id') || $this->session->get('role') !== 'employee') {
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
}
