<?php include './include/header.php'; ?>

<!-- PHP Functionality START -->
<?php
session_start();
require_once 'db.php';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate
    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        // Prepare statement
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check user exists
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "No user found with that username.";
        }

        $stmt->close();
    }
}
?>

<!-- PHP Functionality END -->

<div class="container">
    <div class="py-3 my-4 px-2">
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                 <?php if (!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <div class="card">
                    <div class="card-header text-white text-center">
                        <h4 class="text-dark">User Login </h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" placeholder="Enter Username" autocomplete="off" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" placeholder="Enter Password" autocomplete="off" class="form-control" id="password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-light bg-light text-dark" name="login">Login</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-center">
                        if you don't have an account? <a href="registration.php">Registration</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './include/footer.php'; ?>