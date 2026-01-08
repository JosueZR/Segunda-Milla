<?php
session_start();
require_once '../includes/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim($_POST['usuario']);
    $pass = trim($_POST['password']);

    // Consulta para buscar al usuario
// Traemos también el rol y los permisos
    $stmt = $conn->prepare("SELECT id, password, rol, permisos FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();

    if ($stmt->error) {
    die("Error en la consulta: " . $stmt->error);
    }
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Compara la contraseña escrita con el hash de la DB
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_user'] = $user;

            $_SESSION['rol'] = $row['rol'];
            $_SESSION['permisos'] = $row['permisos'];
            header("Location: ../../public/admin/admin.php"); 
            exit();
        }
    }
    
    // Si falla, muestra alerta y regresa al LOGIN
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location='../../public/admin/login.html';</script>";
    exit();
}
?>