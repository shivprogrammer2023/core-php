<?php
// Header include here..
include './header.php';
require_once '../db.php';
?>

<!-- PHP Functionality START -->
<?php
$msg = "";
$name = $username = $email = ""; // default empty
$editing = false;                // flag to check edit mode

// Check if edit requested
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editing = true;

    // Get user details
    $stmt = $conn->prepare("SELECT id, name, username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $name = $user['name'];
        $username = $user['username'];
        $email = $user['email'];
    } else {
        $msg = "<div class='alert alert-danger'>User not found.</div>";
    }
}

// Form Handling (Add or Update)
if (isset($_POST['addnewUser'])) {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";

    if ($editing) {
        // Update query
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ?, password = ? WHERE id = ?");
            $stmt->bind_param("ssssi", $name, $username, $email, $hashed_password, $editId);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name = ?, username = ?, email = ? WHERE id = ?");
            $stmt->bind_param("sssi", $name, $username, $email, $editId);
        }

        if ($stmt->execute()) {
            $_SESSION['success'] = "User updated successfully.";
            header("Location: user-list.php");
            exit;
        } else {
            $msg = "<div class='alert alert-danger'>Update failed. Try again.</div>";
        }
    } else {
        // ADD user (existing logic)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>Email already registered.</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['success'] = "User added successfully.";
                header("Location: user-list.php");
                exit;
            } else {
                $msg = "<div class='alert alert-danger'>Something went wrong. Try again.</div>";
            }
            $stmt->close();
        }

        $check->close();
    }
}
?>

<!-- PHP Functionality END -->

<!-- Sidebar Include here..  -->
<?php include './sidebar.php'; ?>

<div class="main">
    <!-- Navbar include here -->
    <?php include './navbar.php'; ?>
    <div class="content">

        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <?php if (!empty($msg)) echo $msg; ?>
                <div class="card">
                    <div class="card-header text-white text-center">
                        <h4 class="text-dark"> <?= $editing ? 'Edit User' : 'Add New User'; ?> </h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" value="<?= htmlspecialchars($username); ?>" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($email); ?>" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password <?= $editing ? '(Leave blank to keep current)' : ''; ?></label>
                                <input type="password" name="password" class="form-control" id="password" <?= $editing ? '' : 'required'; ?>>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-light bg-light text-dark" name="addnewUser"><?= $editing ? 'Update User' : 'Submit'; ?></button>
                                <?php if ($editing): ?>
                                    <a href="add-user.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                        <a href="user-list.php"> Get All Users </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>