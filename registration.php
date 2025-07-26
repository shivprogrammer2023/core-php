<?php include './include/header.php'; ?>


<!-- PHP Functionality START -->
<?php
// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "corephp";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form handling
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $name = trim($_POST['name']);

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Optional: Check if email already exists
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $msg = "<div class='alert alert-danger'>Email already registered.</div>";
    } else {
        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO users (username, name, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username,$name, $email, $hashed_password);

        if ($stmt->execute()) {
            $msg = "<div class='alert alert-success'>Registration successful!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Something went wrong. Try again.</div>";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<!-- PHP Functionality END -->




<div class="container">
    <div class="py-1 px-2">
        <div class="row justify-content-center my-5">
            <div class="col-md-6">
                <?php if (!empty($msg)) echo $msg; ?>
                <div class="card">
                    <div class="card-header text-white text-center">
                        <h4 class="text-dark">User Registration</h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="" method="post">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" placeholder="Enter Name" autocomplete="off" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" name="username" placeholder="Enter Username" autocomplete="off" class="form-control" id="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" name="email" placeholder="Enter Email" autocomplete="off" class="form-control" id="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" placeholder="Enter Password" autocomplete="off" class="form-control" id="password" required>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-light bg-light text-dark" name="register">Register</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-muted text-center">
                        Already have an account? <a href="login.php">Login here</a>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<?php include './include/footer.php'; ?>