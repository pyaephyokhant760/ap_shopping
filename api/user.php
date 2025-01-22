<?php
header("Content-Type: application/json");
require '../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'GET':
        handleGet($conn);
        break;
    case 'POST':
        handlePost($conn, $input);
        break;
    case 'PUT':
        handlePut($conn, $input);
        break;
    case 'DELETE':
        handleDelete($conn, $input);
        break;
    default:
        echo json_encode(['message' => 'Invalid request method']);
        break;
}

function handleGet($conn) {
    $sql = "SELECT * FROM users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result);
}

function handlePost($conn, $input) {
    $sql = "INSERT INTO users (name, email,phone,address,role) VALUES (:name, :email, :phone, :address, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $input['name'], 'email' => $input['email'], 'phone' => $input['phone'], 'address' => $input['address'], 'role' => $input['role']]);
    echo json_encode(['message' => 'User created successfully']);
}

function handlePut($conn, $input) {
    $sql = "UPDATE users SET name = :name, email = :email, phone = :phone,address = :address, role = :role WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['name' => $input['name'], 'email' => $input['email'],'phone' => $input['phone'], 'address' => $input['address'], 'role' => $input['role'], 'id' => $input['id']]);
    echo json_encode(['message' => 'User updated successfully']);
}

function handleDelete($conn) {
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        echo json_encode(['error' => 'User ID is required']);
        return;
    }

    $sql = "DELETE FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute(['id' => $_GET['id']]);
        echo json_encode(['message' => 'User deleted successfully']);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Failed to delete user: ' . $e->getMessage()]);
    }
}

?>