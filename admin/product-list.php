<?php
// Header include here..
include './header.php';
require_once '../db.php';

// Fetch all Products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");


// Products Delete Functionality

if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Run delete query
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Product deleted successfully.";
    } else {
        $_SESSION['error'] = "Failed to delete Products";
    }

    // Redirect to avoid repeated deletion on refresh
    header("Location: product-list.php");
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

        <h3> Products </h3>
        <div style="text-align:right;"> 
            <a href="add-product.php">
                <button type="buuton" class="btn btn-secondary" style="border:none; border-radius:0px;"> Add Product </button>
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th> # </th>
                        <th> Name </th>
                        <th> Description </th>
                        <th> Price </th>
                        <th> Date </th>
                        <th> Function </th>
                    </tr>
                </thead>
                <tbody>

                    <?php if ($result->num_rows > 0): ?>
                        <?php 
                            $id = 1;
                            while ($product = $result->fetch_assoc()): ?>
                            <tr>
                                <td> <?= $id++; ?> </td>
                                <td><?= htmlspecialchars($product['name']); ?></td>
                                <td><?= htmlspecialchars($product['description']); ?></td>
                                <td><?= htmlspecialchars($product['price']); ?></td>
                                <td><?= htmlspecialchars($product['created_at']); ?></td>
                                <td> 
                                   <a href="add-product.php?edit=<?= $product['id']; ?>" class="btn btn-light">Edit</a>
                                    <a href="product-list.php?action=delete&id=<?= $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')"> Delete </a> 
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td class="text-center" colspan="6">No Products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include './footer.php'; ?>
