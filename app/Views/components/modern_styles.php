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
        position: relative;
    }
    
    .logo-section {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
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
        white-space: nowrap;
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
    
    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        display: none;
        background: none;
        border: none;
        font-size: 24px;
        color: #1e293b;
        cursor: pointer;
        padding: 8px;
        z-index: 1001;
    }
    
    /* Responsive Styles */
    @media (max-width: 1024px) {
        .main-wrapper {
            padding: 24px 20px;
        }
        
        .mobile-menu-toggle {
            display: block;
        }
        
        .nav-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            flex-direction: column;
            align-items: flex-start;
            padding: 80px 24px 24px;
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            gap: 8px;
        }
        
        .nav-menu.active {
            right: 0;
        }
        
        .nav-link {
            width: 100%;
            padding: 12px 16px;
        }
        
        .user-section {
            gap: 8px;
        }
        
        .logout-btn {
            padding: 8px 12px;
            font-size: 14px;
        }
        
        .logout-btn i {
            display: none;
        }
    }
    
    @media (max-width: 768px) {
        .main-wrapper {
            padding: 20px 16px;
        }
        
        .logo-text {
            font-size: 18px;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .role-badge {
            font-size: 10px !important;
            padding: 3px 8px !important;
        }
        
        .page-title {
            font-size: 24px;
        }
        
        .page-subtitle {
            font-size: 14px;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            font-size: 14px;
        }
        
        .logout-btn {
            padding: 6px 10px;
            font-size: 13px;
        }
    }
    
    @media (max-width: 480px) {
        .logo-section {
            gap: 8px;
        }
        
        .logo-text {
            font-size: 16px;
        }
        
        .logo-icon {
            width: 36px;
            height: 36px;
            font-size: 16px;
        }
        
        .user-avatar {
            display: none;
        }
        
        .logout-btn {
            padding: 6px 8px;
            font-size: 0;
        }
        
        .logout-btn i {
            display: inline;
            font-size: 18px;
        }
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
    
    .logout-btn {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    
    .logout-btn:hover {
        background: #ef4444;
        color: white;
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
    
    .content-section {
        background: white;
        border-radius: 18px;
        padding: 28px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
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
        cursor: pointer;
    }
    
    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(99, 102, 241, 0.3);
        color: white;
    }
    
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-modern thead {
        background: rgba(99, 102, 241, 0.05);
    }
    
    .table-modern th {
        padding: 16px;
        text-align: left;
        font-weight: 600;
        color: #64748b;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table-modern td {
        padding: 16px;
        border-bottom: 1px solid #e2e8f0;
        color: #1e293b;
    }
    
    .table-modern tr:hover {
        background: rgba(99, 102, 241, 0.02);
    }
    
    .badge-custom {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
    
    .badge-success {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .badge-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }
    
    .badge-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .badge-primary {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }
    
    .action-btn {
        padding: 8px 14px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 14px;
        margin: 0 4px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 500;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
    }
    
    .btn-edit {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }
    
    .btn-view {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 14px;
        display: block;
    }
    
    .form-control {
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        font-size: 15px;
        width: 100%;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .form-select {
        padding: 12px 16px;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        font-size: 15px;
        width: 100%;
    }
    
    .form-select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }
    
    .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }
    
    .modal-header {
        border-bottom: 2px solid #e2e8f0;
        padding: 24px;
    }
    
    .modal-title {
        font-weight: 700;
        color: #1e293b;
    }
    
    .modal-body {
        padding: 24px;
    }
    
    .modal-footer {
        border-top: 2px solid #e2e8f0;
        padding: 20px 24px;
    }
    
    .btn-secondary {
        background: #e2e8f0;
        color: #64748b;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
    }
    
    .btn-secondary:hover {
        background: #cbd5e1;
    }
    
    .alert {
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        border: none;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-left: 4px solid #10b981;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border-left: 4px solid #ef4444;
    }
    
    .alert-warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
        border-left: 4px solid #f59e0b;
    }
    
    .alert-info {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
        border-left: 4px solid #3b82f6;
    }
</style>
