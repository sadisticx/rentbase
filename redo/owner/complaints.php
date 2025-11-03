<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'owner') {
    header('Location: /RentBase/index.php?error=Access Denied');
    exit();
}
include __DIR__ . '/../includes/header.php';
include __DIR__ . '/../includes/db_connect.php';

$owner_id = $_SESSION['user_id'];

// Fetch complaints for the owner's rooms
$stmt = $conn->prepare("SELECT complaints.*, rooms.room_number, users.username AS tenant_username FROM complaints JOIN rooms ON complaints.room_id = rooms.id JOIN users ON complaints.tenant_id = users.id WHERE rooms.owner_id = ? ORDER BY complaints.created_at DESC");
$stmt->bind_param("i", $owner_id);
$stmt->execute();
$result = $stmt->get_result();
$complaints = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

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
        <h1 class="uk-heading-medium uk-margin-medium-bottom">View Complaints</h1>

        <div class="uk-card uk-card-default uk-card-body uk-border-rounded">
            <h3 class="uk-card-title">Complaints in Your Rooms</h3>
            
            <?php if (empty($complaints)): ?>
                <div class="uk-alert-primary" uk-alert>
                    <p>No complaints have been submitted yet.</p>
                </div>
            <?php else: ?>
                <div class="uk-overflow-auto">
                    <table class="uk-table uk-table-divider uk-table-hover uk-table-middle">
                        <thead>
                            <tr>
                                <th class="uk-table-shrink">#</th>
                                <th>Room</th>
                                <th>Tenant</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="uk-text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; foreach ($complaints as $complaint): ?>
                                <tr>
                                    <td><?php echo $count++; ?></td>
                                    <td><span class="uk-badge"><?php echo htmlspecialchars($complaint['room_number']); ?></span></td>
                                    <td><?php echo htmlspecialchars($complaint['tenant_username']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($complaint['subject']); ?></strong></td>
                                    <td>
                                        <?php 
                                        $statusClass = $complaint['status'] == 'pending' ? 'uk-label-warning' : 
                                                      ($complaint['status'] == 'resolved' ? 'uk-label-success' : 'uk-label-default');
                                        ?>
                                        <span class="uk-label <?php echo $statusClass; ?>">
                                            <?php echo ucfirst(htmlspecialchars($complaint['status'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date("M j, Y", strtotime($complaint['created_at'])); ?></td>
                                    <td class="uk-text-right">
                                        <a href="view_complaint.php?id=<?php echo $complaint['id']; ?>" class="uk-button uk-button-small uk-button-primary" uk-tooltip="View Details">
                                            <span uk-icon="icon: file-text"></span> View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
