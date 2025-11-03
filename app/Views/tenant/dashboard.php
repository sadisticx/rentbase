<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?> 
    <style>
        /* Stat card styling to match owner dashboard */
        .stat-card {
            background-color: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
            transform: translateY(-2px);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .stat-title {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 14px;
            color: #94a3b8;
        }

        /* Quick actions grid styling to match owner dashboard */
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 32px;
        }

        .action-card-modern {
            background-color: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-card-modern:hover {
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .action-icon-modern {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .action-title-modern {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b; /* slate-800 */
        }
        
        .action-subtitle-modern {
            font-size: 14px;
            color: #64748b;
        }
        
        /* Room Details and Quick Actions section adjustments */
        .content-section {
            background-color: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            height: 100%;
        }
        
        .section-header {
            padding: 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* Bootstrap grid adjustments */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

    </style>
</head>
<body>
    <div class="main-wrapper">
        <?php $active = 'dashboard'; ?>
        <?= view('components/tenant_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Welcome, <?= esc($username) ?>!</h1>
        <p class="page-subtitle">Your tenant dashboard overview</p>
        
        <div class="stats-grid">
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">My Room</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-door-open"></i>
                    </div>
                </div>
                <div class="stat-value"><?= esc($room['room_number'] ?? 'N/A') ?></div>
                <div class="stat-label">Current assigned room</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Payments Made</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $payments_count ?? 0 ?></div>
                <div class="stat-label">Total rent payments</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Active Complaints</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(251, 146, 60, 0.1); color: #fb923c;">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $complaints_count ?? 0 ?></div>
                <div class="stat-label">Pending attention</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Parking Slot</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                        <i class="bi bi-car-front"></i>
                    </div>
                </div>
                <div class="stat-value"><?= esc($parking['slot_number'] ?? 'None') ?></div>
                <div class="stat-label">Your assigned slot</div>
            </div>
        </div>
        
        <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-top: 40px;">Quick Actions</h2>
        
        <div class="quick-actions-grid">
            
            <a href="<?= base_url('tenant/payments') ?>" class="action-card-modern">
                <div class="action-icon-modern" style="background: #10b981;"> <i class="bi bi-credit-card"></i>
                </div>
                <div class="action-content">
                    <div class="action-title-modern">Make Payment</div>
                    <div class="action-subtitle-modern">Submit your rent payment</div>
                </div>
            </a>
            
            <a href="<?= base_url('tenant/complaints') ?>" class="action-card-modern">
                <div class="action-icon-modern" style="background: #fb923c;"> <i class="bi bi-chat-left-text"></i>
                </div>
                <div class="action-content">
                    <div class="action-title-modern">Submit Complaint</div>
                    <div class="action-subtitle-modern">Report an issue or concern</div>
                </div>
            </a>
            
            <a href="<?= base_url('tenant/payments') ?>" class="action-card-modern">
                <div class="action-icon-modern" style="background: #3b82f6;"> <i class="bi bi-receipt"></i>
                </div>
                <div class="action-content">
                    <div class="action-title-modern">View Payments</div>
                    <div class="action-subtitle-modern">Check payment history</div>
                </div>
            </a>
            
            <a href="#" class="action-card-modern">
                <div class="action-icon-modern" style="background: #8b5cf6;"> <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="action-content">
                    <div class="action-title-modern">View Lease</div>
                    <div class="action-subtitle-modern">Review contract details</div>
                </div>
            </a>
        </div>
        
        <div class="row" style="margin-top: 32px;">
            <div class="col-md-5">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Room Details</h3>
                    </div>
                    <?php if (!empty($room)): ?>
                        <div style="padding: 24px;">
                            <div class="mb-4">
                                <label class="form-label" style="font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase;">Room Number</label>
                                <p style="color: #1e293b; font-size: 32px; font-weight: 700; margin: 0;"><?= esc($room['room_number']) ?></p>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" style="font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase;">Room Details</label>
                                <p style="color: #64748b; font-size: 14px; margin: 0;"><?= esc($room['details'] ?? 'No additional details available') ?></p>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" style="font-weight: 600; color: #475569; font-size: 14px; text-transform: uppercase;">Status</label>
                                <div><span class="badge-success"><i class="bi bi-check-circle"></i> Occupied</span></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div style="padding: 60px; text-align: center;">
                            <i class="bi bi-house-slash" style="font-size: 56px; color: #cbd5e1;"></i>
                            <p style="color: #64748b; margin-top: 16px; font-size: 18px; font-weight: 500;">No room currently assigned.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-7">
                <?php if (!empty($recent_payments)): ?>
                    <div class="content-section" style="height: 100%;">
                        <div class="section-header">
                            <h3 class="section-title">Recent Payments</h3>
                            <a href="<?= base_url('tenant/payments') ?>" style="color: #6366f1; text-decoration: none; font-weight: 500;">
                                View All <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="table-responsive" style="padding: 0 20px 20px 20px;">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Reference</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_payments as $payment): ?>
                                        <tr>
                                            <td><?= date('M d, Y', strtotime($payment['payment_date'])) ?></td>
                                            <td><strong>₱<?= number_format($payment['amount'], 2) ?></strong></td>
                                            <td><?= esc($payment['payment_method']) ?></td>
                                            <td><?= esc($payment['reference_number'] ?: '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>