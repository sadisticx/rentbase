<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];
$complaint_id = $_GET['id'];

// Fetch complaint details
$stmt = $conn->prepare("SELECT complaints.*, rooms.room_number, users.username AS tenant_username FROM complaints JOIN rooms ON complaints.room_id = rooms.id JOIN users ON complaints.tenant_id = users.id WHERE complaints.id = ? AND rooms.owner_id = ?");
$stmt->bind_param("ii", $complaint_id, $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$complaint = $result->fetch_assoc();
$stmt->close();

if (!$complaint) {
    header('Location: complaints.php?error=Complaint not found');
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
                <li><a href="complaints.php"><span uk-icon="arrow-left"></span> Back to Complaints</a></li>
                <li><a href="/RentBase/logout.php"><span uk-icon="sign-out"></span> Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="uk-margin-large-top">
        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h2 class="uk-card-title"><?php echo htmlspecialchars($complaint['subject']); ?></h2>
            
            <dl class="uk-description-list uk-description-list-divider uk-margin-medium-top">
                <dt>Room Number</dt>
                <dd><span class="uk-badge"><?php echo htmlspecialchars($complaint['room_number']); ?></span></dd>
                
                <dt>Tenant</dt>
                <dd><?php echo htmlspecialchars($complaint['tenant_username']); ?></dd>
                
                <dt>Status</dt>
                <dd>
                    <?php 
                    $statusClass = $complaint['status'] == 'pending' ? 'uk-label-warning' : 
                                  ($complaint['status'] == 'resolved' ? 'uk-label-success' : 'uk-label-default');
                    ?>
                    <span class="uk-label <?php echo $statusClass; ?>">
                        <?php echo ucfirst(htmlspecialchars($complaint['status'])); ?>
                    </span>
                </dd>
                
                <dt>Date Submitted</dt>
                <dd><?php echo date("F j, Y, g:i a", strtotime($complaint['created_at'])); ?></dd>
            </dl>
            
            <hr class="uk-divider-icon">
            
            <div class="uk-margin-medium-top">
                <h4 class="uk-heading-line"><span>Description</span></h4>
                <p class="uk-text-justify"><?php echo nl2br(htmlspecialchars($complaint['description'])); ?></p>
            </div>
            
            <div class="uk-margin-medium-top">
                <a href="complaints.php" class="uk-button uk-button-default">
                    <span uk-icon="arrow-left"></span> Back to Complaints
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
