<?php
// Header include here..
include './include//header.php';
require_once './db.php';

session_start();
$msg = "";

if (!isset($_SESSION['reset_email'])) {
    header("Location: forgot-password.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp']);

    if ($otp === '1111') {
        header("Location: reset-password.php");
        exit;
    } else {
        $msg = "Invalid OTP";
    }
}


?>

<div class="container vh-100">

<div class="main">

    <div class="content mt-5">
        <div class="row">
            <div class="col-md-3"> </div>
            <div class="col-md-6">
                <div class="card p-4">
                    <form method="post">
                        <h2>Verify OTP</h2>
                        <div class="form-group d-flex">
                            <input type="text" name="otp" value="1111" placeholder="Enter OTP" class="form-control" style="border-radius:0px;" required>
                            <button type="submit" class="btn btn-light btn-light" style="width:150px; border-radius:0px;">Verify</button>
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