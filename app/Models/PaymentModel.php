<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentModel extends Model
{
    protected $table            = 'payments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['tenant_id', 'amount', 'payment_method', 'reference_number', 'payment_date', 'notes'];

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
        'tenant_id'    => 'required|integer',
        'amount'       => 'required|decimal|greater_than[0]',
        'payment_date' => 'required|valid_date',
        'notes'        => 'permit_empty|max_length[500]'
    ];
    protected $validationMessages   = [
        'amount' => [
            'required'     => 'Amount is required',
            'greater_than' => 'Amount must be greater than 0'
        ],
        'payment_date' => [
            'required' => 'Payment date is required'
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
     * Get payments by tenant
     */
    public function getPaymentsByTenant(int $tenantId): array
    {
        return $this->where('tenant_id', $tenantId)
            ->orderBy('payment_date', 'DESC')
            ->findAll();
    }

    /**
     * Get total paid by tenant
     */
    public function getTotalPaidByTenant(int $tenantId): float
    {
        $result = $this->selectSum('amount')
            ->where('tenant_id', $tenantId)
            ->first();
        
        return (float) ($result['amount'] ?? 0);
    }
}
