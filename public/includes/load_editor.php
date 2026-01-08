<?php
// Solo cargar si el usuario es ADMINISTRADOR
if (isset($_SESSION['admin_id'])) {
    
    // 1. Leer el archivo .env manualmente
    $envPath = __DIR__ . '/../../.env'; 
    $apiKey = '';

    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; 
            list($name, $value) = explode('=', $line, 2);
            if (trim($name) == 'TINYMCE_API_KEY') { 
                $apiKey = trim($value);
                break;
            }
        }
    }
    
    $apiKey = $apiKey ?: 'no-api-key-found';

    // 2. Imprimir los Scripts
    echo '';
    echo '<script src="https://cdn.tiny.cloud/1/'.$apiKey.'/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>';

    // --- CORRECCIÓN: Definir la ruta correcta dinámicamente ---
    // Esto evita el error 404 al guardar desde diferentes carpetas
    $rutaBackend = 'php/admin/guardar_inline.php'; // Por defecto (si estuvieras en la raíz)

    // Si la URL actual contiene '/public/' pero NO '/admin/' (Ej: nosotros.php)
    if (strpos($_SERVER['SCRIPT_NAME'], '/public/') !== false && strpos($_SERVER['SCRIPT_NAME'], '/admin/') === false) {
        $rutaBackend = '../php/admin/guardar_inline.php';
    }
    // Si la URL actual contiene '/public/admin/' (Ej: admin.php)
    elseif (strpos($_SERVER['SCRIPT_NAME'], '/public/admin/') !== false) {
        $rutaBackend = '../../php/admin/guardar_inline.php';
    }

    // Pasamos la variable a JavaScript para que edicion-inline.js la use
    echo "<script>var rutaBackend = '$rutaBackend';</script>";
    // -----------------------------------------------------------

    echo '<script src="js/edicion-inline.js"></script>';
    
    // 3. Estilos visuales
    echo '<style>
        .texto-editable:hover { outline: 2px dashed #3498db; cursor: text; min-height: 20px; }
        .texto-editable:focus { outline: none; } 
    </style>';

    echo '<script src="js/edicion-imagenes.js"></script>';
    
    echo '<style>
        .texto-editable:hover { outline: 2px dashed #3498db; cursor: text; }
        
        /* Estilos para Imagen Editable */
        .imagen-editable { transition: all 0.3s ease; }
        .imagen-editable:hover { 
            outline: 4px solid #e74c3c; 
            cursor: pointer; 
            opacity: 0.8;
            filter: brightness(0.9);
        }
    </style>';
}
?>