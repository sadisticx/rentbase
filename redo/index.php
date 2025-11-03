
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    if ($role == 'owner') {
        header('Location: owner/dashboard.php');
    } elseif ($role == 'tenant') {
        header('Location: tenant/dashboard.php');
    } elseif ($role == 'employee') {
        header('Location: employee/dashboard.php');
    }
    exit();
}
// Use absolute path for includes
include __DIR__ . '/includes/header.php';
?>

<div class="uk-container uk-container-small">
    <div class="uk-flex uk-flex-center uk-flex-middle" style="min-height: 70vh;">
        <div class="uk-width-1-1">
            <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
                <div class="uk-text-center uk-margin-medium-bottom">
                    <h2 class="uk-card-title uk-text-bold">Login to RentBase</h2>
                    <p class="uk-text-muted">Enter your credentials to access your account</p>
                </div>
                
                <?php
                if (isset($_GET['error'])) {
                    echo '<div class="uk-alert-danger" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>' . htmlspecialchars($_GET['error']) . '</p>
                          </div>';
                }
                if (isset($_GET['message'])) {
                    echo '<div class="uk-alert-success" uk-alert>
                            <a class="uk-alert-close" uk-close></a>
                            <p>' . htmlspecialchars($_GET['message']) . '</p>
                          </div>';
                }
                ?>
                
                <form action="login.php" method="post" class="uk-form-stacked">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="username">Username</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="username" name="username" type="text" placeholder="Enter your username" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin">
                        <label class="uk-form-label" for="password">Password</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" id="password" name="password" type="password" placeholder="Enter your password" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin">
                        <button type="submit" class="uk-button uk-button-primary uk-width-1-1">
                            <span uk-icon="sign-in"></span> Login
                        </button>
                    </div>
                </form>
                
                <div class="uk-text-center uk-margin-top">
                    <p class="uk-text-muted">Don't have an account? <a href="register.php" class="uk-link-text">Register here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
