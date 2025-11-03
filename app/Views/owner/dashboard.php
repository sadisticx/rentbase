<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .main-wrapper {
            max-width: 100%;
            margin: 0;
            background: #ffffff;
            padding: 32px 40px;
        }
        
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }
        
        .nav-menu {
            display: flex;
            gap: 24px;
            align-items: center;
        }
        
        .nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 10px;
            transition: all 0.3s;
        }
        
        .nav-link:hover, .nav-link.active {
            color: #1e293b;
            background: rgba(99, 102, 241, 0.08);
        }
        
        .user-section {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        
        .page-title {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .page-subtitle {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 32px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: white;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }
        
        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }
        
        .stat-title {
            font-size: 14px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .stat-icon.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
        .stat-icon.orange { background: rgba(251, 146, 60, 0.1); color: #fb923c; }
        
        .stat-value {
            font-size: 36px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }
        
        .stat-label {
            font-size: 13px;
            color: #64748b;
        }
        
        .content-section {
            background: white;
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
            color: white;
        }
        
        .quick-action {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            background: rgba(99, 102, 241, 0.05);
            border-radius: 14px;
            border: 2px solid transparent;
            transition: all 0.3s;
            text-decoration: none;
            color: #1e293b;
        }
        
        .quick-action:hover {
            border-color: #6366f1;
            background: rgba(99, 102, 241, 0.08);
        }
        
        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: white;
        }
        
        .action-info h4 {
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 4px 0;
            color: #1e293b;
        }
        
        .action-info p {
            font-size: 13px;
            color: #64748b;
            margin: 0;
        }
        
        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: none;
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: #ef4444;
            color: white;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <!-- Top Navigation -->
        <nav class="top-nav">
            <div class="logo-section">
                <div class="logo-icon">
                    <i class="bi bi-building"></i>
                </div>
                <span class="logo-text">RENTBASE</span>
            </div>
            
            <div class="nav-menu">
                <a href="<?= base_url('owner/dashboard') ?>" class="nav-link active">Home</a>
                <a href="<?= base_url('owner/rooms') ?>" class="nav-link">Rooms</a>
                <a href="<?= base_url('owner/tenants') ?>" class="nav-link">Tenants</a>
                <a href="<?= base_url('owner/employees') ?>" class="nav-link">Employees</a>
                <a href="<?= base_url('owner/parking') ?>" class="nav-link">Parking</a>
                <a href="<?= base_url('owner/complaints') ?>" class="nav-link">Complaints</a>
            </div>
            
            <div class="user-section">
                <button class="logout-btn" onclick="location.href='<?= base_url('auth/logout') ?>'">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
                <div class="user-avatar">
                    <?= strtoupper(substr($username, 0, 1)) ?>
                </div>
            </div>
        </nav>
        
        <!-- Page Header -->
        <h1 class="page-title">Owner Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?= esc($username) ?>!</p>
        
        <!-- Statistics Cards -->
        <div class="dashboard-grid">
            <div class="stat-card" onclick="location.href='<?= base_url('owner/rooms') ?>'">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Manage Rooms</div>
                    </div>
                    <div class="stat-icon blue">
                        <i class="bi bi-door-closed"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $rooms_count ?? 0 ?></div>
                <div class="stat-label">Total rooms available</div>
            </div>
            
            <div class="stat-card" onclick="location.href='<?= base_url('owner/tenants') ?>'">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Manage Tenants</div>
                    </div>
                    <div class="stat-icon green">
                        <i class="bi bi-people"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $tenants_count ?? 0 ?></div>
                <div class="stat-label">Total tenants registered</div>
            </div>
            
            <div class="stat-card" onclick="location.href='<?= base_url('owner/parking') ?>'">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">Manage Parking</div>
                    </div>
                    <div class="stat-icon purple">
                        <i class="bi bi-car-front"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $parking_count ?? 0 ?></div>
                <div class="stat-label">Total parking slots</div>
            </div>
            
            <div class="stat-card" onclick="location.href='<?= base_url('owner/complaints') ?>'">
                <div class="stat-header">
                    <div>
                        <div class="stat-title">View Complaints</div>
                    </div>
                    <div class="stat-icon orange">
                        <i class="bi bi-chat-left-text"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $complaints_count ?? 0 ?></div>
                <div class="stat-label">Active complaints</div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="content-section">
            <div class="section-header">
                <h3 class="section-title">Quick Actions</h3>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <a href="<?= base_url('owner/rooms') ?>" class="quick-action">
                        <div class="action-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                            <i class="bi bi-door-open"></i>
                        </div>
                        <div class="action-info">
                            <h4>View Rooms</h4>
                            <p>Manage room inventory</p>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <a href="<?= base_url('owner/tenants') ?>" class="quick-action">
                        <div class="action-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="action-info">
                            <h4>View Tenants</h4>
                            <p>Manage tenant accounts</p>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <a href="<?= base_url('owner/parking') ?>" class="quick-action">
                        <div class="action-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                            <i class="bi bi-p-square"></i>
                        </div>
                        <div class="action-info">
                            <h4>View Parking</h4>
                            <p>Manage parking slots</p>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-6 col-lg-3">
                    <a href="<?= base_url('owner/complaints') ?>" class="quick-action">
                        <div class="action-icon" style="background: linear-gradient(135deg, #fb923c, #f97316);">
                            <i class="bi bi-envelope-open"></i>
                        </div>
                        <div class="action-info">
                            <h4>View Complaints</h4>
                            <p>Handle tenant issues</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
