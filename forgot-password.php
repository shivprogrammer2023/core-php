<?php
// Header include here..
include './include/header.php';
require_once './db.php';
session_start();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['otp'] = '1111'; // static OTP
        header("Location: verify-otp.php");
        exit;
    } else {
        $msg = "Email not found.";
    }
}


?>

<div class="container vh-100">
<div class="main">
    <!-- Navbar include here -->

    <div class="content my-5">
        <div class="row">
            <div class="col-md-3"> </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <form method="post">
                        <h2>Forgot Password</h2>
                        <div class="form-group d-flex">
                            <input type="email" name="email" class="form-control" placeholder="Enter your email" style="border-radius:0px;" required>
                            <button type="submit" class="btn btn-light btn-light" style="width:150px; border-radius:0px;">Send OTP</button>
                        </div>
                        <span style="color:red;"> <?= $msg ?></span>
                    </form>
                </div>
            </div>
            <div class="col-md-3">
            </div>
        </div>
    </div>
</div>
</div>

<?php include './include/footer.php'; ?>
