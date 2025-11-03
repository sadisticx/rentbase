<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$tenant_id = $_SESSION['user_id'];

// Fetch tenant details, room, and parking slot
$stmt = $conn->prepare("
    SELECT 
        up.full_name, up.email, up.phone_number,
        r.room_number, r.details AS room_details,
        ps.slot_number
    FROM users u
    LEFT JOIN user_profiles up ON u.id = up.user_id
    LEFT JOIN rooms r ON u.id = r.tenant_id
    LEFT JOIN parking_slots ps ON u.id = ps.tenant_id
    WHERE u.id = ?
");
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();
$details = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="#">RentBase - Tenant</a>
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
        <h1 class="uk-heading-medium uk-text-center">Tenant Dashboard</h1>
        <p class="uk-text-lead uk-text-center uk-text-muted">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
    </div>

    <div class="uk-grid-match uk-child-width-1-2@s uk-margin-large-top" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: comments; ratio: 2.5" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">Manage Complaints</h3>
                    <p class="uk-text-muted">Submit and track your complaints</p>
                    <a href="/RentBase/tenant/complaints.php" class="uk-button uk-button-primary uk-margin-small-top">
                        View Complaints <span uk-icon="chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-hover uk-card-body uk-border-rounded">
                <div class="uk-text-center">
                    <span uk-icon="icon: credit-card; ratio: 2.5" class="uk-text-primary"></span>
                    <h3 class="uk-card-title uk-margin-small-top">Pay Fees</h3>
                    <p class="uk-text-muted">Make payments for rent and utilities</p>
                    <a href="/RentBase/tenant/payments.php" class="uk-button uk-button-primary uk-margin-small-top">
                        Make Payment <span uk-icon="chevron-right"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="uk-grid-match uk-child-width-1-1@s uk-child-width-1-3@m uk-margin-large-top" uk-grid>
        <div>
            <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
                <h3 class="uk-card-title">
                    <span uk-icon="user" class="uk-margin-small-right"></span>Personal Details
                </h3>
                <?php if ($details): ?>
                    <dl class="uk-description-list">
                        <dt>Full Name</dt>
                        <dd><?php echo htmlspecialchars($details['full_name'] ?: 'N/A'); ?></dd>
                        <dt>Email</dt>
                        <dd><?php echo htmlspecialchars($details['email'] ?: 'N/A'); ?></dd>
                        <dt>Phone</dt>
                        <dd><?php echo htmlspecialchars($details['phone_number'] ?: 'N/A'); ?></dd>
                    </dl>
                <?php else: ?>
                    <p class="uk-text-muted">No personal details found. The owner may need to create your profile.</p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
                <h3 class="uk-card-title">
                    <span uk-icon="home" class="uk-margin-small-right"></span>Room Details
                </h3>
                <?php if ($details && $details['room_number']): ?>
                    <dl class="uk-description-list">
                        <dt>Room Number</dt>
                        <dd><span class="uk-badge uk-badge-primary"><?php echo htmlspecialchars($details['room_number']); ?></span></dd>
                        <dt>Details</dt>
                        <dd><?php echo htmlspecialchars($details['room_details']); ?></dd>
                    </dl>
                <?php else: ?>
                    <p class="uk-text-muted">You are not currently assigned to a room.</p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
                <h3 class="uk-card-title">
                    <span uk-icon="location" class="uk-margin-small-right"></span>Parking Slot
                </h3>
                <?php if ($details && $details['slot_number']): ?>
                    <dl class="uk-description-list">
                        <dt>Slot Number</dt>
                        <dd><span class="uk-badge uk-badge-primary"><?php echo htmlspecialchars($details['slot_number']); ?></span></dd>
                    </dl>
                <?php else: ?>
                    <p class="uk-text-muted">You do not have an allotted parking slot.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>