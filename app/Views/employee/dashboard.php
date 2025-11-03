<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
    <style>
        /* Stat card styling to match tenant dashboard */
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
        <?= view('components/employee_navbar', ['username' => $username, 'active' => $active]) ?>
        
        <h1 class="page-title">Welcome Back, <?= esc($username) ?>!</h1>
        <p class="page-subtitle">Your employee dashboard overview</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Total Complaints</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $totalComplaints ?? 0 ?></div>
                <div class="stat-label">All complaints</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Open Complaints</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $openComplaints ?? 0 ?></div>
                <div class="stat-label">Needs attention</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">In Progress</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $inProgressComplaints ?? 0 ?></div>
                <div class="stat-label">Being resolved</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Closed</div>
                    </div>
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $closedComplaints ?? 0 ?></div>
                <div class="stat-label">Completed</div>
            </div>
        </div>
        
        <div class="row" style="margin-top: 32px;">
            <div class="col-md-8">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Welcome to RentBase Employee Portal</h3>
                    </div>
                    <div style="padding: 30px;">
                        <div class="d-flex align-items-start gap-3 mb-4">
                            <div style="flex-shrink: 0;">
                                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-person-workspace" style="font-size: 40px; color: white;"></i>
                                </div>
                            </div>
                            <div style="flex-grow: 1;">
                                <h4 style="color: #1e293b; margin-bottom: 12px; font-size: 20px; font-weight: 600;">Employee Portal Access</h4>
                                <p style="color: #64748b; line-height: 1.6; margin-bottom: 16px;">
                                    Welcome to your employee dashboard. Your role and permissions have been configured by the system administrator. 
                                    As an employee, you have access to various system features to assist with property management tasks.
                                </p>
                                <div class="alert alert-info" style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); color: #1e40af; border-radius: 12px;">
                                    <i class="bi bi-info-circle me-2"></i>
                                    <strong>Note:</strong> If you need additional access or have questions about your responsibilities, please contact your supervisor or system administrator.
                                </div>
                            </div>
                        </div>
                        
                        <!-- <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <div style="padding: 20px; background: linear-gradient(135deg, rgba(99, 102, 241, 0.05) 0%, rgba(139, 92, 246, 0.05) 100%); border-radius: 12px; border: 1px solid rgba(99, 102, 241, 0.1);">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-shield-check" style="color: #6366f1; font-size: 20px;"></i>
                                        <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1e293b;">Secure Access</h5>
                                    </div>
                                    <p style="color: #64748b; font-size: 14px; margin: 0;">Your account is protected with enterprise-level security measures.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div style="padding: 20px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(5, 150, 105, 0.05) 100%); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.1);">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <i class="bi bi-lightning-charge" style="color: #10b981; font-size: 20px;"></i>
                                        <h5 style="margin: 0; font-size: 16px; font-weight: 600; color: #1e293b;">Real-time Updates</h5>
                                    </div>
                                    <p style="color: #64748b; font-size: 14px; margin: 0;">Access the latest property information and tenant data instantly.</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Account Info</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="mb-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; font-weight: 700;">
                                    <?= strtoupper(substr($username, 0, 1)) ?>
                                </div>
                                <div>
                                    <div style="font-weight: 600; color: #1e293b; font-size: 18px;"><?= esc($username) ?></div>
                                    <div style="color: #64748b; font-size: 14px;">Employee Account</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Role</label>
                            <div><span class="badge-success"><i class="bi bi-person-badge"></i> Employee</span></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Status</label>
                            <div><span class="badge-success"><i class="bi bi-check-circle"></i> Active</span></div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Assigned Owner</label>
                            <?php if (!empty($ownerName)): ?>
                                <p style="color: #1e293b; font-size: 14px; margin: 0; font-weight: 500;"><?= esc($ownerName) ?></p>
                            <?php else: ?>
                                <p style="color: #94a3b8; font-size: 14px; margin: 0; font-style: italic;">Not assigned</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Last Login</label>
                            <p style="color: #1e293b; font-size: 14px; margin: 0;"><?= date('F d, Y') ?><br><?= date('h:i A') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-section" style="margin-top: 24px;">
            <div class="section-header">
                <h3 class="section-title">System Status</h3>
            </div>
            <div class="row g-3" style="padding: 20px;">
                <div class="col-md-3">
                    <div style="padding: 24px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.08) 0%, rgba(37, 99, 235, 0.08) 100%); border-radius: 14px; text-align: center; border: 2px solid rgba(59, 130, 246, 0.15);">
                        <i class="bi bi-server" style="font-size: 32px; color: #3b82f6; margin-bottom: 12px;"></i>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">Database</div>
                        <span class="badge-success">Connected</span>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div style="padding: 24px; background: linear-gradient(135deg, rgba(16, 185, 129, 0.08) 0%, rgba(5, 150, 105, 0.08) 100%); border-radius: 14px; text-align: center; border: 2px solid rgba(16, 185, 129, 0.15);">
                        <i class="bi bi-shield-check" style="font-size: 32px; color: #10b981; margin-bottom: 12px;"></i>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">Security</div>
                        <span class="badge-success">Protected</span>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div style="padding: 24px; background: linear-gradient(135deg, rgba(139, 92, 246, 0.08) 0%, rgba(124, 58, 237, 0.08) 100%); border-radius: 14px; text-align: center; border: 2px solid rgba(139, 92, 246, 0.15);">
                        <i class="bi bi-hdd" style="font-size: 32px; color: #8b5cf6; margin-bottom: 12px;"></i>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">Storage</div>
                        <span class="badge-success">Available</span>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div style="padding: 24px; background: linear-gradient(135deg, rgba(251, 146, 60, 0.08) 0%, rgba(249, 115, 22, 0.08) 100%); border-radius: 14px; text-align: center; border: 2px solid rgba(251, 146, 60, 0.15);">
                        <i class="bi bi-speedometer2" style="font-size: 32px; color: #fb923c; margin-bottom: 12px;"></i>
                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">Performance</div>
                        <span class="badge-success">Optimal</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update time every second
        function updateTime() {
            const now = new Date();
            const hours = now.getHours();
            const minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';
            const displayHours = hours % 12 || 12;
            const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
            document.getElementById('currentTime').textContent = displayHours + ':' + displayMinutes + ' ' + ampm;
        }
        
        setInterval(updateTime, 1000);
    </script>
</body>
</html>
