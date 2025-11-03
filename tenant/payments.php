<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'tenant') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}

include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$tenant_id = $_SESSION['user_id'];
$error = '';
$message = '';

// Handle form submission for new payment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_payment'])) {
    $amount = $_POST['amount'];
    $notes = $_POST['notes'];
    $payment_date = date('Y-m-d'); // Use current date for payment

    if (empty($amount) || !is_numeric($amount) || $amount <= 0) {
        $error = "Please enter a valid payment amount.";
    } else {
        $stmt = $conn->prepare("INSERT INTO payments (tenant_id, amount, payment_date, notes) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $tenant_id, $amount, $payment_date, $notes);
        if ($stmt->execute()) {
            $message = "Payment recorded successfully!";
        } else {
            $error = "Failed to record payment. Please try again.";
        }
        $stmt->close();
    }
}

// Fetch existing payments for the tenant
$payments_stmt = $conn->prepare("SELECT amount, payment_date, notes FROM payments WHERE tenant_id = ? ORDER BY payment_date DESC");
$payments_stmt->bind_param("i", $tenant_id);
$payments_stmt->execute();
$payments_result = $payments_stmt->get_result();

$conn->close();
?>

<div class="uk-container">
    <nav class="uk-navbar-container uk-margin" uk-navbar>
        <div class="uk-navbar-left">
            <a class="uk-navbar-item uk-logo" href="dashboard.php">RentBase - Tenant</a>
        </div>
        <div class="uk-navbar-right">
            <ul class="uk-navbar-nav">
                <li><a href="/RentBase/tenant/dashboard.php"><span uk-icon="home"></span> Dashboard</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <h1 class="uk-heading-medium uk-margin-medium-bottom">Pay & View Fees</h1>

        <?php if ($message): ?>
            <div class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><?php echo $message; ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="uk-alert-danger" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded uk-margin-medium-bottom">
            <h3 class="uk-card-title">Record a New Payment</h3>
            <div class="uk-alert-primary" uk-alert>
                <p>This form is a placeholder for a real payment gateway. Submitting this form will simply record a payment in the system.</p>
            </div>

            <form action="payments.php" method="post" class="uk-form-stacked">
                <div class="uk-margin">
                    <label class="uk-form-label" for="amount">Amount (USD)</label>
                    <div class="uk-form-controls">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon">$</span>
                            <input class="uk-input" type="number" step="0.01" name="amount" id="amount" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                <div class="uk-margin">
                    <label class="uk-form-label" for="notes">Notes (Optional)</label>
                    <div class="uk-form-controls">
                        <textarea class="uk-textarea" name="notes" id="notes" rows="3" placeholder="Add any notes about this payment"></textarea>
                    </div>
                </div>
                <div class="uk-margin">
                    <button type="submit" name="submit_payment" class="uk-button uk-button-primary">
                        <span uk-icon="credit-card"></span> Record Payment
                    </button>
                </div>
            </form>
        </div>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h3 class="uk-card-title">Your Payment History</h3>
            
            <?php if ($payments_result->num_rows > 0): ?>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Date Paid</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($payment = $payments_result->fetch_assoc()): ?>
                            <tr>
                                <td><strong class="uk-text-success">$<?php echo htmlspecialchars(number_format($payment['amount'], 2)); ?></strong></td>
                                <td><?php echo date("M j, Y", strtotime($payment['payment_date'])); ?></td>
                                <td><?php echo htmlspecialchars($payment['notes']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="uk-alert-primary" uk-alert>
                    <p>You have no payment history.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
