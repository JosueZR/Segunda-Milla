<?php 
session_start(); 
include("../php/includes/conexion.php"); 
include("includes/funciones_edicion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Segunda Milla</title>
    <link rel="stylesheet" href="css/main.css">
    
    <style>
        /* ================= ESTILOS LISTA DE EVENTOS ================= */
        
        /* Contenedor Principal */
        .eventos-lista {
            display: flex;
            flex-direction: column; /* Lista vertical */
            gap: 30px;
            max-width: 1000px; /* Ancho máximo para que no se estire demasiado */
            margin: 50px auto;
            padding: 0 20px;
        }

        /* Tarjeta Individual (Estilo Fila) */
        .grupo-card {
            display: flex;
            flex-direction: row; /* Imagen izquierda, texto derecha */
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-height: 250px; /* Altura mínima para que se vea sustancial */
            border: 1px solid #f0f0f0;
        }

        .grupo-card:hover {
            transform: translateX(5px); /* Efecto sutil a la derecha */
            box-shadow: 0 8px 25px rgba(0,153,61,0.15);
            border-color: #34b065;
        }

        /* Imagen del Grupo (Izquierda) */
        .grupo-imagen {
            flex: 0 0 40%; /* Ocupa el 40% del ancho de la tarjeta */
            position: relative;
            overflow: hidden;
            background-color: #f3f3f3;
        }

        .grupo-imagen img {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Llena el espacio sin deformarse */
            transition: transform 0.5s ease;
        }

        .grupo-card:hover .grupo-imagen img {
            transform: scale(1.05);
        }

        /* Contenido (Derecha) */
        .grupo-contenido {
            flex: 1; /* Ocupa el resto del espacio */
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: center; /* Centrado vertical */
            text-align: left; /* Alineado a la izquierda */
        }

        .grupo-titulo {
            color: #00993d;
            font-size: 1.8rem;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .grupo-desc {
            color: #4a5568;
            font-size: 1.05rem;
            margin-bottom: 25px;
            line-height: 1.6;
        }

        /* Botón "Soy..." */
        .btn-identidad {
            background-color: #34b065;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            align-self: flex-start; /* El botón se ajusta a su contenido, no al ancho total */
        }

        .btn-identidad:hover {
            background-color: #007a30;
            box-shadow: 0 4px 12px rgba(52, 176, 101, 0.3);
        }

        /* --- RESPONSIVE (MÓVIL) --- */
        @media (max-width: 768px) {
            .grupo-card {
                flex-direction: column; /* En móvil, imagen arriba texto abajo */
                min-height: auto;
            }
            .grupo-imagen {
                flex: none;
                width: 100%;
                height: 200px; /* Altura fija para la imagen en móvil */
            }
            .grupo-contenido {
                padding: 25px;
                text-align: center; /* Centrado en móvil */
            }
            .btn-identidad {
                align-self: center; /* Botón centrado en móvil */
                width: 100%;
            }
        }

        /* Modal de Registro */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 10000;
            display: none; align-items: center; justify-content: center;
            backdrop-filter: blur(4px);
        }
        .modal-box {
            background: white; padding: 40px; border-radius: 15px;
            width: 90%; max-width: 450px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            position: relative; text-align: center;
        }
        .modal-box h2 { color: #34b065; margin-bottom: 20px; }
        .modal-box input {
            width: 100%; padding: 12px; margin: 10px 0;
            border: 2px solid #e2e8f0; border-radius: 8px;
            font-size: 1rem; outline: none; transition: border 0.3s;
        }
        .modal-box input:focus { border-color: #34b065; }
        
        .btn-enviar-registro {
            background: #34b065; color: white; width: 100%; padding: 12px;
            border: none; border-radius: 8px; font-weight: bold; font-size: 1.1rem;
            cursor: pointer; margin-top: 15px;
        }
        .btn-cerrar-modal {
            position: absolute; top: 15px; right: 20px;
            background: none; border: none; font-size: 24px; color: #aaa; cursor: pointer;
        }
        .btn-cerrar-modal:hover { color: #ef4444; }

    </style>
</head>
<body>
    
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/load_editor.php"); ?>

    <header class="encabezado">
        <?php editable($conn, 'eventos', 'titulo_principal', 'h1'); ?>
        <div style="display:inline-block; margin-top:10px;">
            <?php editable($conn, 'eventos', 'subtitulo_header', 'p'); ?>
        </div>
    </header>

    <section class="eventos-lista">
        
        <article class="grupo-card">
            <div class="grupo-imagen">
                <?php editableImagen($conn, 'eventos', 'img_varones', 'images/referancia.jpeg', 'Varones'); ?>
            </div>
            <div class="grupo-contenido">
                <?php editable($conn, 'eventos', 'titulo_varones', 'h3', 'grupo-titulo'); ?>
                <?php editable($conn, 'eventos', 'desc_varones', 'p', 'grupo-desc'); ?>
                
                <button class="btn-identidad" onclick="abrirModalRegistro('Varones')">
                    Soy Varón
                </button>
            </div>
        </article>

        <article class="grupo-card">
            <div class="grupo-imagen">
                <?php editableImagen($conn, 'eventos', 'img_damas', 'images/referancia.jpeg', 'Damas'); ?>
            </div>
            <div class="grupo-contenido">
                <?php editable($conn, 'eventos', 'titulo_damas', 'h3', 'grupo-titulo'); ?>
                <?php editable($conn, 'eventos', 'desc_damas', 'p', 'grupo-desc'); ?>
                
                <button class="btn-identidad" onclick="abrirModalRegistro('Damas')">
                    Soy Dama
                </button>
            </div>
        </article>

        <article class="grupo-card">
            <div class="grupo-imagen">
                <?php editableImagen($conn, 'eventos', 'img_jovenes', 'images/referancia.jpeg', 'Jóvenes'); ?>
            </div>
            <div class="grupo-contenido">
                <?php editable($conn, 'eventos', 'titulo_jovenes', 'h3', 'grupo-titulo'); ?>
                <?php editable($conn, 'eventos', 'desc_jovenes', 'p', 'grupo-desc'); ?>
                
                <button class="btn-identidad" onclick="abrirModalRegistro('Jóvenes')">
                    Soy Joven
                </button>
            </div>
        </article>

        <article class="grupo-card">
            <div class="grupo-imagen">
                <?php editableImagen($conn, 'eventos', 'img_ninos', 'images/referancia.jpeg', 'Niños'); ?>
            </div>
            <div class="grupo-contenido">
                <?php editable($conn, 'eventos', 'titulo_ninos', 'h3', 'grupo-titulo'); ?>
                <?php editable($conn, 'eventos', 'desc_ninos', 'p', 'grupo-desc'); ?>
                
                <button class="btn-identidad" onclick="abrirModalRegistro('Niños')">
                    Soy Niño / Padre
                </button>
            </div>
        </article>

    </section>

    <div id="modalRegistro" class="modal-overlay">
        <div class="modal-box">
            <button class="btn-cerrar-modal" onclick="cerrarModalRegistro()">&times;</button>
            
            <h2 id="tituloModalGrupo">Registro</h2>
            <p style="color:#666; margin-bottom:20px;">Déjanos tus datos para informarte sobre los eventos de este grupo.</p>
            
            <form id="formRegistroGrupo" onsubmit="enviarRegistro(event)">
                <input type="hidden" id="grupoSeleccionado" name="grupo">
                
                <input type="text" placeholder="Tu Nombre Completo" required>
                <input type="tel" placeholder="Tu Teléfono / WhatsApp" required>
                
                <button type="submit" class="btn-enviar-registro">Unirme al Grupo</button>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalRegistro');
        const tituloModal = document.getElementById('tituloModalGrupo');
        const inputGrupo = document.getElementById('grupoSeleccionado');

        function abrirModalRegistro(grupo) {
            tituloModal.innerText = 'Eventos para ' + grupo;
            inputGrupo.value = grupo;
            modal.style.display = 'flex';
        }

        function cerrarModalRegistro() {
            modal.style.display = 'none';
        }

        modal.addEventListener('click', function(e) {
            if (e.target === this) cerrarModalRegistro();
        });

        function enviarRegistro(e) {
            e.preventDefault();
            const grupo = inputGrupo.value;
            alert('¡Gracias! Te hemos registrado en la lista de ' + grupo + '. Pronto te contactaremos.');
            cerrarModalRegistro();
        }
    </script>

</body>
</html>