<?php
require_once "../classes/Usuario.php";
require_once "../classes/Biblioteca.php";

$biblioteca = new Biblioteca();
$usuario = new Usuario($_SESSION['nombre'], $_SESSION['tipo'], $biblioteca);

echo "<h2>Cliente</h2>";
echo "<p>Hola, {$usuario->getNombre()}.</p>";
?>

<div class="search-section">
    <h3>Buscar Libros</h3>
    <form method="GET">
        <input type="hidden" name="page" value="cliente">
        <select name="criterio">
            <option value="titulo">Título</option>
            <option value="autor">Autor</option>
            <option value="categoria">Categoría</option>
        </select>
        <input type="text" name="busqueda">
        <button type="submit">Buscar</button>
    </form>
</div>

<?php
if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
    $libros = $biblioteca->buscarLibro($_GET['criterio'], $_GET['busqueda']);
} else {
    $libros = $biblioteca->listarLibros();
}

// Mover esta sección antes de mostrar la tabla de libros
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["titulo"])) {
    $titulo = trim($_POST["titulo"]);
    if (!empty($titulo)) {
        $resultado = $usuario->prestarLibro($titulo);
        $tipoMensaje = $resultado['success'] ? 'mensaje-success' : 'mensaje-error';
        echo "<div class='mensaje {$tipoMensaje}'>";
        echo htmlspecialchars($resultado['message']);
        echo "</div>";
    } else {
        echo "<div class='mensaje mensaje-error'>Por favor, ingrese el título del libro.</div>";
    }
}

echo "<h3>Libros Disponibles</h3>";
echo "<div class='table-container'>";
echo "<table><tr><th>Título</th><th>Autor</th><th>Categoría</th><th>Estado</th></tr>";
foreach ($libros as $libro) {
    $estado = $libro->isDisponible() ? 
        "<span class='estado-disponible'>Disponible</span>" : 
        "<span class='estado-prestado'>Prestado</span>";
    
    echo "<tr>";
    echo "<td>" . htmlspecialchars($libro->getTitulo()) . "</td>";
    echo "<td>" . htmlspecialchars($libro->getAutor()) . "</td>";
    echo "<td>" . htmlspecialchars($libro->getCategoria()) . "</td>";
    echo "<td>" . $estado . "</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";
?>

<form method="POST">
    <label>Ingrese el título del libro que desea solicitar:</label>
    <input type="text" name="titulo">
    <button type="submit">Solicitar Préstamo</button>
</form>

<p><a href="index.php">Volver al Menú Principal</a></p>

<?php
// Eliminar el bloque duplicado al final del archivo
// if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     $titulo = $_POST["titulo"];
//     $usuario->prestarLibro($titulo);
// }
?>
