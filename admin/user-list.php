<?php
// Header include here..
include './header.php';
require_once '../db.php';

// Fetch all users
$sql = "SELECT id, username, name, email, created_at, password FROM users ORDER BY id DESC";
$result = $conn->query($sql);



// User Delete Functionality

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $userId = intval($_GET['id']);

    // Run delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "User deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete user.";
    }

    // Redirect to avoid repeated deletion on refresh
    header("Location: user-list.php");
    exit;
}

?>

<!-- Sidebar Include here..  -->
<?php include './sidebar.php'; ?>

<div class="main">
    <!-- Navbar include here -->
    <?php include './navbar.php'; ?>
    <div class="content">
        <!-- Status of Action -->
        <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; ?></div>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; ?></div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h3> Users </h3>
        <div style="text-align:right;"> 
            <a href="add-user.php">
                <button type="buuton" class="btn btn-secondary" style="border:none; border-radius:0px;"> Add User </button>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Name </th>
                        <th> Email </th>
                        <th> Username </th>
                        <th> Password </th>
                        <th> Date </th>
                        <th> Function </th>
                    </tr>
                </thead>
                <tbody>

                    <?php if ($result->num_rows > 0): ?>
                        <?php 
                            $id = 1;
                            while ($user = $result->fetch_assoc()): ?>
                            <tr>
                                <td> <?= $id++; ?> </td>
                                <td><?= htmlspecialchars($user['name']); ?></td>
                                <td><?= htmlspecialchars($user['email']); ?></td>
                                <td><?= htmlspecialchars($user['username']); ?></td>
                                <td><?= htmlspecialchars($user['password']); ?></td>
                                <td><?= htmlspecialchars($user['created_at']); ?></td>
                                <td> 
                                   <a href="add-user.php?edit=<?= $user['id']; ?>" class="btn btn-light">Edit</a>
                                    <a href="user-list.php?action=delete&id=<?= $user['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')"> Delete </a> 
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include './footer.php'; ?>