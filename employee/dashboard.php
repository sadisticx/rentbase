
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="#">RentBase - Employee</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li>
                    <a href="#">
                        <span uk-icon="user"></span> <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </a>
                    <div class="uk-navbar-dropdown">
                        <ul class="uk-nav uk-navbar-dropdown-nav">
                            <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <h1 class="uk-heading-medium uk-text-center">Employee Dashboard</h1>
        <p class="uk-text-lead uk-text-center uk-text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    </div>

    <div class="uk-flex uk-flex-center uk-margin-large-top">
        <div class="uk-width-1-2@s uk-width-1-3@m">
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: comments; ratio: 3" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">Manage Complaints</h3>
                    <p class="uk-text-muted">View and manage tenant complaints</p>
                    <div class="uk-margin-top">
                        <p class="uk-text-meta">Coming soon...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
