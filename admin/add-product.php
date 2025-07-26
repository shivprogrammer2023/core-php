<?php
// Header include here..
include './header.php';
require_once '../db.php';
?>

<!-- PHP Functionality START -->
<?php
$msg = "";
$name = $description = $price = ""; // default empty
$editing = false;                // flag to check edit mode

// Check if edit requested
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $editId = intval($_GET['edit']);
    $editing = true;

    // Get Products details
    $stmt = $conn->prepare("SELECT id, name, description, price FROM products WHERE id = ?");
    $stmt->bind_param("i", $editId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $product = $result->fetch_assoc();
        $name = $product['name'];
        $description = $product['description'];
        $price = $product['price'];
    } else {
        $msg = "<div class='alert alert-danger'>Product not found.</div>";
    }
}

// Form Handling (Add or Update)
if (isset($_POST['addNewProduct'])) {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if ($editing) {
        // Update query
        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $description, $price, $editId);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product updated successfully.";
            header("Location: product-list.php");
            exit;
        } else {
            $msg = "<div class='alert alert-danger'>Update failed. Try again.</div>";
        }
        $stmt->close();
    } else {
        // Insert query
        $stmt = $conn->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $description, $price);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Product added successfully.";
            header("Location: product-list.php");
            exit;
        } else {
            $msg = "<div class='alert alert-danger'>Something went wrong. Try again.</div>";
        }
        $stmt->close();
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
                        <h4 class="text-dark"> <?= $editing ? 'Edit Product' : 'Add New Product'; ?> </h4>
                    </div>
                    <div class="card-body">
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" class="form-control" id="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <input type="text" name="description" value="<?= htmlspecialchars($description); ?>" class="form-control" id="description" required>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="text" name="price" value="<?= htmlspecialchars($price); ?>" class="form-control" id="price" required>
                            </div>
                          
                            <div class="text-center">
                                <button type="submit" class="btn btn-light bg-light text-dark" name="addNewProduct"><?= $editing ? 'Update Product' : 'Submit'; ?></button>
                                <?php if ($editing): ?>
                                    <a href="add-product.php" class="btn btn-secondary">Cancel</a>
                                <?php endif; ?>
                            </div>
                        </form>
                        <a href="product-list.php"> Get All Products </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php include './footer.php'; ?>