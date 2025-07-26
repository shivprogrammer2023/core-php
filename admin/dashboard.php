<?php
// Header include here..
include './header.php';
require_once '../db.php';


// Count users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$row = $result->fetch_assoc();
$totalUsers = $row['total_users'];

$products = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$productsrow = $products->fetch_assoc();
$totalProducts = $productsrow['total_products'];

?>

<!-- Sidebar Include here..  -->
<?php include './sidebar.php'; ?>

<div class="main">
    <!-- Navbar include here -->
    <?php include './navbar.php'; ?>
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <h2>Users</h2>
                    <p> <?= $totalUsers; ?> </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <h3>Products </h3>
                    <p> <?= $totalProducts; ?> </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>