<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table            = 'rooms';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['room_number', 'owner_id', 'tenant_id', 'details'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'room_number' => 'required|max_length[20]',
        'owner_id'    => 'required|integer',
        'details'     => 'permit_empty|max_length[1000]'
    ];
    protected $validationMessages   = [
        'room_number' => [
            'required' => 'Room number is required'
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
     * Get rooms by owner ID with tenant information
     */
    public function getRoomsByOwner(int $ownerId): array
    {
        return $this->select('rooms.*, users.username as tenant_username')
            ->join('users', 'users.id = rooms.tenant_id', 'left')
            ->where('rooms.owner_id', $ownerId)
            ->findAll();
    }

    /**
     * Get available rooms (no tenant assigned)
     */
    public function getAvailableRooms(int $ownerId): array
    {
        return $this->where('owner_id', $ownerId)
            ->where('tenant_id', null)
            ->findAll();
    }

    /**
     * Get room with tenant details
     */
    public function getRoomWithTenant(int $roomId): ?array
    {
        return $this->select('rooms.*, users.username as tenant_username, users.id as tenant_user_id')
            ->join('users', 'users.id = rooms.tenant_id', 'left')
            ->find($roomId);
    }

    /**
     * Assign tenant to room
     */
    public function assignTenant(int $roomId, int $tenantId): bool
    {
        return $this->update($roomId, ['tenant_id' => $tenantId]);
    }

    /**
     * Remove tenant from room
     */
    public function removeTenant(int $roomId): bool
    {
        return $this->update($roomId, ['tenant_id' => null]);
    }
}
