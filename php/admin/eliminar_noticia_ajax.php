<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Verificar ID
if (isset($_POST['id'])) {
    
    $id = (int)$_POST['id'];

    // 3. Obtener la ruta de la imagen ANTES de borrar el registro
    $queryImg = "SELECT imagen FROM noticias WHERE id = $id";
    $resImg = mysqli_query($conn, $queryImg);
    $dataImg = mysqli_fetch_assoc($resImg);

    // 4. Borrar de la Base de Datos
    $queryDelete = "DELETE FROM noticias WHERE id = $id";
    
    if (mysqli_query($conn, $queryDelete)) {
        // 5. Si se borró de la BD, borrar el archivo de imagen si existe
        if ($dataImg && !empty($dataImg['imagen'])) {
            // Ajustamos la ruta: estamos en php/admin, la imagen está en public/...
            $rutaFisica = '../../public/' . $dataImg['imagen'];
            
            if (file_exists($rutaFisica)) {
                unlink($rutaFisica); // Borrado físico
            }
        }
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Falta ID']);
}
?>