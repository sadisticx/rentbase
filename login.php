
<?php
session_start();
include __DIR__ . '/includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        header('Location: index.php?error=Username and password are required');
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'owner') {
                header('Location: owner/dashboard.php');
            } elseif ($user['role'] == 'tenant') {
                header('Location: tenant/dashboard.php');
            } elseif ($user['role'] == 'employee') {
                header('Location: employee/dashboard.php');
            }
            exit();
        } else {
            header('Location: index.php?error=Invalid username or password');
            exit();
        }
    } else {
        header('Location: index.php?error=Invalid username or password');
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header('Location: index.php');
    exit();
}
?>
