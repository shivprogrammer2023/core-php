<?php
// Header include here..
include './include/header.php';
require_once './db.php';
session_start();

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);
    $email = $_SESSION['reset_email'];

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $new_password, $email);

    if ($stmt->execute()) {
        unset($_SESSION['reset_email']);
        $msg = "Password reset successfully <a href='login.php'>Login </a> ";
    } else {
        $msg = "Failed to reset password.";
    }
}
?>

<div class="container vh-100 my-4">
<div class="main">
 
    <div class="content">
        <div class="row">
            <div class="col-md-4"> </div>
            <div class="col-md-4">
                <div class="card p-4">
                    <form method="post">
                        <h2>Reset Password</h2>
                        <div class="form-group d-flex">
                            <input type="password" name="password" placeholder="New Password" class="form-control" style="border-radius:0px;" required>
                            <button type="submit" class="btn btn-light btn-light" style="width:150px; border-radius:0px;">Reset</button>
                        </div>
                        <span style="color:red;"> <?= $msg ?></span>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
            </div>
        </div>
    </div>
</div>
</div>

<?php include './include/footer.php'; ?>