<?php
session_start();
// Ajustamos la ruta para que encuentre el archivo de conexión
require_once '../includes/conexion.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['usuario'];
    $pass = $_POST['password'];

    // Consulta preparada para evitar Inyección SQL
    $stmt = $conn->prepare("SELECT id, password FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Verifica la contraseña (asegúrate de que en la DB esté hasheada con password_hash)
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_user'] = $user;
            // Redirige al panel de administración
            header("Location: ../../public/admin/admin.html"); 
            exit();
        }
    }
    
    echo "<script>alert('Usuario o contraseña incorrectos'); window.location='../../public/admin/admin.html';</script>";
}
?>