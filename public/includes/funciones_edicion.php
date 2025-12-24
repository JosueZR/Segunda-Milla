<?php
function botonEditar($seccion, $clave = null) {
    if (isset($_SESSION['admin_id'])) {
        // Preparar parámetros de URL
        $params = "seccion=$seccion";
        if ($clave) {
            $params .= "&clave=$clave";
        }
        
        // CORRECCIÓN AQUÍ: Usar "../" en lugar de "../../"
        // Porque estamos en 'public/' y queremos ir a 'php/admin/'
        echo "<a href='../php/admin/subir_recursos.php?$params' 
                 style='background:#e74c3c; color:white; padding:4px 8px; border-radius:4px; text-decoration:none; font-size:11px; position:absolute; z-index:1000; top:0; left:0; font-family:sans-serif; opacity:0.8; box-shadow:0 2px 4px rgba(0,0,0,0.2);'>
                 ✎ EDITAR
              </a>";
    }
}
?>