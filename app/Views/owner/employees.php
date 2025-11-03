<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'employees'; ?>
        <?= view('components/owner_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Manage Employees</h1>
        <p class="page-subtitle">Add and manage employee accounts for your property</p>
        
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Employee List</h3>
                <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    <i class="bi bi-person-plus-fill"></i> Add New Employee
                </button>
            </div>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($employees)): ?>
                            <?php foreach ($employees as $employee): ?>
                                <tr>
                                    <td><strong><?= esc($employee['username']) ?></strong></td>
                                    <td><?= esc($employee['full_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($employee['email'] ?? 'N/A') ?></td>
                                    <td><?= esc($employee['phone_number'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($employee['created_at'])) ?></td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="editEmployee(<?= $employee['id'] ?>, '<?= esc($employee['username']) ?>', '<?= esc($employee['full_name'] ?? '') ?>', '<?= esc($employee['email'] ?? '') ?>', '<?= esc($employee['phone_number'] ?? '') ?>')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="action-btn btn-delete" onclick="deleteEmployee(<?= $employee['id'] ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 40px;">
                                    <i class="bi bi-person-badge" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <p style="color: #64748b; margin-top: 16px;">No employees found. Add your first employee to get started!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Employee Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('owner/employees/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Username *</label>
                            <input type="text" class="form-control" name="username" placeholder="Enter username" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" placeholder="Enter password" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" placeholder="Enter full name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter email address">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" placeholder="Enter phone number">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle"></i> Add Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editEmployeeForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Username *</label>
                            <input type="text" class="form-control" name="username" id="edit_username" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="full_name" id="edit_full_name">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit_email">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" class="form-control" name="phone_number" id="edit_phone_number">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle"></i> Update Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editEmployee(id, username, fullName, email, phone) {
            document.getElementById('editEmployeeForm').action = '<?= base_url('owner/employees/edit') ?>/' + id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_full_name').value = fullName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone_number').value = phone;
            new bootstrap.Modal(document.getElementById('editEmployeeModal')).show();
        }
        
        function deleteEmployee(id) {
            if (confirm('Are you sure you want to delete this employee? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('owner/employees/delete') ?>/' + id;
                form.innerHTML = '<?= csrf_field() ?>';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
