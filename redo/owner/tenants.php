<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$add_tenant_error = ''; // Initialize error variable

// Handle form submissions for adding a tenant
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_tenant'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $room_id = $_POST['room_id'];

    if (empty($username) || empty($password) || empty($full_name) || empty($room_id)) {
        $add_tenant_error = "All fields with * are required.";
    } elseif ($password !== $confirm_password) {
        $add_tenant_error = "Passwords do not match. Please try again.";
    } else {
        // Check if username already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $add_tenant_error = "Username '" . htmlspecialchars($username) . "' is already taken. Please choose a different one.";
        } else {
            // All clear, proceed with creating the tenant
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $role = 'tenant';

            // Use a transaction to ensure all queries succeed
            $conn->begin_transaction();

            try {
                // Insert into users table
                $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $password_hash, $role);
                $stmt->execute();
                $user_id = $stmt->insert_id;
                $stmt->close();

                // Insert into user_profiles table
                $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, full_name, email, phone_number) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("isss", $user_id, $full_name, $email, $phone_number);
                $stmt->execute();
                $stmt->close();

                // Update rooms table
                $stmt = $conn->prepare("UPDATE rooms SET tenant_id = ? WHERE id = ? AND owner_id = ?");
                $stmt->bind_param("iii", $user_id, $room_id, $owner_id);
                $stmt->execute();
                $stmt->close();

                $conn->commit();
                header('Location: tenants.php?message=Tenant added successfully');
                exit();

            } catch (mysqli_sql_exception $exception) {
                $conn->rollback();
                $add_tenant_error = "An error occurred during the registration process. Please try again.";
            }
        }
        $check_stmt->close();
    }
}

// Fetch tenants for the owner's rooms
$stmt = $conn->prepare("SELECT users.id, users.username, user_profiles.full_name, rooms.room_number FROM users JOIN user_profiles ON users.id = user_profiles.user_id JOIN rooms ON users.id = rooms.tenant_id WHERE rooms.owner_id = ?");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$tenants = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch rooms for the owner to assign to a new tenant
$stmt = $conn->prepare("SELECT * FROM rooms WHERE owner_id = ? AND tenant_id IS NULL");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$available_rooms = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="dashboard.php">RentBase - Owner</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="dashboard.php"><span uk-icon="home"></span> Dashboard</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <div class="uk-flex uk-flex-between uk-flex-middle uk-margin-medium-bottom">
            <h1 class="uk-heading-medium">Manage Tenants</h1>
        </div>

        <?php if (isset($_GET['message'])): ?>
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><?php echo htmlspecialchars($_GET['message']); ?></p>
            </div>
        <?php endif; ?>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-margin-medium-bottom">
            <div class="uk-flex uk-flex-between uk-flex-middle">
                <h3 class="uk-card-title">Your Tenants</h3>
            </div>
            
            <?php if (empty($tenants)): ?>
                <div class="uk-alert-warning" uk-alert>
                    <p>You have not added any tenants yet.</p>
                </div>
            <?php else: ?>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th class="uk-table-shrink">#</th>
                                <th>Username</th>
                                <th>Full Name</th>
                                <th>Room Number</th>
                                <th class="uk-text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach ($tenants as $tenant): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><?php echo htmlspecialchars($tenant['username']); ?></td>
                                    <td><?php echo htmlspecialchars($tenant['full_name']); ?></td>
                                    <td><span class="uk-badge"><?php echo htmlspecialchars($tenant['room_number']); ?></span></td>
                                    <td class="uk-text-right">
                                        <a href="edit_tenant.php?id=<?php echo $tenant['id']; ?>" class="uk-button uk-button-small uk-button-default" uk-tooltip="Edit Tenant">
                                            <span uk-icon="icon: pencil"></span>
                                        </a>
                                        <a href="delete_tenant.php?id=<?php echo $tenant['id']; ?>" class="uk-button uk-button-small uk-button-danger" uk-tooltip="Delete Tenant" onclick="return confirm('Are you sure you want to delete this tenant? This action cannot be undone.');">
                                            <span uk-icon="icon: trash"></span>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h3 class="uk-card-title" id="add-tenant-form">Add a New Tenant</h3>
            
            <?php if (!empty($add_tenant_error)): ?>
                <div class="uk-alert-danger" uk-alert>
                    <a class="uk-alert-close" uk-close></a>
                    <p><?php echo $add_tenant_error; ?></p>
                </div>
            <?php endif; ?>

            <form action="tenants.php#add-tenant-form" method="post" class="uk-form-stacked">
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="username">Username <span class="uk-text-danger">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" name="username" id="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="full_name">Full Name <span class="uk-text-danger">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" name="full_name" id="full_name" placeholder="Enter full name" required>
                        </div>
                    </div>

                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="password">Password <span class="uk-text-danger">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="password" name="password" id="password" placeholder="Enter password" required>
                        </div>
                    </div>
                    
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="confirm_password">Confirm Password <span class="uk-text-danger">*</span></label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
                        </div>
                    </div>

                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="email">Email</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="email" name="email" id="email" placeholder="Enter email address">
                        </div>
                    </div>
                    
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="phone_number">Phone Number</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" name="phone_number" id="phone_number" placeholder="Enter phone number">
                        </div>
                    </div>

                    <div class="uk-width-1-1">
                        <label class="uk-form-label" for="room_id">Assign to Room <span class="uk-text-danger">*</span></label>
                        <div class="uk-form-controls">
                            <select class="uk-select" name="room_id" id="room_id" required>
                                <option value="">Select an Available Room</option>
                                <?php foreach ($available_rooms as $room): ?>
                                    <option value="<?php echo $room['id']; ?>"><?php echo htmlspecialchars($room['room_number']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="uk-width-1-1">
                        <button type="submit" name="add_tenant" class="uk-button uk-button-primary">
                            <span uk-icon="check"></span> Add Tenant
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
