<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'rooms'; ?>
        <?= view('components/owner_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Manage Rooms</h1>
        <p class="page-subtitle">Add, edit, and manage your room inventory</p>
        
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Room List</h3>
                <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addRoomModal">
                    <i class="bi bi-plus-circle"></i> Add New Room
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
                            <th>Room Number</th>
                            <th>Assigned Tenant</th>
                            <th>Details</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rooms)): ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><strong><?= esc($room['room_number']) ?></strong></td>
                                    <td>
                                        <?php if ($room['tenant_username']): ?>
                                            <span class="badge-success"><?= esc($room['tenant_username']) ?></span>
                                        <?php else: ?>
                                            <span class="badge-custom" style="background: #f1f5f9; color: #64748b;">Unassigned</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= esc($room['details'] ?? 'No details') ?></td>
                                    <td>
                                        <button class="action-btn btn-edit" onclick="editRoom(<?= $room['id'] ?>, '<?= esc($room['room_number']) ?>', <?= $room['tenant_id'] ?? 'null' ?>, '<?= esc($room['details'] ?? '') ?>')">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <button class="action-btn btn-delete" onclick="deleteRoom(<?= $room['id'] ?>)">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center" style="padding: 40px;">
                                    <i class="bi bi-inbox" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <p style="color: #64748b; margin-top: 16px;">No rooms found. Add your first room to get started!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Room Modal -->
    <div class="modal fade" id="addRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?= base_url('owner/rooms/add') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" name="room_number" placeholder="e.g., 101" required>
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
                        
                        <div class="form-group">
                            <label class="form-label">Room Details</label>
                            <textarea class="form-control" name="details" rows="3" placeholder="Add room details, notes, or description..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-plus-circle"></i> Add Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Room Modal -->
    <div class="modal fade" id="editRoomModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Room</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editRoomForm" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Room Number</label>
                            <input type="text" class="form-control" name="room_number" id="edit_room_number" required>
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
                        
                        <div class="form-group">
                            <label class="form-label">Room Details</label>
                            <textarea class="form-control" name="details" id="edit_details" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle"></i> Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editRoom(id, roomNumber, tenantId, details) {
            document.getElementById('editRoomForm').action = '<?= base_url('owner/rooms/edit') ?>/' + id;
            document.getElementById('edit_room_number').value = roomNumber;
            document.getElementById('edit_tenant_id').value = tenantId || '';
            document.getElementById('edit_details').value = details;
            new bootstrap.Modal(document.getElementById('editRoomModal')).show();
        }
        
        function deleteRoom(id) {
            if (confirm('Are you sure you want to delete this room?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?= base_url('owner/rooms/delete') ?>/' + id;
                form.innerHTML = '<?= csrf_field() ?>';
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
