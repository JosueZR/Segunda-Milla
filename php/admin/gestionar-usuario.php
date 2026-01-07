<?php
session_start();

// 1. Verificar Sesión
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit();
}

// 2. Conexión
include("../php/includes/conexion.php");

// Variables para el HTML final
$mensaje_alerta = "";
$filas_tabla_html = "";

// --- LÓGICA: CREAR USUARIO ---
if (isset($_POST['crear_usuario'])) {
    $nuevo_user = mysqli_real_escape_string($conn, $_POST['nuevo_user']);
    $nuevo_pass = $_POST['nuevo_pass'];
    $pass_hash = password_hash($nuevo_pass, PASSWORD_DEFAULT);
    
    $sql = "INSERT INTO usuarios (usuario, password) VALUES ('$nuevo_user', '$pass_hash')";
    if (mysqli_query($conn, $sql)) {
        $mensaje_alerta = "<script>alert('✅ Usuario creado correctamente');</script>";
    } else {
        $mensaje_alerta = "<script>alert('❌ Error: " . mysqli_error($conn) . "');</script>";
    }
}

// --- LÓGICA: ELIMINAR USUARIO ---
if (isset($_GET['eliminar'])) {
    $id_borrar = intval($_GET['eliminar']);
    if ($id_borrar != $_SESSION['admin_id']) {
        mysqli_query($conn, "DELETE FROM usuarios WHERE id = $id_borrar");
        header("Location:gestionar_usuarios.php"); // Recargar limpio
        exit();
    } else {
        $mensaje_alerta = "<script>alert('⚠️ No puedes eliminarte a ti mismo.');</script>";
    }
}

// --- LÓGICA: GENERAR FILAS DE LA TABLA ---
$res = mysqli_query($conn, "SELECT * FROM usuarios ORDER BY id ASC");

while ($row = mysqli_fetch_assoc($res)) {
    $id = $row['id'];
    $user = $row['usuario'];
    
    // Determinar si mostrar botón eliminar
    $boton_accion = "";
    if ($id == $_SESSION['admin_id']) {
        $boton_accion = "<span style='color:#aaa; font-size:0.85rem;'>(Tú)</span>";
    } else {
        $boton_accion = "<a href='gestionar_usuarios.php?eliminar=$id' class='btn-eliminar' onclick=\"return confirm('¿Eliminar a $user?')\">🗑️ Eliminar</a>";
    }

    // Construir el HTML de esta fila
    $filas_tabla_html .= "
    <tr>
        <td>#$id</td>
        <td><strong>$user</strong></td>
        <td><span style='color:#33834b; background:#e6f4ea; padding:4px 8px; border-radius:4px; font-size:12px;'>Activo</span></td>
        <td style='text-align:right;'>$boton_accion</td>
    </tr>";
}

// 3. CARGAR Y MOSTRAR LA PLANTILLA HTML
// Leemos el archivo HTML puro
$html_template = file_get_contents('gestionar-usuario.html');

// Reemplazamos los marcadores con los datos dinámicos
$html_final = str_replace('{{ALERTA}}', $mensaje_alerta, $html_template);
$html_final = str_replace('{{LISTA_USUARIOS}}', $filas_tabla_html, $html_final);

// Imprimimos el resultado
echo $html_final;
?>