<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RentBase</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 48px;
        max-width: 450px;
        width: 100%;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: slideUp 0.5s ease-out;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .login-logo {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
    }
    
    .login-logo i {
        font-size: 32px;
        color: white;
    }
    
    .login-title {
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
        text-align: center;
    }
    
    .login-subtitle {
        color: #64748b;
        text-align: center;
        margin-bottom: 32px;
        font-size: 15px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-label {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
        font-size: 14px;
    }
    
    .input-group {
        position: relative;
    }
    
    .input-group i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        z-index: 5;
        font-size: 18px;
    }
    
    .form-control {
        padding: 14px 16px 14px 45px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        font-size: 15px;
        position: relative;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    
    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        border: none;
        border-radius: 12px;
        color: white;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s;
        margin-top: 8px;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
    }
    
    .divider {
        text-align: center;
        margin: 24px 0;
        position: relative;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        left: 0;
        top: 50%;
        width: 100%;
        height: 1px;
        background: #e2e8f0;
    }
    
    .divider span {
        background: white;
        padding: 0 12px;
        color: #64748b;
        font-size: 14px;
        position: relative;
        z-index: 1;
    }
    
    .register-link {
        text-align: center;
        color: #64748b;
        font-size: 14px;
    }
    
    .register-link a {
        color: #6366f1;
        font-weight: 600;
        text-decoration: none;
    }
    
    .register-link a:hover {
        text-decoration: underline;
    }
    
    .alert {
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 20px;
        border: none;
        font-size: 14px;
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }
</style>

<div class="login-card">
    <div class="login-logo">
        <i class="bi bi-house-door-fill"></i>
    </div>
    
    <h1 class="login-title">Welcome Back!</h1>
    <p class="login-subtitle">Sign in to access your RentBase account</p>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('message')) ?>
        </div>
    <?php endif; ?>
    
    <form action="<?= base_url('auth/processLogin') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label class="form-label">Username</label>
            <div class="input-group">
                <i class="bi bi-person-fill"></i>
                <input type="text" class="form-control" name="username" placeholder="Enter username" required autofocus>
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Password</label>
            <div class="input-group">
                <i class="bi bi-lock-fill"></i>
                <input type="password" class="form-control" name="password" placeholder="Enter password" required>
            </div>
        </div>
        
        <button type="submit" class="btn-login">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
        </button>
    </form>
    
    <div class="divider">
        <span>OR</span>
    </div>
    
    <div class="register-link">
        Don't have an account? <a href="<?= base_url('auth/register') ?>">Create one now</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
