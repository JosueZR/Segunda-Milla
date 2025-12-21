<?php
session_start();
require_once '../includes/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['usuario']);
    $pass = trim($_POST['password']);

    // Consulta para buscar al usuario
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Compara la contraseña escrita con el hash de la DB
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_user'] = $user;
            header("Location: ../../public/admin/admin.html"); 
            exit();
        }
    }
    
    // Si falla, muestra alerta y regresa al LOGIN
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location='../../public/admin/login.html';</script>";
    exit();
}
?>