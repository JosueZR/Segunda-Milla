<?php
session_start();
require 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['usuario'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            header("Location: admin.html"); // Redirige a tu panel actual
            exit();
        }
    }
    
    echo "<script>alert('Datos incorrectos'); window.location='login.html';</script>";
}
?>