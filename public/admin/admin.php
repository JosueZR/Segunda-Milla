<?php
session_start();

// 1. Incluir el sistema de permisos (Asegúrate de haber creado este archivo como hablamos)
include("../../php/includes/permisos.php");

// 2. Seguridad: Verificar Login básico
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// 3. Conexión a la Base de Datos
include("../../php/includes/conexion.php");

// 4. Obtener Estadísticas Reales (Las estadísticas las pueden ver todos, o puedes restringirlas también)
// Imágenes
$sql_img = "SELECT COUNT(*) as total FROM multimedia WHERE tipo = 'imagen'";
$total_imagenes = mysqli_fetch_assoc(mysqli_query($conn, $sql_img))['total'];

// Videos
$sql_vid = "SELECT COUNT(*) as total FROM multimedia WHERE tipo LIKE 'video%'";
$res_vid = mysqli_query($conn, $sql_vid);
$data_vid = mysqli_fetch_assoc($res_vid);
$total_videos = $data_vid['total'];

// Noticias
$sql_news = "SELECT COUNT(*) as total FROM noticias";
$total_noticias = mysqli_fetch_assoc(mysqli_query($conn, $sql_news))['total'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Segunda Milla</title>
    <link rel="stylesheet" href="../css/main.css">
    
    <style>
        /* === ESTILOS DEL PANEL ADMIN === */
        body { 
            background-color: #f4f6f9; 
            display: flex; 
            min-height: 100vh; 
            font-family: sans-serif;
            margin: 0;
        }
        
        /* Sidebar Lateral */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            position: fixed;
            height: 100%;
            box-sizing: border-box;
        }
        .sidebar__logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .sidebar h2 { 
            font-size: 1.2rem; 
            margin-top: 10px; 
            margin-bottom: 10px; 
            color: white;
            border-bottom: 1px solid #33834b;
            padding-bottom: 15px; 
        }
        .sidebar a {
            color: #b8c7ce;
            text-decoration: none;
            padding: 12px;
            display: block;
            border-radius: 4px;
            margin-bottom: 5px;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active { 
            background: #33834b; 
            color: white; 
        }
        
        .sidebar .btn-salir {
            background: transparent;
            border: 1px solid #c0392b;
            color: #c0392b;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: auto; 
            width: 100%;
            transition: 0.3s;
        }
        .sidebar .btn-salir:hover {
            background: #c0392b;
            color: white;
        }

        /* Contenido Principal */
        .main-content {
            margin-left: 250px; 
            padding: 40px;
            width: calc(100% - 250px);
        }
        .header-panel { margin-bottom: 30px; }
        .header-panel h1 { color: #33834b; margin-bottom: 5px; } 
        .header-panel p { color: #666; margin-top: 0; }

        /* Estadísticas */
        .dashboard-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-align: center;
            border-left: 5px solid #33834b;
        }
        .stat-card h4 { margin: 0; color: #666; font-size: 0.9rem; text-transform: uppercase; }
        .stat-card p { margin: 10px 0 0 0; font-size: 1.8rem; font-weight: bold; color: #333; }

        /* Grid de Tarjetas */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-align: center;
            transition: transform 0.2s;
            border-top: 4px solid #33834b;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card:hover { transform: translateY(-5px); }
        .card h3 { margin: 0 0 10px 0; color: #333; }
        .card p { font-size: 0.9rem; color: #777; margin-bottom: 20px; flex-grow: 1; }
        
        .btn-card {
            display: inline-block;
            padding: 10px 20px;
            background: #33834b; 
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 0.9rem;
            transition: 0.3s;
        }
        .btn-card:hover { background: #266138; } 
        .card.home { border-top-color: #f39c12; }
    </style>
</head>
<body>

    <nav class="sidebar">
        <div class="sidebar__logo">
            <img src="../images/logo.png" alt="Logo" style="height: 50px;">
            <h2>Admin SM</h2>
        </div>
        
        <a href="#" class="active">🏠 Dashboard</a>
        <a href="../../php/admin/subir_recursos.php?seccion=general">📂 Subir Archivos</a>
        
        <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] === 'super_admin'): ?>
            <a href="../../php/admin/gestionar-usuario.php">👥 Gestión de Usuarios</a>
        <?php endif; ?>
        
        <button class="btn-salir" onclick="location.href='../../php/admin/logout.php'">Cerrar Sesión</button>
    </nav>

    <main class="main-content">
        <div class="header-panel">
            <h1>Panel de Control</h1>
            <p>Bienvenido. Aquí tienes un resumen y accesos directos.</p>
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <h4>Total Imágenes</h4>
                <p><?php echo $total_imagenes; ?></p> 
            </div>
            <div class="stat-card">
                <h4>Total Videos</h4>
                <p><?php echo $total_videos; ?></p>
            </div>
            <div class="stat-card">
                <h4>Noticias Publicadas</h4>
                <p><?php echo $total_noticias; ?></p>
            </div>
        </div>

        <h3 style="color:#666; margin-top:40px;">Editar Páginas Públicas</h3>
        
        <div class="cards-grid">
            
            <?php if(tiene_permiso('inicio')): ?>
            <div class="card home">
                <h3>🏠 Inicio</h3>
                <p>Banner principal y textos de bienvenida.</p>
                <a href="../../index.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('nosotros')): ?>
            <div class="card">
                <h3>👥 Nosotros</h3>
                <p>Historia, Pastor y Creencias.</p>
                <a href="../nosotros.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('que_hacemos')): ?>
            <div class="card">
                <h3>🙏 ¿Qué Hacemos?</h3>
                <p>Horarios y Ministerios.</p>
                <a href="../que_hacemos.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('recursos')): ?>
            <div class="card news">
                <h3>🎥 Recursos</h3>
                <p>Predicaciones y Eventos.</p>
                <a href="../recursos.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('noticias')): ?>
            <div class="card news">
                <h3>📰 Noticias</h3>
                <p>Blog y actualidad.</p>
                <a href="../noticias.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('eventos')): ?>
            <div class="card events">
                <h3>📅 Eventos</h3>
                <p>Calendario y congresos.</p>
                <a href="../eventos.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('donde_estamos')): ?>
            <div class="card">
                <h3>📍 Ubicación</h3>
                <p>Mapa y dirección.</p>
                <a href="../donde_estamos.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

            <?php if(tiene_permiso('contactanos')): ?>
            <div class="card">
                <h3>📞 Contáctanos</h3>
                <p>Teléfonos y correos.</p>
                <a href="../contactanos.php" target="_blank" class="btn-card">Ir a Editar</a>
            </div>
            <?php endif; ?>

        </div>
    </main>

</body>
</html>