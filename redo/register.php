<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/db_connect.php';

$error = ''; // Initialize error variable

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if (empty($username) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    } else {
        // Check if username already exists
        $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Username '" . htmlspecialchars($username) . "' is already taken. Please choose a different one.";
        } else {
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $password_hash, $role);

            if ($stmt->execute()) {
                // Redirect to login with a success message
                header('Location: index.php?message=Registration successful. Please login.');
                exit();
            } else {
                $error = "Registration failed due to a server error. Please try again later.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
$conn->close();
?>

<div class="uk-container uk-container-small">
    <div class="uk-flex uk-flex-center uk-flex-middle" style="min-height: 70vh;">
        <div class="uk-width-1-1">
            <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
                <div class="uk-text-center uk-margin-medium-bottom">
                    <h2 class="uk-card-title uk-text-bold">Register</h2>
                    <p class="uk-text-muted">Create an account to access the system</p>
                </div>
        
                <?php if (!empty($error)): ?>
                    <div class="uk-alert-danger" uk-alert>
                        <a class="uk-alert-close" uk-close></a>
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>

                <form action="register.php" method="post" class="uk-form-stacked">
                    <div class="uk-margin">
                        <label class="uk-form-label" for="username">Username</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="text" name="username" id="username" placeholder="Choose a username" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin">
                        <label class="uk-form-label" for="password">Password</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="password" name="password" id="password" placeholder="Enter password" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin">
                        <label class="uk-form-label" for="confirm_password">Confirm Password</label>
                        <div class="uk-form-controls">
                            <input class="uk-input" type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter password" required>
                        </div>
                    </div>
                    
                    <div class="uk-margin">
                        <label class="uk-form-label" for="role">I am a...</label>
                        <div class="uk-form-controls">
                            <select class="uk-select" name="role" id="role" required>
                                <option value="">Select your role</option>
                                <option value="owner">Apartment Owner</option>
                                <option value="tenant">Tenant</option>
                                <option value="employee">Employee</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="uk-margin">
                        <button type="submit" class="uk-button uk-button-primary uk-width-1-1">
                            <span uk-icon="user"></span> Register
                        </button>
                    </div>
                </form>
                
                <div class="uk-text-center uk-margin-top">
                    <p class="uk-text-muted">Already have an account? <a href="index.php" class="uk-link-text">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>