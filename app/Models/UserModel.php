<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['username', 'password', 'role', 'owner_id'];

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
        'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username,id,{id}]',
        'password' => 'required|min_length[6]',
        'role'     => 'required|in_list[owner,tenant,employee]'
    ];
    protected $validationMessages   = [
        'username' => [
            'required'   => 'Username is required',
            'is_unique'  => 'Username already exists',
            'min_length' => 'Username must be at least 3 characters'
        ],
        'password' => [
            'required'   => 'Password is required',
            'min_length' => 'Password must be at least 6 characters'
        ],
        'role' => [
            'required' => 'Role is required',
            'in_list'  => 'Invalid role selected'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Hash password before inserting or updating
     */
    protected function hashPassword(array $data): array
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_BCRYPT);
        }
        return $data;
    }

    /**
     * Verify user credentials
     */
    public function verifyLogin(string $username, string $password): ?array
    {
        $user = $this->where('username', $username)->first();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return null;
    }

    /**
     * Get user by username
     */
    public function getUserByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Get tenant details with room and parking info
     */
    public function getTenantDetails(int $userId): ?array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        
        return $builder
            ->select('up.full_name, up.email, up.phone_number, r.room_number, r.details AS room_details, ps.slot_number')
            ->join('user_profiles up', 'u.id = up.user_id', 'left')
            ->join('rooms r', 'u.id = r.tenant_id', 'left')
            ->join('parking_slots ps', 'u.id = ps.tenant_id', 'left')
            ->where('u.id', $userId)
            ->get()
            ->getRowArray();
    }

    /**
     * Get all tenants with their profile information for a specific owner
     */
    public function getTenantsWithProfiles(int $ownerId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        
        $builder->select('u.id, u.username, u.role, u.created_at, up.full_name, up.email, up.phone_number')
            ->join('user_profiles up', 'u.id = up.user_id', 'left')
            ->where('u.role', 'tenant');
        
        // Filter by owner if specified
        if ($ownerId !== null) {
            $builder->where('u.owner_id', $ownerId);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get all employees with their profile information for a specific owner
     */
    public function getEmployeesWithProfiles(int $ownerId = null): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users u');
        
        $builder->select('u.id, u.username, u.role, u.created_at, up.full_name, up.email, up.phone_number')
            ->join('user_profiles up', 'u.id = up.user_id', 'left')
            ->where('u.role', 'employee');
        
        // Filter by owner if specified
        if ($ownerId !== null) {
            $builder->where('u.owner_id', $ownerId);
        }
        
        return $builder->get()->getResultArray();
    }
}
