<?php
session_start();
require_once '../includes/conexion.php';

// 1. Seguridad
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No autorizado']);
    exit;
}

// 2. Recibir URL
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['mapa_url'])) {
    
    $input = $data['mapa_url'];
    $urlFinal = '';

    // 3. Lógica Inteligente: ¿Pegó solo la URL o todo el iframe?
    
    // Si contiene "<iframe", usamos una expresión regular para sacar el src="..."
    if (strpos($input, '<iframe') !== false) {
        preg_match('/src="([^"]+)"/', $input, $match);
        if(isset($match[1])) {
            $urlFinal = $match[1];
        }
    } else {
        // Si no, asumimos que es la URL directa
        $urlFinal = $input;
    }

    // Limpieza básica
    $urlFinal = mysqli_real_escape_string($conn, $urlFinal);

    // 4. Guardar en la tabla TEXTOS (usaremos seccion='donde_estamos', clave='mapa_url')
    // Primero intentamos actualizar
    $query = "UPDATE textos SET contenido = '$urlFinal' WHERE seccion = 'donde_estamos' AND clave = 'mapa_url'";
    mysqli_query($conn, $query);

    // Si no se actualizó nada (porque no existía), insertamos
    if (mysqli_affected_rows($conn) == 0) {
        // Verificamos si existe para evitar duplicados en lógica rara
        $check = mysqli_query($conn, "SELECT id FROM textos WHERE seccion = 'donde_estamos' AND clave = 'mapa_url'");
        if(mysqli_num_rows($check) == 0) {
            $insert = "INSERT INTO textos (seccion, clave, contenido) VALUES ('donde_estamos', 'mapa_url', '$urlFinal')";
            mysqli_query($conn, $insert);
        }
    }

    echo json_encode(['status' => 'success', 'url' => $urlFinal]);

} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
}
?>