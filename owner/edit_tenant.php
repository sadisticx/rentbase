<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$tenant_id = $_GET['id'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_tenant'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    if (!empty($full_name)) {
        $stmt = $conn->prepare("UPDATE user_profiles SET full_name = ?, email = ?, phone_number = ? WHERE user_id = ?");
        $stmt->bind_param("sssi", $full_name, $email, $phone_number, $tenant_id);
        $stmt->execute();
        $stmt->close();
        header('Location: tenants.php');
        exit();
    }
}

// Fetch tenant details
$stmt = $conn->prepare("SELECT users.id, users.username, user_profiles.full_name, user_profiles.email, user_profiles.phone_number FROM users JOIN user_profiles ON users.id = user_profiles.user_id WHERE users.id = ?");
$stmt->bind_param("i", $tenant_id);
$stmt->execute();
$result = $stmt->get_result();
$tenant = $result->fetch_assoc();
$stmt->close();

if (!$tenant) {
    header('Location: tenants.php?error=Tenant not found');
    exit();
}
?>

<div class="uk-container uk-container-small">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="dashboard.php">RentBase - Owner</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="tenants.php"><span uk-icon="arrow-left"></span> Back to Tenants</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h2 class="uk-card-title">Edit Tenant</h2>
            
            <form action="edit_tenant.php?id=<?php echo $tenant_id; ?>" method="post" class="uk-form-stacked">
                <div class="uk-margin">
                    <label class="uk-form-label" for="username">Username</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" name="username" id="username" value="<?php echo htmlspecialchars($tenant['username']); ?>" disabled>
                        <p class="uk-text-meta uk-margin-small-top">Username cannot be changed</p>
                    </div>
                </div>
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="full_name">Full Name</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" name="full_name" id="full_name" value="<?php echo htmlspecialchars($tenant['full_name']); ?>" required>
                    </div>
                </div>
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="email">Email</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="email" name="email" id="email" value="<?php echo htmlspecialchars($tenant['email']); ?>">
                    </div>
                </div>
                
                <div class="uk-margin">
                    <label class="uk-form-label" for="phone_number">Phone Number</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($tenant['phone_number']); ?>">
                    </div>
                </div>
                
                <div class="uk-margin">
                    <a href="tenants.php" class="uk-button uk-button-default uk-margin-small-right">Cancel</a>
                    <button type="submit" name="edit_tenant" class="uk-button uk-button-primary">
                        <span uk-icon="check"></span> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
