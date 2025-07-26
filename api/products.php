<?php
header("Content-Type: application/json");
require_once '../db.php'; // include your DB connection

$method = $_SERVER['REQUEST_METHOD'];

// For PUT and DELETE (parse input manually)
parse_str(file_get_contents("php://input"), $_PUT_OR_DELETE);

// Handle routes
switch ($method) {
    case 'GET':
        // Get all products or single
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_assoc());
        } else {
            $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = $row;
            }
            echo json_encode($products);
        }
        break;

    case 'POST':
        // Add new product
        $name = $_POST['name'] ?? '';
        $desc = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';

        $stmt = $conn->prepare("INSERT INTO products (name, description, price) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $desc, $price);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Product created', 'id' => $stmt->insert_id]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Insert failed']);
        }
        break;

    case 'PUT':
        // Update product
        $id    = intval($_GET['id'] ?? 0);
        $name  = $_PUT_OR_DELETE['name'] ?? '';
        $desc  = $_PUT_OR_DELETE['description'] ?? '';
        $price = $_PUT_OR_DELETE['price'] ?? '';

        $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssdi", $name, $desc, $price, $id);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Product updated']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Update failed']);
        }
        break;

    case 'DELETE':
        // Delete product
        $id = intval($_GET['id'] ?? 0);
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['message' => 'Product deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Delete failed']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
?>
