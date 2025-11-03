<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintReplyModel extends Model
{
    protected $table = 'complaint_replies';
    protected $primaryKey = 'id';
    protected $allowedFields = ['complaint_id', 'user_id', 'message', 'created_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

    public function getRepliesWithUsers($complaintId)
    {
        return $this->select('complaint_replies.*, users.username, user_profiles.full_name')
            ->join('users', 'users.id = complaint_replies.user_id')
            ->join('user_profiles', 'user_profiles.user_id = users.id', 'left')
            ->where('complaint_replies.complaint_id', $complaintId)
            ->orderBy('complaint_replies.created_at', 'ASC')
            ->findAll();
    }
}
