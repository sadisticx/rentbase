<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <?php $active = 'dashboard'; ?>
    <?= view('components/tenant_navbar', ['username' => $username, 'active' => $active]) ?>
    
    <div class="main-wrapper">
        <h1 class="page-title">Welcome, <?= esc($username) ?></h1>
        <p class="page-subtitle">Your tenant dashboard overview</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i class="bi bi-door-open"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= esc($room['room_number'] ?? 'N/A') ?></div>
                    <div class="stat-label">My Room</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= $payments_count ?? 0 ?></div>
                    <div class="stat-label">Payments Made</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="bi bi-chat-left-text"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= $complaints_count ?? 0 ?></div>
                    <div class="stat-label">Active Complaints</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="bi bi-car-front"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= esc($parking['slot_number'] ?? 'None') ?></div>
                    <div class="stat-label">Parking Slot</div>
                </div>
            </div>
        </div>
        
        <div class="row" style="margin-top: 32px;">
            <div class="col-md-6">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Room Details</h3>
                    </div>
                    <?php if (!empty($room)): ?>
                        <div style="padding: 20px;">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569;">Room Number</label>
                                <p style="color: #1e293b; font-size: 18px;"><?= esc($room['room_number']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569;">Monthly Rate</label>
                                <p style="color: #1e293b; font-size: 18px;">₱<?= number_format($room['monthly_rate'], 2) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569;">Occupancy</label>
                                <p style="color: #1e293b; font-size: 18px;"><?= esc($room['occupancy_count']) ?> / <?= esc($room['max_occupancy']) ?> persons</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #475569;">Status</label>
                                <p><span class="badge-success"><i class="bi bi-check-circle"></i> Occupied</span></p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="padding: 40px; text-align: center;">
                            <i class="bi bi-house-slash" style="font-size: 48px; color: #cbd5e1;"></i>
                            <p style="color: #64748b; margin-top: 16px;">No room assigned</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Quick Actions</h3>
                    </div>
                    <div class="actions-grid">
                        <a href="<?= base_url('tenant/payments') ?>" class="action-card">
                            <div class="action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="bi bi-credit-card"></i>
                            </div>
                            <div class="action-content">
                                <div class="action-title">Make Payment</div>
                                <div class="action-subtitle">Submit your rent payment</div>
                            </div>
                            <i class="bi bi-chevron-right" style="color: #cbd5e1;"></i>
                        </a>
                        
                        <a href="<?= base_url('tenant/complaints') ?>" class="action-card">
                            <div class="action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                                <i class="bi bi-chat-left-text"></i>
                            </div>
                            <div class="action-content">
                                <div class="action-title">Submit Complaint</div>
                                <div class="action-subtitle">Report an issue or concern</div>
                            </div>
                            <i class="bi bi-chevron-right" style="color: #cbd5e1;"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($recent_payments)): ?>
            <div class="content-section" style="margin-top: 32px;">
                <div class="section-header">
                    <h3 class="section-title">Recent Payments</h3>
                    <a href="<?= base_url('tenant/payments') ?>" style="color: #6366f1; text-decoration: none; font-weight: 500;">
                        View All <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($recent_payments, 0, 5) as $payment): ?>
                                <tr>
                                    <td><?= date('M d, Y', strtotime($payment['created_at'])) ?></td>
                                    <td><strong>₱<?= number_format($payment['amount'], 2) ?></strong></td>
                                    <td><?= esc($payment['payment_method']) ?></td>
                                    <td>
                                        <?php if ($payment['status'] === 'approved'): ?>
                                            <span class="badge-success"><i class="bi bi-check-circle"></i> Approved</span>
                                        <?php elseif ($payment['status'] === 'pending'): ?>
                                            <span class="badge-warning"><i class="bi bi-clock"></i> Pending</span>
                                        <?php else: ?>
                                            <span class="badge-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
