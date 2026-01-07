<?php
session_start();

// 1. Seguridad: Verificar Login primero
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../../public/admin/login.html");
    exit();
}

// 2. Seguridad: Verificar si es Super Admin
// Si no es super_admin, detenemos la ejecución aquí mismo.
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'super_admin') {
    die("⛔ Acceso Denegado: Solo los Super Administradores pueden gestionar usuarios.");
}

// 3. Conexión
include("../includes/conexion.php");

// Inicializar variable de alerta
$mensaje_alerta = "";

// 4. RECUPERAR MENSAJES DE SESIÓN (Si venimos de una redirección)
if (isset($_SESSION['msg'])) {
    $mensaje_alerta = $_SESSION['msg'];
    unset($_SESSION['msg']); // Borramos el mensaje para que no salga otra vez al recargar
}

// --- LÓGICA: CREAR USUARIO ---
if (isset($_POST['crear_usuario'])) {
    $nuevo_user = mysqli_real_escape_string($conn, $_POST['nuevo_user']);
    $nuevo_pass = $_POST['nuevo_pass'];
    
    // --- Lógica de Roles y Permisos ---
    $rol = $_POST['rol'];
    $permisos_txt = "";

    // Si es editor, juntamos los checkboxes en una cadena (ej: "inicio,nosotros")
    if ($rol === 'editor' && isset($_POST['permisos'])) {
        // Escapamos los datos por seguridad, aunque vengan de checkboxes
        $permisos_seguros = array_map(function($p) use ($conn) {
            return mysqli_real_escape_string($conn, $p);
        }, $_POST['permisos']);
        
        $permisos_txt = implode(',', $permisos_seguros);
    }
    // ----------------------------------

    $pass_hash = password_hash($nuevo_pass, PASSWORD_DEFAULT);
    
    // Verificar si el usuario ya existe antes de insertar
    $check = mysqli_query($conn, "SELECT id FROM usuarios WHERE usuario = '$nuevo_user'");
    
    if (mysqli_num_rows($check) > 0) {
        $mensaje_alerta = "<script>alert('⚠️ El nombre de usuario ya existe.');</script>";
    } else {
        // INSERT ACTUALIZADO CON ROL Y PERMISOS
        $sql = "INSERT INTO usuarios (usuario, password, rol, permisos) 
                VALUES ('$nuevo_user', '$pass_hash', '$rol', '$permisos_txt')";
        
        if (mysqli_query($conn, $sql)) {
            // ÉXITO: Guardamos mensaje y REDIRIGIMOS
            $_SESSION['msg'] = "<script>alert('✅ Usuario creado correctamente');</script>";
            header("Location: gestionar-usuario.php");
            exit(); 
        } else {
            $mensaje_alerta = "<script>alert('❌ Error: " . mysqli_error($conn) . "');</script>";
        }
    }
}

// --- LÓGICA: ELIMINAR USUARIO ---
if (isset($_GET['eliminar'])) {
    $id_borrar = intval($_GET['eliminar']);
    
    // Evitar auto-eliminación
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
    
    // Recuperamos el rol para mostrarlo en la tabla
    // Si la columna 'rol' está vacía en la BD antigua, mostramos 'editor' por defecto visualmente
    $rol_db = isset($row['rol']) ? $row['rol'] : 'editor'; 
    
    // Estilo visual para el rol
    $rol_display = ($rol_db === 'super_admin') 
        ? "<span style='color:white; background:#e67e22; padding:3px 8px; border-radius:4px; font-size:11px;'>Super Admin</span>"
        : "<span style='color:white; background:#3498db; padding:3px 8px; border-radius:4px; font-size:11px;'>Editor</span>";

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
        <td>$rol_display</td>
        <td><span style='color:#33834b; background:#e6f4ea; padding:4px 8px; border-radius:4px; font-size:12px;'>Activo</span></td>
        <td style='text-align:right;'>$boton_accion</td>
    </tr>";
}

// 5. CARGAR Y MOSTRAR LA PLANTILLA
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