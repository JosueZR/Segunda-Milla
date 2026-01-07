<?php
function tiene_permiso($seccion_requerida) {
    // Si no ha iniciado sesión, fuera
    if (!isset($_SESSION['admin_id'])) return false;

    // 1. El Super Admin puede hacer TODO
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'super_admin') {
        return true;
    }

    // 2. El Editor solo si la sección está en su lista
    if (isset($_SESSION['permisos'])) {
        $permisos_array = explode(',', $_SESSION['permisos']);
        if (in_array($seccion_requerida, $permisos_array)) {
            return true;
        }
    }

    return false;
}
?>