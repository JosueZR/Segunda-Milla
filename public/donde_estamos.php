<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

// Obtener la URL del mapa desde la BD
$url_mapa = obtenerTexto($conn, 'donde_estamos', 'mapa_url');

// Si está vacía, ponemos una por defecto (Ej: Zócalo CDMX)
if(empty($url_mapa)) {
    $url_mapa = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.5323259905975!2d-99.1354!3d19.4326!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTnCsDI1JzU3LjQiTiA5OcKwMDgnMDcuNCJX!5e0!3m2!1ses!2smx!4v1600000000000!5m2!1ses!2smx";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dónde Estamos - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    
    <style>
        /* Estilos específicos para esta página */
        .seccion-ubicacion {
            max-width: 1200px; margin: 40px auto; padding: 0 20px;
        }

        /* --- IMAGEN PRINCIPAL --- */
        .imagen-iglesia-principal {
            width: 100%; max-width: 1000px; margin: 0 auto 50px;
            border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            position: relative;
        }
        .imagen-iglesia-principal img {
            width: 100%; height: 500px; object-fit: cover; display: block;
        }

        /* --- CONTENEDOR DEL MAPA --- */
        .mapa-container {
            margin-top: 50px; border-radius: 12px; overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative; /* Importante para el botón flotante */
        }
        .mapa-container iframe {
            width: 100%; height: 450px; border: none; display: block;
        }

        /* Botón Editar Mapa (Flotante) */
        .btn-editar-mapa {
            position: absolute; top: 15px; right: 15px;
            background-color: #f59e0b; color: white; border: none;
            padding: 10px 15px; border-radius: 50px;
            cursor: pointer; font-weight: bold; box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            z-index: 10; transition: transform 0.2s; display: flex; align-items: center; gap: 5px;
        }
        .btn-editar-mapa:hover { transform: scale(1.05); background-color: #d97706; }

        /* --- HORARIOS --- */
        .horarios-servicios {
            background: linear-gradient(135deg, #6aea66 0%, #81a24b 100%);
            color: white; padding: 40px; border-radius: 12px; margin-top: 40px; text-align: center;
        }
        .horarios-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-top: 20px;
        }
        .horario-item {
            background: rgba(255, 255, 255, 0.1); padding: 20px;
            border-radius: 8px; backdrop-filter: blur(10px);
        }

        /* --- MODAL --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 10000;
            display: none; align-items: center; justify-content: center;
        }
        .modal-box {
            background: white; padding: 30px; border-radius: 12px;
            width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .modal-box textarea {
            width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 6px;
            height: 100px; resize: vertical;
        }
        .btn-guardar { background: #34b065; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
        .btn-cancelar { background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-left: 10px; }
        
        @media (max-width: 768px) {
            .imagen-iglesia-principal img { height: 300px; }
            .mapa-container iframe { height: 350px; }
        }
    </style>
</head>
<body>
    
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'donde_estamos', 'titulo_principal', 'h1'); ?>
        <div style="display:inline-block; margin-top:10px;">
             <?php editable($conn, 'donde_estamos', 'subtitulo_header', 'p'); ?>
        </div>
    </header>

    <div class="seccion-ubicacion">
        
        <div class="imagen-iglesia-principal">
            <?php editableImagen($conn, 'donde_estamos', 'foto_fachada', 'images/referancia.jpeg', 'Fachada Iglesia'); ?>
            <div class="imagen-descripcion">
                 <?php editable($conn, 'donde_estamos', 'pie_foto_fachada', 'p'); ?>
            </div>
        </div>

        <div style="text-align:center; margin: 40px 0;">
            <?php editable($conn, 'donde_estamos', 'texto_invitacion', 'h2', 'titulo-seccion'); ?>
            <?php editable($conn, 'donde_estamos', 'direccion_texto', 'p'); ?>
        </div>

        <div class="mapa-container">
            
            <?php if(isset($_SESSION['admin_id'])): ?>
                <button class="btn-editar-mapa" onclick="abrirModalMapa()">
                    ✏️ Editar Ubicación
                </button>
            <?php endif; ?>

            <iframe id="iframeMapa" src="<?php echo $url_mapa; ?>" allowfullscreen loading="lazy"></iframe>
        </div>

        <div class="horarios-servicios">
            <?php editable($conn, 'donde_estamos', 'titulo_horarios', 'h3'); ?>
            
            <div class="horarios-grid">
                <div class="horario-item">
                    <strong>Domingos</strong>
                    <?php editable($conn, 'donde_estamos', 'hora_domingo', 'span'); ?>
                </div>
                <div class="horario-item">
                    <strong>Miércoles</strong>
                    <?php editable($conn, 'donde_estamos', 'hora_miercoles', 'span'); ?>
                </div>
                <div class="horario-item">
                    <strong>Viernes</strong>
                    <?php editable($conn, 'donde_estamos', 'hora_viernes', 'span'); ?>
                </div>
            </div>
        </div>

    </div>

    <?php if(isset($_SESSION['admin_id'])): ?>
    <div id="modalMapa" class="modal-overlay">
        <div class="modal-box">
            <h3 style="color:#34b065; margin-bottom:10px;">Cambiar Ubicación</h3>
            <p style="font-size:0.9rem; color:#666; margin-bottom:10px;">
                Ve a Google Maps, busca tu iglesia, dale a "Compartir" -> "Insertar un mapa" y copia el código HTML.
            </p>
            
            <textarea id="inputMapa" placeholder='Pega aquí el código <iframe...> o la URL'></textarea>
            
            <div style="text-align:right;">
                <button class="btn-guardar" onclick="guardarMapa()">Guardar Cambios</button>
                <button class="btn-cancelar" onclick="cerrarModalMapa()">Cancelar</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalMapa');
        const inputMapa = document.getElementById('inputMapa');
        const iframe = document.getElementById('iframeMapa');

        function abrirModalMapa() {
            modal.style.display = 'flex';
            // Opcional: poner el valor actual en el input
            inputMapa.value = iframe.src;
        }

        function cerrarModalMapa() {
            modal.style.display = 'none';
        }

        function guardarMapa() {
            const contenido = inputMapa.value;
            if(!contenido) return;

            fetch('../php/admin/guardar_mapa_ajax.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ mapa_url: contenido })
            })
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    // Actualizar iframe al instante
                    iframe.src = data.url;
                    alert('✅ Mapa actualizado correctamente');
                    cerrarModalMapa();
                } else {
                    alert('❌ Error: ' + data.message);
                }
            })
            .catch(err => console.error(err));
        }

        modal.addEventListener('click', function(e) {
            if (e.target === this) cerrarModalMapa();
        });
    </script>
    <?php endif; ?>

</body>
</html>