<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Redirect root to login
$routes->get('/', 'Auth::login');

// Auth routes
$routes->group('auth', function($routes) {
    $routes->get('login', 'Auth::login');
    $routes->post('processLogin', 'Auth::processLogin');
    $routes->get('register', 'Auth::register');
    $routes->post('processRegister', 'Auth::processRegister');
    $routes->get('logout', 'Auth::logout');
});

// Owner routes
$routes->group('owner', function($routes) {
    $routes->get('dashboard', 'Owner::dashboard');
    
    // Rooms
    $routes->get('rooms', 'Owner::rooms');
    $routes->post('rooms/add', 'Owner::addRoom');
    $routes->post('rooms/edit/(:num)', 'Owner::editRoom/$1');
    $routes->get('rooms/delete/(:num)', 'Owner::deleteRoom/$1');
    
    // Tenants
    $routes->get('tenants', 'Owner::tenants');
    $routes->post('tenants/add', 'Owner::addTenant');
    $routes->post('tenants/edit/(:num)', 'Owner::editTenant/$1');
    $routes->get('tenants/delete/(:num)', 'Owner::deleteTenant/$1');
    
    // Employees
    $routes->get('employees', 'Owner::employees');
    $routes->post('employees/add', 'Owner::addEmployee');
    $routes->post('employees/edit/(:num)', 'Owner::editEmployee/$1');
    $routes->post('employees/delete/(:num)', 'Owner::deleteEmployee/$1');
    
    // Parking
    $routes->get('parking', 'Owner::parking');
    $routes->post('parking/add', 'Owner::addParking');
    $routes->post('parking/edit/(:num)', 'Owner::editParking/$1');
    $routes->get('parking/delete/(:num)', 'Owner::deleteParking/$1');
    
    // Complaints
    $routes->get('complaints', 'Owner::complaints');
    $routes->post('complaints/update/(:num)', 'Owner::updateComplaintStatus/$1');
    $routes->get('complaints/getReplies/(:num)', 'Owner::getReplies/$1');
    $routes->post('complaints/reply/(:num)', 'Owner::addReply/$1');
});

// Tenant routes
$routes->group('tenant', function($routes) {
    $routes->get('dashboard', 'Tenant::dashboard');
    
    // Complaints
    $routes->get('complaints', 'Tenant::complaints');
    $routes->post('complaints/submit', 'Tenant::submitComplaint');
    $routes->get('complaints/getReplies/(:num)', 'Tenant::getReplies/$1');
    $routes->post('complaints/reply/(:num)', 'Tenant::addReply/$1');
    
    // Payments
    $routes->get('payments', 'Tenant::payments');
    $routes->post('payments/process', 'Tenant::processPayment');
});

// Employee routes
$routes->group('employee', function($routes) {
    $routes->get('dashboard', 'Employee::dashboard');
    
    // Complaints
    $routes->get('complaints', 'Employee::complaints');
    $routes->post('complaints/update/(:num)', 'Employee::updateComplaint/$1');
    $routes->get('complaints/getReplies/(:num)', 'Employee::getReplies/$1');
    $routes->post('complaints/reply/(:num)', 'Employee::addReply/$1');
});
