<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['nombre'])) {
    $_SESSION['nombre'] = htmlspecialchars(trim($_POST['nombre']));
    $_SESSION['tipo'] = $_POST['tipo'];
    $_SESSION['mensaje'] = "Bienvenido/a, " . $_SESSION['nombre'];
    header("Location: index.php?page=" . strtolower($_POST['tipo']));
    exit();
}

if (!isset($_SESSION['nombre']) && !isset($_POST['nombre']) && isset($_GET['page'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>BibliotechFSJ</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <?php
    if (isset($_SESSION['mensaje'])) {
        echo "<div class='mensaje'>{$_SESSION['mensaje']}</div>";
        unset($_SESSION['mensaje']);
    }
    
    $page = isset($_GET['page']) ? $_GET['page'] : 'menu';
    
    switch($page) {
        case 'cliente':
            require_once "../views/cliente.php";
            break;
        case 'administrador':
            require_once "../views/administrador.php";
            break;
        default:
            require_once "../views/menu.php";
    }
    ?>
    <?php if(isset($_SESSION['nombre'])): ?>
        <div class="logout">
            <form action="index.php" method="POST">
                <input type="hidden" name="logout" value="1">
                <button type="submit">Cerrar Sesión</button>
            </form>
        </div>
    <?php endif; ?>
</body>
</html>
<?php
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
