<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        
        // Check if user is logged in
        if (!$session->get('logged_in')) {
            return redirect()->to('/auth/login')->with('error', 'Please login to continue');
        }
        
        // If role argument is provided, check role
        if ($arguments && !empty($arguments)) {
            $requiredRole = $arguments[0];
            $userRole = $session->get('role');
            
            if ($userRole !== $requiredRole) {
                return redirect()->to('/auth/login')->with('error', 'Access Denied');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after
    }
}
