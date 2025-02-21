<?php
require_once "../classes/Usuario.php";
require_once "../classes/Biblioteca.php";

$biblioteca = new Biblioteca();
$usuario = new Usuario("Juan", "Cliente", $biblioteca);

echo "<h2>Área del Cliente</h2>";
echo "<p>Hola, {$usuario->getNombre()}.</p>";

$usuario->listarLibros();
?>

<form method="POST">
    <label>Ingrese el título del libro que desea solicitar:</label>
    <input type="text" name="titulo">
    <button type="submit">Solicitar Préstamo</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST["titulo"];
    $usuario->prestarLibro($titulo);
}
?>
