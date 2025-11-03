<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $session;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // Redirect if already logged in
        if ($this->session->get('user_id')) {
            return $this->redirectToDashboard($this->session->get('role'));
        }

        $data = [
            'title' => 'Login to RentBase',
            'error' => $this->request->getGet('error'),
            'message' => $this->request->getGet('message')
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function processLogin()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/auth/login')->with('error', 'Username and password are required');
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->verifyLogin($username, $password);

        if ($user) {
            $sessionData = [
                'user_id'  => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role'],
                'logged_in' => true
            ];
            
            // Set owner_id for employees and tenants if applicable
            if (isset($user['owner_id']) && $user['owner_id']) {
                $sessionData['owner_id'] = $user['owner_id'];
            }
            
            $this->session->set($sessionData);

            return $this->redirectToDashboard($user['role']);
        }

        return redirect()->to('/auth/login')->with('error', 'Invalid username or password');
    }

    /**
     * Display registration page
     */
    public function register()
    {
        // Redirect if already logged in
        if ($this->session->get('user_id')) {
            return $this->redirectToDashboard($this->session->get('role'));
        }

        $data = [
            'title' => 'Register',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/register', $data);
    }

    /**
     * Process registration
     */
    public function processRegister()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'password'         => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
            'role'            => 'required|in_list[owner,tenant,employee]'
        ];

        $messages = [
            'username' => [
                'required'   => 'Username is required',
                'is_unique'  => 'Username is already taken. Please choose a different one.',
                'min_length' => 'Username must be at least 3 characters'
            ],
            'password' => [
                'required'   => 'Password is required',
                'min_length' => 'Password must be at least 6 characters'
            ],
            'confirm_password' => [
                'required' => 'Please confirm your password',
                'matches'  => 'Passwords do not match'
            ],
            'role' => [
                'required' => 'Please select a role',
                'in_list'  => 'Invalid role selected'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'role'     => $this->request->getPost('role')
        ];

        if ($this->userModel->insert($data)) {
            return redirect()->to('/auth/login')->with('message', 'Registration successful. Please login.');
        }

        return redirect()->back()->withInput()->with('error', 'Registration failed. Please try again.');
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to('/auth/login')->with('message', 'Logged out successfully');
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'owner':
                return redirect()->to('/owner/dashboard');
            case 'tenant':
                return redirect()->to('/tenant/dashboard');
            case 'employee':
                return redirect()->to('/employee/dashboard');
            default:
                return redirect()->to('/auth/login');
        }
    }
}
