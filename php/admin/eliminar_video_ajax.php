<?php
session_start();
require_once '../includes/conexion.php';

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $id = (int)$data['id'];
    
    $query = "DELETE FROM multimedia WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Falta ID']);
}
?>