<?php
// public/includes/funciones_edicion.php

// 1. Función para Edición de TEXTO
function editable($conn, $seccion, $clave, $tag = 'div', $claseExtra = '') {
    $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);
    $contenido = $data ? $data['contenido'] : "Texto pendiente...";

    if (isset($_SESSION['admin_id'])) {
        $clases = "texto-editable" . ($claseExtra ? " $claseExtra" : "");
        echo "<$tag class='$clases' data-seccion='$seccion' data-clave='$clave'>";
        echo $contenido;
        echo "</$tag>";
    } else {
        if ($claseExtra) {
            echo "<$tag class='$claseExtra'>$contenido</$tag>";
        } else {
            echo "<$tag>$contenido</$tag>";
        }
    }
}

// 2. Función para Edición de IMÁGENES
function editableImagen($conn, $seccion, $clave, $rutaDefault, $alt='', $claseExtra='', $estiloInline='') {
    // Buscar en BD
    $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);
    
    // Valor: de la BD o Default
    $src = ($data && !empty($data['contenido'])) ? $data['contenido'] : $rutaDefault;

    // Corrección automática de rutas para index.php vs public/
    // Si NO estamos en public/ y la imagen no tiene public/, se lo agregamos (excepto URLs externas)
    if (strpos($_SERVER['SCRIPT_NAME'], '/public/') === false && strpos($src, 'public/') !== 0 && strpos($src, 'http') !== 0) {
        $src = 'public/' . $src;
    }

    // Renderizar
    if (isset($_SESSION['admin_id'])) {
        echo "<img src='$src' alt='$alt' 
                   class='imagen-editable $claseExtra' 
                   style='$estiloInline'
                   data-seccion='$seccion' 
                   data-clave='$clave'
                   title='Click para cambiar imagen'>";
    } else {
        echo "<img src='$src' alt='$alt' class='$claseExtra' style='$estiloInline'>";
    }
}

// 3. Función SOLO LECTURA (Para mapas, iframes, links)
function obtenerTexto($conn, $seccion, $clave) {
    $query = "SELECT contenido FROM textos WHERE seccion = '$seccion' AND clave = '$clave'";
    $res = mysqli_query($conn, $query);
    $data = mysqli_fetch_assoc($res);
    return $data ? $data['contenido'] : "";
}

// 4. Función ESPECÍFICA para Noticias (Registros de BD con ID)
function editableImagenNoticia($id, $rutaActual, $alt='') {
    
    // Si la ruta está vacía, poner una por defecto
    if (empty($rutaActual)) {
        $rutaActual = 'images/referancia.jpeg';
    }

    if (isset($_SESSION['admin_id'])) {
        // Admin: Clase especial 'imagen-noticia-editable' y data-id
        echo "<img src='$rutaActual' alt='$alt' 
                   class='imagen-noticia-editable' 
                   data-id='$id'
                   style='cursor:pointer; border:2px dashed transparent; transition:all 0.3s;'
                   onmouseover=\"this.style.border='2px dashed #e74c3c'\"
                   onmouseout=\"this.style.border='2px dashed transparent'\"
                   title='Click para cambiar foto de la noticia'>";
    } else {
        // Usuario normal
        echo "<img src='$rutaActual' alt='$alt'>";
    }
}

?>