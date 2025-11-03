<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'complaints'; ?>
        <?= view('components/owner_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Tenant Complaints</h1>
        <p class="page-subtitle">Review and manage tenant complaints and issues</p>
        
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">All Complaints</h3>
                <div>
                    <span class="badge-warning me-2"><i class="bi bi-clock"></i> <?= count(array_filter($complaints ?? [], fn($c) => $c['status'] === 'open')) ?> Open</span>
                    <span class="badge-primary me-2"><i class="bi bi-hourglass-split"></i> <?= count(array_filter($complaints ?? [], fn($c) => $c['status'] === 'in_progress')) ?> In Progress</span>
                    <span class="badge-success"><i class="bi bi-check-circle"></i> <?= count(array_filter($complaints ?? [], fn($c) => $c['status'] === 'closed')) ?> Closed</span>
                </div>
            </div>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Room</th>
                            <th>Subject</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($complaints)): ?>
                            <?php foreach ($complaints as $complaint): ?>
                                <tr>
                                    <td><strong><?= esc($complaint['tenant_username']) ?></strong></td>
                                    <td><span class="badge-primary"><?= esc($complaint['room_number']) ?></span></td>
                                    <td><?= esc($complaint['subject']) ?></td>
                                    <td style="max-width: 300px;"><?= esc(substr($complaint['description'], 0, 100)) ?><?= strlen($complaint['description']) > 100 ? '...' : '' ?></td>
                                    <td>
                                        <?php if ($complaint['status'] === 'open'): ?>
                                            <span class="badge-warning"><i class="bi bi-exclamation-circle"></i> Open</span>
                                        <?php elseif ($complaint['status'] === 'in_progress'): ?>
                                            <span class="badge-primary"><i class="bi bi-hourglass-split"></i> In Progress</span>
                                        <?php else: ?>
                                            <span class="badge-success"><i class="bi bi-check-circle"></i> Closed</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($complaint['created_at'])) ?></td>
                                    <td>
                                        <button class="action-btn btn-view" onclick="viewComplaint(<?= $complaint['id'] ?>, '<?= esc($complaint['tenant_username']) ?>', '<?= esc($complaint['room_number']) ?>', '<?= esc($complaint['subject']) ?>', '<?= esc($complaint['description']) ?>', '<?= date('M d, Y H:i', strtotime($complaint['created_at'])) ?>')">
                                            <i class="bi bi-eye"></i> View & Reply
                                        </button>
                                        <button class="action-btn btn-edit" onclick="updateStatus(<?= $complaint['id'] ?>, '<?= $complaint['status'] ?>')">
                                            <i class="bi bi-arrow-repeat"></i> Update
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center" style="padding: 40px;">
                                    <i class="bi bi-chat-left-text" style="font-size: 48px; color: #cbd5e1;"></i>
                                    <p style="color: #64748b; margin-top: 16px;">No complaints found. Your tenants are happy!</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- View Complaint & Replies Modal -->
    <div class="modal fade" id="viewComplaintModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complaint Details & Replies</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Tenant:</strong></label>
                                <p id="view_tenant"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label"><strong>Room:</strong></label>
                                <p id="view_room"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><strong>Submitted:</strong></label>
                                <p id="view_date"></p>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Subject:</strong></label>
                        <p id="view_subject"></p>
                    </div>
                    <div class="mb-4">
                        <label class="form-label"><strong>Description:</strong></label>
                        <p id="view_description" style="white-space: pre-wrap;"></p>
                    </div>
                    
                    <hr>
                    
                    <h6 style="margin-bottom: 16px; font-weight: 600;">Replies</h6>
                    <div id="replies_container" style="max-height: 300px; overflow-y: auto; margin-bottom: 20px;">
                        <!-- Replies will be loaded here -->
                    </div>
                    
                    <form id="replyForm" method="post">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label class="form-label">Add Reply</label>
                            <textarea class="form-control" name="reply_message" rows="3" placeholder="Type your reply here..." required></textarea>
                        </div>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-send"></i> Send Reply
                        </button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Update Status Modal -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Complaint Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="updateStatusForm" method="post">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Select Status</label>
                            <select class="form-select" name="status" id="status_select" required>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom">
                            <i class="bi bi-check-circle"></i> Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentComplaintId = null;
        
        function viewComplaint(id, tenant, room, subject, description, date) {
            currentComplaintId = id;
            document.getElementById('view_tenant').textContent = tenant;
            document.getElementById('view_room').textContent = room;
            document.getElementById('view_subject').textContent = subject;
            document.getElementById('view_description').textContent = description;
            document.getElementById('view_date').textContent = date;
            document.getElementById('replyForm').action = '<?= base_url('owner/complaints/reply') ?>/' + id;
            
            // Load replies
            loadReplies(id);
            
            new bootstrap.Modal(document.getElementById('viewComplaintModal')).show();
        }
        
        function loadReplies(complaintId) {
            fetch('<?= base_url('owner/complaints/getReplies') ?>/' + complaintId)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('replies_container');
                    if (data.replies && data.replies.length > 0) {
                        container.innerHTML = data.replies.map(reply => `
                            <div style="background: #f8fafc; padding: 12px 16px; border-radius: 8px; margin-bottom: 12px; border-left: 3px solid #6366f1;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                    <strong style="color: #1e293b;">${reply.username}</strong>
                                    <span style="color: #64748b; font-size: 12px;">${reply.created_at}</span>
                                </div>
                                <p style="margin: 0; color: #475569;">${reply.message}</p>
                            </div>
                        `).join('');
                    } else {
                        container.innerHTML = '<p style="color: #64748b; text-align: center; padding: 20px;">No replies yet. Be the first to reply!</p>';
                    }
                });
        }
        
        function updateStatus(id, currentStatus) {
            document.getElementById('updateStatusForm').action = '<?= base_url('owner/complaints/update') ?>/' + id;
            document.getElementById('status_select').value = currentStatus;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }
        
        // Reload replies after submitting
        document.getElementById('replyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.reset();
                    loadReplies(currentComplaintId);
                    alert('Reply sent successfully!');
                } else {
                    alert('Failed to send reply. Please try again.');
                }
            });
        });
    </script>
</body>
</html>
