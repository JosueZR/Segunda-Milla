<?php
session_start();
require_once '../includes/conexion.php'; 

// Función para limpiar URLs de YouTube
function convertirYouTubeEmbed($url) {
    if (strpos($url, 'embed') !== false) return $url;
    if (strpos($url, 'youtu.be') !== false) {
        $partes = explode('youtu.be/', $url);
        $id_limpio = explode('?', $partes[1])[0];
        return "https://www.youtube.com/embed/" . $id_limpio;
    }
    if (strpos($url, 'watch?v=') !== false) {
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        return "https://www.youtube.com/embed/" . $params['v'];
    }
    return $url;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['admin_id'])) {
    
    // 1. Recibir datos básicos
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $seccion = isset($_POST['seccion_origen']) ? mysqli_real_escape_string($conn, $_POST['seccion_origen']) : 'general';
    $contenido_texto = isset($_POST['contenido_desc']) ? mysqli_real_escape_string($conn, $_POST['contenido_desc']) : '';

    $mensaje = "";
    $error = false;

    // --- CASO 1: TEXTO ---
    if ($tipo == 'texto') {
        $clave = mysqli_real_escape_string($conn, $_POST['clave_texto']);
        $check = mysqli_query($conn, "SELECT id FROM textos WHERE seccion='$seccion' AND clave='$clave'");
        
        if (mysqli_num_rows($check) > 0) {
            $sql = "UPDATE textos SET contenido = '$contenido_texto' WHERE seccion = '$seccion' AND clave = '$clave'";
        } else {
            $sql = "INSERT INTO textos (seccion, clave, contenido) VALUES ('$seccion', '$clave', '$contenido_texto')";
        }
        
        if (mysqli_query($conn, $sql)) $mensaje = "Texto actualizado.";
        else { $error = true; $mensaje = mysqli_error($conn); }
    } 
    
    // --- CASO 2: VIDEO ---
    else if ($tipo == 'video') {
        $url_original = $_POST['url_video'];
        $url_final = mysqli_real_escape_string($conn, convertirYouTubeEmbed($url_original));
        
        // Obtenemos la descripción
        $desc_video = isset($_POST['desc_video']) ? mysqli_real_escape_string($conn, $_POST['desc_video']) : 'Video sin título';
        
        // --- AQUÍ ESTABA EL PROBLEMA ---
        // Forzamos un valor por defecto si no llega la categoría
        if (isset($_POST['categoria_video']) && !empty($_POST['categoria_video'])) {
            $tipo_db = 'video_' . $_POST['categoria_video']; 
        } else {
            $tipo_db = 'video'; // Valor de seguridad para que NO quede vacío
        }

        $sql = "INSERT INTO multimedia (tipo, url, descripcion) VALUES ('$tipo_db', '$url_final', '$desc_video')";
        
        if (mysqli_query($conn, $sql)) $mensaje = "Video agregado correctamente ($tipo_db).";
        else { $error = true; $mensaje = mysqli_error($conn); }
    }
    
    // --- CASO 3: IMAGEN ---
    else if ($tipo == 'imagen') {
        $target_dir = "../../public/images/uploads/";
        if (!file_exists($target_dir)) mkdir($target_dir, 0777, true);
        
        $nombre_archivo = time() . "_" . basename($_FILES["archivo_imagen"]["name"]);
        $target_file = $target_dir . $nombre_archivo;
        $desc_imagen = isset($_POST['desc_imagen']) ? mysqli_real_escape_string($conn, $_POST['desc_imagen']) : 'Imagen sin descripción';
        
        if (move_uploaded_file($_FILES["archivo_imagen"]["tmp_name"], $target_file)) {
            $url_db = "images/uploads/" . $nombre_archivo;
            // Para imagen el tipo siempre es fijo 'imagen'
            $sql = "INSERT INTO multimedia (tipo, url, descripcion) VALUES ('imagen', '$url_db', '$desc_imagen')";
            
            if (mysqli_query($conn, $sql)) $mensaje = "Imagen subida.";
            else { $error = true; $mensaje = mysqli_error($conn); }
        } else {
            $error = true; $mensaje = "Error al subir archivo.";
        }
    }

    // --- REDIRECCIÓN ---
    if ($error) {
        echo "<script>alert('❌ Error: $mensaje'); window.history.back();</script>";
    } else {
        if ($seccion == 'inicio') $dest = "../../index.php";
        else if ($seccion == 'general') $dest = "../../public/admin/admin.php";
        else $dest = "../../public/" . $seccion . ".php";
        
        echo "<script>alert('✅ $mensaje'); window.location='$dest';</script>";
    }

} else {
    header("Location: ../../public/admin/login.html");
}
?>