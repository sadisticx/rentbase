<?php

namespace App\Models;

use CodeIgniter\Model;

class ParkingModel extends Model
{
    protected $table            = 'parking_slots';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['slot_number', 'owner_id', 'tenant_id'];

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
        'slot_number' => 'required|max_length[20]',
        'owner_id'    => 'required|integer'
    ];
    protected $validationMessages   = [
        'slot_number' => [
            'required' => 'Slot number is required'
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
     * Get parking slots by owner ID with tenant information
     */
    public function getSlotsByOwner(int $ownerId): array
    {
        return $this->select('parking_slots.*, users.username as tenant_username')
            ->join('users', 'users.id = parking_slots.tenant_id', 'left')
            ->where('parking_slots.owner_id', $ownerId)
            ->findAll();
    }

    /**
     * Get available parking slots (no tenant assigned)
     */
    public function getAvailableSlots(int $ownerId): array
    {
        return $this->where('owner_id', $ownerId)
            ->where('tenant_id', null)
            ->findAll();
    }

    /**
     * Assign tenant to parking slot
     */
    public function assignTenant(int $slotId, int $tenantId): bool
    {
        return $this->update($slotId, ['tenant_id' => $tenantId]);
    }

    /**
     * Remove tenant from parking slot
     */
    public function removeTenant(int $slotId): bool
    {
        return $this->update($slotId, ['tenant_id' => null]);
    }
}
