<?php
session_start();
require_once '../includes/conexion.php'; 
if (!isset($_SESSION['admin_id'])) { header("Location: ../../public/admin/login.html"); exit(); }

// --- LÓGICA PARA CARGAR EL .ENV MANUALMENTE ---
// Buscamos el archivo .env subiendo dos niveles desde esta carpeta
$envPath = __DIR__ . '/../../.env';
$apiKey = 'no-api-key'; // Valor por defecto si falla

if (file_exists($envPath)) {
    // Leemos el archivo línea por línea
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignoramos comentarios que empiecen con #
        if (strpos(trim($line), '#') === 0) continue;
        
        // Separamos nombre y valor
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Si encontramos la clave de TinyMCE, la guardamos
            if ($name === 'TINYMCE_API_KEY') {
                $apiKey = $value;
            }
        }
    }
}
// ----------------------------------------------

$seccion = isset($_GET['seccion']) ? $_GET['seccion'] : 'general';
$clave = isset($_GET['clave']) ? $_GET['clave'] : '';

$link_cancelar = "";
if ($seccion == 'general') { $link_cancelar = "../../public/admin/admin.php"; } 
elseif ($seccion == 'inicio') { $link_cancelar = "../../index.php"; } 
else { $link_cancelar = "../../public/" . $seccion . ".php"; }

$contenido_actual = "";
$modo_edicion_texto = false;

if ($clave != '') {
    $modo_edicion_texto = true;
    $stmt = $conn->prepare("SELECT contenido FROM textos WHERE seccion = ? AND clave = ?");
    $stmt->bind_param("ss", $seccion, $clave);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($fila = $resultado->fetch_assoc()) { $contenido_actual = $fila['contenido']; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editor - <?php echo ucfirst($seccion); ?></title>
    <link rel="stylesheet" href="../../public/css/pages/admin.css">
    
    <script src="https://cdn.tiny.cloud/1/<?php echo $apiKey; ?>/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <style>
        .readonly-input { background: #e2e8f0; color: #718096; cursor: not-allowed; border: 1px solid #cbd5e0; }
        .form-box { background: white; padding: 40px; border-radius: 15px; max-width: 800px; margin: 20px auto; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .tox-tinymce { border-radius: 8px !important; border: 1px solid #ccc !important; }
    </style>
</head>
<body class="body-admin">
    <div class="main">
        <div class="form-box">
            
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; border-bottom:1px solid #eee; padding-bottom:15px;">
                <h2 style="color:#33834b;">✏️ Editando: <span style="color:#2d3748"><?php echo ucfirst($seccion); ?></span></h2>
                <a href="<?php echo $link_cancelar; ?>" style="color:#e74c3c; text-decoration:none; font-weight:bold;">❌ Cancelar</a>
            </div>
            
            <form action="../public/procesar_recursos.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="seccion_origen" value="<?php echo $seccion; ?>">
                
                <div class="form-group">
                    <label style="font-weight:bold; display:block; margin-bottom:8px;">¿Qué deseas hacer?</label>
                    <select name="tipo" id="tipoSelect" onchange="toggleInputs()" style="padding:12px; width:100%; border-radius:8px; border:1px solid #ccc;">
                        <option value="texto" <?php echo $modo_edicion_texto ? 'selected' : ''; ?>>Editar Texto / Información</option>
                        <?php if(!$modo_edicion_texto): ?>
                            <option value="video">Subir Nuevo Video (YouTube)</option>
                            <option value="imagen">Subir Nueva Imagen</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div id="div_video" style="display:none; margin-top:20px; background:#f7fafc; padding:15px; border-radius:8px;">
                    <label style="font-weight:bold;">URL del Video (Embed o Normal):</label>
                    <input type="text" name="url_video" placeholder="Ej: https://youtu.be/..." style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
                    
                    <label style="font-weight:bold; margin-top:15px; display:block;">Categoría del Video:</label>
                    <select name="categoria_video" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                        <option value="predicacion">Predicación / Mensaje</option>
                        <option value="evento">Evento / Momento Especial</option>
                    </select>

                    <label style="font-weight:bold; margin-top:15px; display:block;">Título / Descripción del Video:</label>
                    <input type="text" name="desc_video" placeholder="Ej: Culto Dominical - La Fe" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <div id="div_texto" style="margin-top:20px;">
                    <div class="form-group">
                        <label style="font-weight:bold;">Clave interna (Identificador):</label>
                        <input type="text" name="clave_texto" value="<?php echo $clave; ?>" 
                               class="<?php echo $modo_edicion_texto ? 'readonly-input' : ''; ?>"
                               <?php echo $modo_edicion_texto ? 'readonly' : ''; ?> 
                               placeholder="Ej: titulo_principal" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div class="form-group" style="margin-top:20px;">
                        <label style="font-weight:bold; margin-bottom:10px; display:block;">Contenido:</label>
                        <textarea id="editor_texto" name="contenido_desc" rows="10" style="width:100%;"><?php echo $contenido_actual; ?></textarea>
                    </div>
                </div>

                <div id="div_imagen" style="display:none; margin-top:20px; background:#f7fafc; padding:15px; border-radius:8px;">
                     <label style="font-weight:bold;">Seleccionar Archivo:</label>
                     <input type="file" name="archivo_imagen" style="display:block; margin-top:10px;">
                     
                     <label style="font-weight:bold; margin-top:15px; display:block;">Descripción de la Imagen:</label>
                     <input type="text" name="desc_imagen" placeholder="Ej: Foto del evento" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
                </div>

                <button type="submit" class="btn-atajo" style="margin-top:30px; width:100%; justify-content:center; font-size:1.1rem;">Guardar Cambios</button>
            </form>
        </div>
    </div>

    <script>
        tinymce.init({
            selector: '#editor_texto',
            height: 300,
            menubar: false,
            plugins: 'lists link',
            toolbar: 'bold italic underline | bullist numlist | removeformat',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
        });

        function toggleInputs() {
            const v = document.getElementById('tipoSelect').value;
            document.getElementById('div_video').style.display = 'none';
            document.getElementById('div_texto').style.display = 'none';
            document.getElementById('div_imagen').style.display = 'none';
            
            if (v === 'video') document.getElementById('div_video').style.display = 'block';
            if (v === 'texto') document.getElementById('div_texto').style.display = 'block';
            if (v === 'imagen') document.getElementById('div_imagen').style.display = 'block';
        }
        window.onload = toggleInputs;
    </script>
</body>
</html>