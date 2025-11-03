<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parking - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'parking'; ?>
        <?= view('components/owner_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Manage Parking</h1>
        <p class="page-subtitle">Manage parking slot allocation for tenants</p>
        
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Parking Slots</h3>
                <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addParkingModal">
                    <i class="bi bi-plus-circle"></i> Add Parking Slot
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
                            <th>Slot Number</th>
                            <th>Assigned Tenant</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($parking)): ?>
                            <?php foreach ($parking as $slot): ?>
                                <tr>
                                    <td><strong><i class="bi bi-p-square me-2"></i><?= esc($slot['slot_number']) ?></strong></td>
                                    <td>
                                        <?php if ($slot['tenant_username']): ?>
                                            <span class="badge-success"><?= esc($slot['tenant_username']) ?></span>
                                        <?php else: ?>
                                            <span class="badge-custom" style="background: #f1f5f9; color: #64748b;">Available</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($slot['tenant_username']): ?>
                                            <span class="badge-danger">Occupied</span>
                                        <?php else: ?>
                                            <span class="badge-success">Available</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="editParking(<?= $slot['id'] ?>, '<?= esc($slot['slot_number']) ?>', <?= $slot['tenant_id'] ?? 'null' ?>)">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="action-btn btn-delete" onclick="deleteParking(<?= $slot['id'] ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center" style="padding: 40px;">
                                    <i class="bi bi-car-front" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <p style="color: #64748b; margin-top: 16px;">No parking slots found. Add your first slot to get started!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Parking Modal -->
    <div class="modal fade" id="addParkingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Parking Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('owner/parking/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Slot Number *</label>
                            <input type="text" class="form-control" name="slot_number" placeholder="e.g., A-101" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Assign Tenant (Optional)</label>
                            <select class="form-select" name="tenant_id">
                                <option value="">-- Select Tenant --</option>
                                <?php if (!empty($tenants)): ?>
                                    <?php foreach ($tenants as $tenant): ?>
                                        <option value="<?= $tenant['id'] ?>"><?= esc($tenant['username']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-plus-circle"></i> Add Slot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Parking Modal -->
    <div class="modal fade" id="editParkingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Parking Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editParkingForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Slot Number *</label>
                            <input type="text" class="form-control" name="slot_number" id="edit_slot_number" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Assign Tenant</label>
                            <select class="form-select" name="tenant_id" id="edit_tenant_id">
                                <option value="">-- Select Tenant --</option>
                                <?php if (!empty($tenants)): ?>
                                    <?php foreach ($tenants as $tenant): ?>
                                        <option value="<?= $tenant['id'] ?>"><?= esc($tenant['username']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle"></i> Update Slot
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editParking(id, slotNumber, tenantId) {
            document.getElementById('editParkingForm').action = '<?= base_url('owner/parking/edit') ?>/' + id;
            document.getElementById('edit_slot_number').value = slotNumber;
            document.getElementById('edit_tenant_id').value = tenantId || '';
            new bootstrap.Modal(document.getElementById('editParkingModal')).show();
        }
        
        function deleteParking(id) {
            if (confirm('Are you sure you want to delete this parking slot?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('owner/parking/delete') ?>/' + id;
                form.innerHTML = '<?= csrf_field() ?>';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
