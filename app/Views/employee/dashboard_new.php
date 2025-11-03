<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <?= view('components/modern_styles') ?>
</head>
<body>
    <?php $active = 'dashboard'; ?>
    <?= view('components/employee_navbar', ['username' => $username, 'active' => $active]) ?>
    
    <div class="main-wrapper">
        <h1 class="page-title">Welcome, <?= esc($username) ?></h1>
        <p class="page-subtitle">Employee dashboard</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">Employee</div>
                    <div class="stat-label">Current Role</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?= date('F d, Y') ?></div>
                    <div class="stat-label">Today's Date</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="bi bi-clock"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value" id="currentTime"><?= date('h:i A') ?></div>
                    <div class="stat-label">Current Time</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">Active</div>
                    <div class="stat-label">Account Status</div>
                </div>
            </div>
        </div>
        
        <div class="content-section" style="margin-top: 32px;">
            <div class="section-header">
                <h3 class="section-title">Welcome to RentBase Employee Portal</h3>
            </div>
            <div style="padding: 40px; text-align: center;">
                <i class="bi bi-person-workspace" style="font-size: 64px; color: #6366f1;"></i>
                <h4 style="color: #1e293b; margin-top: 24px; margin-bottom: 16px;">Employee Dashboard</h4>
                <p style="color: #64748b; max-width: 600px; margin: 0 auto;">
                    Welcome to your employee dashboard. Your role and permissions have been configured by the system administrator. 
                    If you need additional access or have questions about your responsibilities, please contact your supervisor.
                </p>
            </div>
        </div>
        
        <div class="row" style="margin-top: 32px;">
            <div class="col-md-6">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">Quick Information</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569;">Username</label>
                            <p style="color: #1e293b; font-size: 16px;"><?= esc($username) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569;">Role</label>
                            <p><span class="badge-success"><i class="bi bi-person-badge"></i> Employee</span></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #475569;">Login Time</label>
                            <p style="color: #1e293b; font-size: 16px;"><?= date('F d, Y h:i A') ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="content-section">
                    <div class="section-header">
                        <h3 class="section-title">System Status</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>All Systems Operational</strong>
                            <p class="mb-0 mt-2" style="font-size: 14px;">The RentBase system is running smoothly. All features are available.</p>
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span style="color: #475569;"><i class="bi bi-server"></i> Database</span>
                                <span class="badge-success">Connected</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span style="color: #475569;"><i class="bi bi-shield-check"></i> Security</span>
                                <span class="badge-success">Active</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span style="color: #475569;"><i class="bi bi-hdd"></i> Storage</span>
                                <span class="badge-success">Available</span>
                            </div>
                        </div>
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
