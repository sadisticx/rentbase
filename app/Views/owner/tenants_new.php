<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tenants - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'tenants'; ?>
        <?= view('components/owner_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Manage Tenants</h1>
        <p class="page-subtitle">Add, edit, and manage tenant accounts</p>
        
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Tenant List</h3>
                <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addTenantModal">
                    <i class="bi bi-person-plus"></i> Add New Tenant
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
                        <?php if (!empty($tenants)): ?>
                            <?php foreach ($tenants as $tenant): ?>
                                <tr>
                                    <td><strong><?= esc($tenant['username']) ?></strong></td>
                                    <td><?= esc($tenant['full_name'] ?? 'N/A') ?></td>
                                    <td><?= esc($tenant['email'] ?? 'N/A') ?></td>
                                    <td><?= esc($tenant['phone_number'] ?? 'N/A') ?></td>
                                    <td><?= date('M d, Y', strtotime($tenant['created_at'])) ?></td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="editTenant(<?= $tenant['id'] ?>, '<?= esc($tenant['username']) ?>', '<?= esc($tenant['full_name'] ?? '') ?>', '<?= esc($tenant['email'] ?? '') ?>', '<?= esc($tenant['phone_number'] ?? '') ?>')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="action-btn btn-delete" onclick="deleteTenant(<?= $tenant['id'] ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center" style="padding: 40px;">
                                    <i class="bi bi-people" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <p style="color: #64748b; margin-top: 16px;">No tenants found. Add your first tenant to get started!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Tenant Modal -->
    <div class="modal fade" id="addTenantModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('owner/tenants/add') ?>" method="post">
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
                            <i class="bi bi-person-plus"></i> Add Tenant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Tenant Modal -->
    <div class="modal fade" id="editTenantModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tenant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editTenantForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" id="edit_username" readonly style="background: #f8fafc;">
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
                            <i class="bi bi-check-circle"></i> Update Tenant
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editTenant(id, username, fullName, email, phone) {
            document.getElementById('editTenantForm').action = '<?= base_url('owner/tenants/edit') ?>/' + id;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_full_name').value = fullName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone_number').value = phone;
            new bootstrap.Modal(document.getElementById('editTenantModal')).show();
        }
        
        function deleteTenant(id) {
            if (confirm('Are you sure you want to delete this tenant? This will also remove all their data.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('owner/tenants/delete') ?>/' + id;
                form.innerHTML = '<?= csrf_field() ?>';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
