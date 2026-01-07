<?php
session_start();

// 1. Verificar Sesión
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../public/admin/login.html");
    exit();
}

// 2. Conexión
include("../includes/conexion.php");

// Inicializar variable de alerta
$mensaje_alerta = "";

// 3. RECUPERAR MENSAJES DE SESIÓN (Si venimos de una redirección)
if (isset($_SESSION['msg'])) {
    $mensaje_alerta = $_SESSION['msg'];
    unset($_SESSION['msg']); // Borramos el mensaje para que no salga otra vez al recargar
}

// --- LÓGICA: CREAR USUARIO ---
if (isset($_POST['crear_usuario'])) {
    $nuevo_user = mysqli_real_escape_string($conn, $_POST['nuevo_user']);
    $nuevo_pass = $_POST['nuevo_pass'];
    $pass_hash = password_hash($nuevo_pass, PASSWORD_DEFAULT);
    
    // Verificar si el usuario ya existe antes de insertar
    $check = mysqli_query($conn, "SELECT id FROM usuarios WHERE usuario = '$nuevo_user'");
    
    if (mysqli_num_rows($check) > 0) {
        $mensaje_alerta = "<script>alert('⚠️ El nombre de usuario ya existe.');</script>";
    } else {
        $sql = "INSERT INTO usuarios (usuario, password) VALUES ('$nuevo_user', '$pass_hash')";
        if (mysqli_query($conn, $sql)) {
            // ÉXITO: Guardamos mensaje y REDIRIGIMOS para evitar reenvío de formulario
            $_SESSION['msg'] = "<script>alert('✅ Usuario creado correctamente');</script>";
            header("Location: gestionar-usuario.php");
            exit(); // Importante detener el script aquí
        } else {
            $mensaje_alerta = "<script>alert('❌ Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// --- LÓGICA: ELIMINAR USUARIO ---
if (isset($_GET['eliminar'])) {
    $id_borrar = intval($_GET['eliminar']);
    if ($id_borrar != $_SESSION['admin_id']) {
        mysqli_query($conn, "DELETE FROM usuarios WHERE id = $id_borrar");
        
        $_SESSION['msg'] = "<script>alert('🗑️ Usuario eliminado.');</script>";
        header("Location: gestionar-usuario.php"); 
        exit();
    } else {
        $mensaje_alerta = "<script>alert('⚠️ No puedes eliminarte a ti mismo.');</script>";
    }
}

// --- LÓGICA: GENERAR FILAS DE LA TABLA ---
$filas_tabla_html = "";
$res = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id ASC");

while ($row = mysqli_fetch_assoc($res)) {
    $id = $row['id'];
    $user = $row['usuario'];
    
    $boton_accion = "";
    if ($id == $_SESSION['admin_id']) {
        $boton_accion = "<span style='color:#aaa; font-size:0.85rem;'>(Tú)</span>";
    } else {
        $boton_accion = "<a href='gestionar-usuario.php?eliminar=$id' class='btn-eliminar' onclick=\"return confirm('¿Eliminar a $user?')\">🗑️ Eliminar</a>";
    }

    $filas_tabla_html .= "
    <tr>
        <td>#$id</td>
        <td><strong>$user</strong></td>
        <td><span style='color:#33834b; background:#e6f4ea; padding:4px 8px; border-radius:4px; font-size:12px;'>Activo</span></td>
        <td style='text-align:right;'>$boton_accion</td>
    </tr>";
}

// 4. CARGAR Y MOSTRAR LA PLANTILLA
$ruta_template = '../../public/admin/gestionar-usuario.html';

if (file_exists($ruta_template)) {
    $html_template = file_get_contents($ruta_template);
    
    // Reemplazos
    $html_final = str_replace('{{ALERTA}}', $mensaje_alerta, $html_template);
    $html_final = str_replace('{{LISTA_USUARIOS}}', $filas_tabla_html, $html_final);

    echo $html_final;
} else {
    echo "Error: No se encuentra la plantilla en $ruta_template";
}
?>