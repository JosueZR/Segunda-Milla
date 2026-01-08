<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");

$query_noticias = "SELECT * FROM noticias ORDER BY fecha DESC";
$resultado_noticias = mysqli_query($conn, $query_noticias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/noticias.css">

    <style>
        /* ================= ESTILOS GENERALES ================= */
        .noticia-card { position: relative; }

        .noticia-card.card-agregar {
            background-color: #f0fdf4; border: 2px dashed #34b065;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            cursor: pointer; min-height: 300px; /* Ajustado para verse bien con 16:9 */
            transition: all 0.3s ease;
        }
        .noticia-card.card-agregar:hover { background-color: #dcfce7; transform: translateY(-5px); }
        .card-agregar span { font-size: 4rem; color: #34b065; margin-bottom: 10px; }
        .card-agregar h3 { color: #166534; }

        /* --- BOTONES ADMIN (Editar y Eliminar) --- */
        .btn-admin-flotante {
            position: absolute; top: 10px;
            width: 35px; height: 35px; border-radius: 50%;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2); z-index: 5;
            font-size: 18px; border: none; color: white; transition: transform 0.2s;
        }
        .btn-admin-flotante:hover { transform: scale(1.1); }

        .btn-editar-noticia { right: 55px; background: #f59e0b; }
        .btn-editar-noticia:hover { background: #d97706; }

        .btn-eliminar-noticia { right: 10px; background: #ef4444; }
        .btn-eliminar-noticia:hover { background: #dc2626; }

        /* --- IMÁGENES (FORMATO 16:9 - 1920x1080) --- */
        .noticia-imagen {
            width: 100%;
            aspect-ratio: 16 / 9; /* <--- ESTO ES LA CLAVE PARA 1920x1080 */
            overflow: hidden;
            background-color: #f3f3f3;
        }
        .noticia-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Rellena el rectángulo 16:9 sin deformar */
            object-position: center;
            display: block;
        }

        /* --- MODAL DE LECTURA --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); z-index: 10000;
            display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(5px);
        }

        .modal-leer-box {
            background: white; width: 90%; max-width: 1100px; height: 85vh;      
            border-radius: 15px; overflow: hidden; display: flex;   
            box-shadow: 0 20px 50px rgba(0,0,0,0.3); position: relative;
        }

        .leer-col-img {
            flex: 1; position: relative; background-color: #1a1a1a;
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        #leerFondoBlur {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            object-fit: cover; filter: blur(20px) brightness(0.4); z-index: 1; transform: scale(1.1);
        }
        #leerImagen {
            position: relative; z-index: 2; width: 100%; height: 100%;
            object-fit: contain; box-shadow: 0 0 30px rgba(0,0,0,0.5);
        }

        .leer-col-texto {
            flex: 1.3; padding: 40px; display: flex; flex-direction: column;
            background: white; overflow-y: auto;
        }
        .leer-fecha { color: #34b065; font-weight: bold; font-size: 0.9rem; margin-bottom: 10px; display: block; }
        .leer-titulo { color: #1a202c; font-size: 2rem; margin-bottom: 20px; line-height: 1.2; }
        .leer-contenido { font-size: 1.1rem; line-height: 1.8; color: #4a5568; white-space: pre-wrap; }

        .btn-cerrar-modal {
            position: absolute; top: 15px; right: 20px; font-size: 30px; color: #aaa;
            cursor: pointer; z-index: 10; background: transparent; border: none;
        }
        .btn-cerrar-modal:hover { color: #ef4444; }

        /* --- MODAL ADMIN --- */
        .modal-admin-box {
            background: white; padding: 30px; border-radius: 12px;
            width: 90%; max-width: 500px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .modal-admin-box input, .modal-admin-box textarea {
            width: 100%; padding: 10px; margin-top: 5px; margin-bottom: 15px;
            border: 1px solid #ccc; border-radius: 6px; display: block;
        }
        .input-help {
            font-size: 0.85rem; color: #666; margin-bottom: 8px; display: block;
        }
        .modal-buttons { text-align: right; margin-top: 10px; }
        .btn-guardar { background: #34b065; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; }
        .btn-cancelar { background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; margin-left: 10px; }

        @media (max-width: 768px) {
            .modal-leer-box { flex-direction: column; height: 90vh; }
            .leer-col-img { flex: none; height: 300px; }
            .leer-col-texto { flex: 1; padding: 20px; }
        }
    </style>
</head>
<body>
    
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'noticias', 'titulo_principal', 'h1'); ?>
        <div style="display:inline-block;">
            <?php editable($conn, 'noticias', 'subtitulo_header', 'p'); ?>
        </div>
    </header>

    <section class="contenido">
        <div class="noticias-container">
            
            <?php if(isset($_SESSION['admin_id'])): ?>
                <article class="noticia-card card-agregar" onclick="abrirModalCrear()">
                    <span>+</span>
                    <h3>Nueva Noticia</h3>
                </article>
            <?php endif; ?>

            <?php if(mysqli_num_rows($resultado_noticias) > 0): ?>
                <?php while($fila = mysqli_fetch_assoc($resultado_noticias)): ?>
                    <article class="noticia-card">
                        
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <button class="btn-admin-flotante btn-editar-noticia" 
                                    title="Editar Noticia"
                                    onclick='abrirModalEditar(<?php echo json_encode($fila); ?>)'>
                                ✎
                            </button>

                            <button class="btn-admin-flotante btn-eliminar-noticia" 
                                    title="Eliminar Noticia"
                                    onclick="eliminarNoticia(<?php echo $fila['id']; ?>)">
                                🗑️
                            </button>
                        <?php endif; ?>

                        <div class="noticia-imagen">
                            <?php editableImagenNoticia($fila['id'], $fila['imagen'], $fila['titulo']); ?>
                        </div>
                        <div class="noticia-texto">
                            <span class="fecha"><?php echo date("d M Y", strtotime($fila['fecha'])); ?></span>
                            <h2><?php echo $fila['titulo']; ?></h2>
                            <p><?php echo substr($fila['contenido'], 0, 150) . '...'; ?></p>
                            
                            <a href="javascript:void(0)" 
                               class="leer-mas" 
                               onclick='abrirModalLeer(<?php echo json_encode($fila); ?>)'>
                               Leer más →
                            </a>

                        </div>
                    </article>
                <?php endwhile; ?>
            <?php elseif(!isset($_SESSION['admin_id'])): ?>
                <p style="text-align:center; width:100%;">No hay noticias.</p>
            <?php endif; ?>

        </div>
    </section>

    <?php if(isset($_SESSION['admin_id'])): ?>
    <div id="modalNoticia" class="modal-overlay">
        <div class="modal-admin-box">
            <h2 id="tituloModal" style="color:#34b065; margin-bottom:15px;">Publicar Noticia</h2>
            
            <form id="formNoticia">
                <input type="hidden" id="idNoticia">

                <label style="font-weight:bold;">Título:</label>
                <input type="text" id="tituloNoticia" placeholder="Título..." required>

                <label style="font-weight:bold;">Descripción / Contenido:</label>
                <textarea id="contenidoNoticia" rows="5" placeholder="Contenido completo..." required></textarea>

                <label style="font-weight:bold;">Imagen (Opcional al editar):</label>
                <span class="input-help">ℹ️ Dimensión recomendada: <strong>1920 x 1080 px</strong> (Formato 16:9)</span>
                
                <input type="file" id="imagenNoticia" accept="image/*">

                <div class="modal-buttons">
                    <button type="button" class="btn-guardar" onclick="guardarNoticia()">Guardar</button>
                    <button type="button" class="btn-cancelar" onclick="cerrarModalAdmin()">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <div id="modalLeer" class="modal-overlay">
        <div class="modal-leer-box">
            <button class="btn-cerrar-modal" onclick="cerrarModalLeer()">&times;</button>
            
            <div class="leer-col-img">
                <img id="leerFondoBlur" src="" alt="">
                <img id="leerImagen" src="" alt="Imagen Noticia">
            </div>

            <div class="leer-col-texto">
                <span id="leerFecha" class="leer-fecha"></span>
                <h2 id="leerTitulo" class="leer-titulo"></h2>
                <div id="leerContenido" class="leer-contenido"></div>
            </div>
        </div>
    </div>

    <script>
        // MODAL LECTURA
        const modalLeer = document.getElementById('modalLeer');
        const leerImagen = document.getElementById('leerImagen');
        const leerFondoBlur = document.getElementById('leerFondoBlur');
        const leerTitulo = document.getElementById('leerTitulo');
        const leerFecha = document.getElementById('leerFecha');
        const leerContenido = document.getElementById('leerContenido');

        function abrirModalLeer(datos) {
            let rutaImg = datos.imagen;
            if(!rutaImg) rutaImg = 'images/referancia.jpeg';
            
            leerImagen.src = rutaImg;
            leerFondoBlur.src = rutaImg;
            leerTitulo.innerText = datos.titulo;
            leerFecha.innerText = new Date(datos.fecha).toLocaleDateString('es-ES', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
            leerContenido.innerText = datos.contenido;

            modalLeer.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalLeer() {
            modalLeer.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
        modalLeer.addEventListener('click', function(e) {
            if (e.target === this) cerrarModalLeer();
        });

        // FUNCIONES ADMIN
        <?php if(isset($_SESSION['admin_id'])): ?>
            
            function eliminarNoticia(id) {
                if(!confirm('¿Estás seguro de eliminar esta noticia?')) return;
                let formData = new FormData();
                formData.append('id', id);

                fetch('../php/admin/eliminar_noticia_ajax.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        alert('🗑️ Noticia eliminada');
                        location.reload();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(err => console.error(err));
            }

            const modalAdmin = document.getElementById('modalNoticia');
            const idInput = document.getElementById('idNoticia');
            const tituloInput = document.getElementById('tituloNoticia');
            const contenidoInput = document.getElementById('contenidoNoticia');
            const imagenInput = document.getElementById('imagenNoticia');
            const tituloModal = document.getElementById('tituloModal');

            function abrirModalCrear() {
                idInput.value = ''; 
                tituloInput.value = '';
                contenidoInput.value = '';
                imagenInput.value = ''; 
                tituloModal.innerText = 'Nueva Noticia';
                modalAdmin.style.display = 'flex';
            }

            function abrirModalEditar(datos) {
                idInput.value = datos.id;
                tituloInput.value = datos.titulo;
                contenidoInput.value = datos.contenido;
                imagenInput.value = ''; 
                tituloModal.innerText = 'Editar Noticia';
                modalAdmin.style.display = 'flex';
            }

            function cerrarModalAdmin() {
                modalAdmin.style.display = 'none';
            }

            function guardarNoticia() {
                const id = idInput.value;
                const titulo = tituloInput.value;
                const contenido = contenidoInput.value;

                if(!titulo || !contenido) {
                    alert('Título y contenido son obligatorios');
                    return;
                }

                let formData = new FormData();
                formData.append('titulo', titulo);
                formData.append('contenido', contenido);
                
                if(imagenInput.files.length > 0) {
                    formData.append('imagen', imagenInput.files[0]);
                }

                let urlDestino = id ? '../php/admin/editar_noticia_ajax.php' : '../php/admin/guardar_noticia_ajax.php';
                if(id) formData.append('id', id);

                fetch(urlDestino, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        alert(id ? '✅ Actualizado' : '✅ Creado');
                        location.reload();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(err => console.error(err));
            }
        <?php endif; ?>
    </script>

</body>
</html>