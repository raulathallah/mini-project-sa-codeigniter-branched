<?php

namespace App\Models;

use App\Libraries\DataParams;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'accounts';
    protected $primaryKey       = 'account_id';
    protected $useAutoIncrement = true;
    //protected $returnType       = 'array';
    protected $returnType       = \App\Entities\User::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'username',
        'email',
        //'password',
        'full_name',
        'role',
        'status',
        'last_login'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        //'username'      => 'required|is_unique[users.username]|min_length[3]',
        //'email'         => 'required|is_unique[users.email]|valid_email',
        //'password'      => 'required|min_length[8]',
        //'full_name'     => 'required',
        //'role'          => 'required',
    ];

    protected $validationMessages   = [
        'username' => [
            'required' => 'Username is required',
            'is_unique' => 'Username already exist',
            'min_length' => 'Username must be minimum 3 character',
        ],
        'email' => [
            'required' => 'Email is required',
            'is_unique' => 'Email already exist',
            'valid_email' => 'Email is not valid',
        ],
        // 'password' => [
        //     'required' => 'Password is required',
        //     'min_length' => 'Password must be minimum 8 character '
        // ],
        // 'full_name' => [
        //     'required' => 'Full name is required',
        // ],
        // 'role' => [
        //     'required' => 'Role is required',
        // ],
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

    public function findActiveUsers()
    {
        return $this->where('status', 'active')->countAllResults();
    }

    public function getTotalUsers()
    {
        return $this->countAllResults();
    }

    public function getNewUsersThisMonth()
    {
        $currTime = Time::now();
        $startOfMonth = Time::createFromTimestamp(strtotime($currTime->format('Y-m-01 00:00:00')));
        $endOfMonth = Time::createFromTimestamp(strtotime($currTime->format('Y-m-t 23:59:59')));
        return $this
            ->where('created_at >=', $startOfMonth)
            ->where('created_at <=', $endOfMonth)
            ->countAllResults();
    }

    public function updateLastLogin($user_id)
    {
        return $this->update($user_id, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function getFormattedLastLogin($user_id)
    {
        $user = $this->find($user_id);

        if ($user && $user['last_login']) {
            $lastLoginTime = Time::parse($user['last_login']);
            return $lastLoginTime->format('F j, Y g:i A');
        }

        return 'Never logged in';
    }

    public function setValidationRules($user_id = null)
    {
        // If updating, replace {id} with the user ID
        if ($user_id) {
            $this->validationRules['email'] = str_replace('{id}', $user_id, $this->validationRules['email']);
            $this->validationRules['username'] = str_replace('{id}', $user_id, $this->validationRules['username']);
        }
    }

    public function getFilteredUsers(DataParams $params)
    {
        if (!empty($params->search)) { // Apply search
            $this->groupStart()
                ->like('account_id::text', '%' . $params->search . '%', 'both', null, true)
                ->orLike('full_name', $params->search, 'both', null, true)
                ->orLike('username', $params->search, 'both', null, true)
                ->orLike('email', $params->search, 'both', null, true)
                ->groupEnd();
        }

        if (!empty($params->role)) {
            $this->where('role', $params->role);
        }

        if (!empty($params->status)) {
            $this->where('status', $params->status);
        }

        // Apply sort
        $allowedSortColumns = ['username', 'email', 'last_login'];
        $sort = in_array($params->sort, $allowedSortColumns) ? $params->sort : 'account_id';
        $order = ($params->order === 'desc') ? 'desc' : 'asc';
        $this->orderBy($sort, $order);

        $result = [
            'accounts' => $this->paginate($params->perPage, 'accounts', $params->page),
            'pager' => $this->pager,
            'total' => $this->countAllResults(false)
        ];
        return $result;
    }

    public function getAllRoles()
    {
        $role = $this->select('role')->distinct()->findAll();
        return array_column($role, 'role');
    }

    public function getAllStatus()
    {
        //$role = $this->select('role')->distinct()->findAll();
        //return array_column($role, 'role');
        return ['active', 'inactive'];
    }
}
