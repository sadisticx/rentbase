
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="#">RentBase - Owner</a>
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
        <h1 class="uk-heading-medium uk-text-center">Owner Dashboard</h1>
        <p class="uk-text-lead uk-text-center uk-text-muted">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    </div>

    <div class="uk-grid-match uk-child-width-1-2@s uk-child-width-1-4@m uk-margin-large-top" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: home; ratio: 2.5" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">Manage Rooms</h3>
                    <p class="uk-text-muted">Add, edit, and manage your rooms</p>
                    <a href="rooms.php" class="uk-button uk-button-primary uk-margin-small-top">
                        View Rooms <span uk-icon="chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: users; ratio: 2.5" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">Manage Tenants</h3>
                    <p class="uk-text-muted">Add, edit, and manage your tenants</p>
                    <a href="tenants.php" class="uk-button uk-button-primary uk-margin-small-top">
                        View Tenants <span uk-icon="chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: location; ratio: 2.5" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">Manage Parking</h3>
                    <p class="uk-text-muted">Manage parking slots allocation</p>
                    <a href="parking.php" class="uk-button uk-button-primary uk-margin-small-top">
                        View Parking <span uk-icon="chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: comments; ratio: 2.5" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">View Complaints</h3>
                    <p class="uk-text-muted">Review tenant complaints</p>
                    <a href="complaints.php" class="uk-button uk-button-primary uk-margin-small-top">
                        View Complaints <span uk-icon="chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
