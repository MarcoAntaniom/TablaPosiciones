<?php
require_once("conexion.php"); // Archivo de conexión PDO

if (isset($_GET['id'])) {
    $usuario_id = $_GET['id'];

    $sentencia = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    if ($sentencia->execute([$usuario_id])) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Usuario eliminado',
                text: 'El usuario ha sido eliminado con éxito.'
            }).then(() => {
                window.location.href = 'listado_usuarios.php';
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el usuario.'
            }).then(() => {
                window.location.href = 'listado_usuarios.php';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'ID de usuario no especificado.'
        }).then(() => {
            window.location.href = 'listado_usuarios.php';
        });
    </script>";
}
?>