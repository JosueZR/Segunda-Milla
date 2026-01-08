<?php
// Solo cargar si el usuario es ADMINISTRADOR
if (isset($_SESSION['admin_id'])) {
    
    // 1. Leer el archivo .env manualmente (Si no usas librerías como phpdotenv)
    // Ajusta la ruta __DIR__ según donde esté tu .env real. 
    // Asumimos que .env está en la raíz del proyecto (2 niveles arriba de este archivo)
    $envPath = __DIR__ . '/../../.env'; 
    $apiKey = '';

    if (file_exists($envPath)) {
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Ignorar comentarios
            list($name, $value) = explode('=', $line, 2);
            if (trim($name) == 'TINYMCE_API_KEY') { // Cambia esto por el nombre en tu .env
                $apiKey = trim($value);
                break;
            }
        }
    }
    
    // Si no encuentras la key, usa una por defecto o deja vacío (TinyMCE mostrará advertencia)
    $apiKey = $apiKey ?: 'no-api-key-found';

    // 2. Imprimir los Scripts
    // Nota: Ajusta src="js/..." si incluyes esto desde el root (index.php) vs carpeta public
    // Una forma segura es usar rutas absolutas si tu servidor lo permite, o condicionales.
    echo '';
    echo '<script src="https://cdn.tiny.cloud/1/'.$apiKey.'/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>';
    // Aquí asumimos que se llama desde dentro de "public/". Si es index, ajusta la ruta.
    echo '<script src="js/edicion-inline.js"></script>';
    
    // 3. Estilos visuales para saber qué es editable
    echo '<style>
        .texto-editable:hover { outline: 2px dashed #3498db; cursor: text; min-height: 20px; }
        .texto-editable:focus { outline: none; } /* TinyMCE pone su propio borde */
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