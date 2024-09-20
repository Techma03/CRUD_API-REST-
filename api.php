<?php
header('Content-Type: application/json');
include 'db.php';
$requestMethod = $_SERVER['REQUEST_METHOD'];
switch ($requestMethod) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Read a specific product
            $id = intval($_GET['id']);
            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($product);
        } else {
            // Read all products
            $stmt = $pdo->query("SELECT * FROM products");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($products);
        }
        break;

    case 'POST':
        // Create a new product
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("INSERT INTO products (name, price, description) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['name'], $_POST['price'], $_POST['description']]);
        echo json_encode(['id' => $pdo->lastInsertId()]);
        break;

    case 'PUT':
        // Update an existing product
        $data = json_decode(file_get_contents("php://input"), true);
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->execute([$_GET['name'], $_GET['price'], $_GET['description'], $_GET['id']]);
        echo json_encode(['message' => 'Product updated']);
        break;

    case 'DELETE':
        // Delete a product
        $id = intval($_GET['id']);
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode(['message' => 'Product deleted']);
        break;

    default:
        echo json_encode(['message' => 'Method not allowed']);
        break;
}
?>
