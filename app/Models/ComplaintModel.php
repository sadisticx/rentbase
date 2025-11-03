<?php

namespace App\Models;

use CodeIgniter\Model;

class ComplaintModel extends Model
{
    protected $table            = 'complaints';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tenant_id', 'room_id', 'subject', 'description', 'status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'tenant_id'   => 'required|integer',
        'room_id'     => 'required|integer',
        'subject'     => 'required|max_length[255]',
        'description' => 'required|max_length[5000]',
        'status'      => 'permit_empty|in_list[open,in_progress,closed]'
    ];
    protected $validationMessages   = [
        'subject' => [
            'required' => 'Subject is required'
        ],
        'description' => [
            'required' => 'Description is required'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get complaints by tenant
     */
    public function getComplaintsByTenant(int $tenantId): array
    {
        return $this->select('complaints.*, rooms.room_number')
            ->join('rooms', 'rooms.id = complaints.room_id')
            ->where('complaints.tenant_id', $tenantId)
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Get complaints by owner (all tenants' complaints for owner's rooms)
     */
    public function getComplaintsByOwner(int $ownerId): array
    {
        return $this->select('complaints.*, rooms.room_number, users.username as tenant_username')
            ->join('rooms', 'rooms.id = complaints.room_id')
            ->join('users', 'users.id = complaints.tenant_id')
            ->where('rooms.owner_id', $ownerId)
            ->orderBy('complaints.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Update complaint status
     */
    public function updateStatus(int $complaintId, string $status): bool
    {
        return $this->update($complaintId, ['status' => $status]);
    }
}
