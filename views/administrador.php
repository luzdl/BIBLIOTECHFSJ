<?php
require_once "../classes/Usuario.php";
require_once "../classes/Biblioteca.php";

$biblioteca = new Biblioteca();
$usuario = new Usuario("María", "Administrador", $biblioteca);

echo "<h2>Área del Administrador</h2>";
echo "<p>Bienvenido, {$usuario->getNombre()}.</p>";

$usuario->listarLibros();
?>

<h3>Agregar nuevo libro</h3>
<form method="POST">
    <label>Título:</label> <input type="text" name="titulo"><br>
    <label>Autor:</label> <input type="text" name="autor"><br>
    <label>Categoría:</label> <input type="text" name="categoria"><br>
    <button type="submit">Agregar</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $categoria = $_POST["categoria"];
    $usuario->agregarLibro($titulo, $autor, $categoria);
}
?>
