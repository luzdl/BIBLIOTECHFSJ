<?php
require_once "../classes/Usuario.php";
require_once "../classes/Biblioteca.php";

$biblioteca = new Biblioteca();
$usuario = new Usuario($_SESSION['nombre'], $_SESSION['tipo'], $biblioteca);

echo "<h2>Administrador</h2>";
echo "<p>Bienvenido, {$usuario->getNombre()}.</p>";

// Procesar acciones
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['accion'])) {
        switch($_POST['accion']) {
            case 'eliminar':
                $usuario->eliminarLibro($_POST['titulo']);
                break;
            case 'modificar':
                $usuario->modificarLibro(
                    $_POST['titulo_original'],
                    $_POST['titulo_nuevo'],
                    $_POST['autor'],
                    $_POST['categoria']
                );
                break;
            case 'devolver':
                $resultado = $usuario->devolverLibro($_POST['titulo']);
                echo "<div class='mensaje " . ($resultado['success'] ? 'mensaje-success' : 'mensaje-error') . "'>";
                echo htmlspecialchars($resultado['message']);
                echo "</div>";
                break;
        }
    }
}

// Mover el procesamiento de agregar libro antes de mostrar la tabla
if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['accion'])) {
    $titulo = trim($_POST["titulo"] ?? '');
    $autor = trim($_POST["autor"] ?? '');
    $categoria = trim($_POST["categoria"] ?? '');
    
    if (!empty($titulo) && !empty($autor) && !empty($categoria)) {
        if ($usuario->agregarLibro($titulo, $autor, $categoria)) {
            echo "<div class='mensaje mensaje-success'>";
            echo "El libro '$titulo' ha sido agregado exitosamente.";
            echo "</div>";
            // Actualizar la lista de libros inmediatamente
            $libros = $biblioteca->listarLibros();
        } else {
            echo "<div class='mensaje mensaje-error'>";
            echo "No se pudo agregar el libro. Verifique sus permisos.";
            echo "</div>";
        }
    } else {
        echo "<div class='mensaje mensaje-error'>";
        echo "Por favor, complete todos los campos.";
        echo "</div>";
    }
}

// Mostrar búsqueda
?>
<div class="search-section">
    <h3>Buscar Libros</h3>
    <form method="GET">
        <input type="hidden" name="page" value="administrador">
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
// Procesar búsqueda
if (isset($_GET['busqueda']) && !empty($_GET['busqueda'])) {
    $libros = $biblioteca->buscarLibro($_GET['criterio'], $_GET['busqueda']);
} else {
    $libros = $biblioteca->listarLibros();
}

// Mostrar libros con opciones de administración
echo "<h3>Libros en la Biblioteca</h3>";
echo "<div class='table-container'>";
echo "<table><tr><th>Título</th><th>Autor</th><th>Categoría</th><th>Estado</th><th>Acciones</th></tr>";
foreach ($libros as $libro) {
    echo "<tr>";
    echo "<td>" . $libro->getTitulo() . "</td>";
    echo "<td>" . $libro->getAutor() . "</td>";
    echo "<td>" . $libro->getCategoria() . "</td>";
    echo "<td>" . ($libro->isDisponible() ? "Disponible" : "Prestado") . "</td>";
    echo "<td class='actions'>";
    echo "<form method='POST' style='display:inline;'>";
    echo "<input type='hidden' name='titulo' value='" . $libro->getTitulo() . "'>";
    echo "<input type='hidden' name='accion' value='eliminar'>";
    echo "<button type='submit' class='btn-danger'>Eliminar</button>";
    echo "</form>";
    echo " <button onclick='mostrarFormularioModificar(\"" . $libro->getTitulo() . "\", \"" . $libro->getAutor() . "\", \"" . $libro->getCategoria() . "\")' class='btn-warning'>Modificar</button>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";

// Mostrar préstamos actuales
echo "<h3>Préstamos Actuales</h3>";
$prestamos = $biblioteca->listarPrestamos();
if (!empty($prestamos)) {
    echo "<table border='1'><tr><th>Título</th><th>Usuario</th><th>Fecha</th><th>Acción</th></tr>";
    foreach ($prestamos as $prestamo) {
        echo "<tr>";
        echo "<td>{$prestamo['titulo']}</td>";
        echo "<td>{$prestamo['usuario']}</td>";
        echo "<td>{$prestamo['fecha']}</td>";
        echo "<td>";
        echo "<form method='POST' style='display:inline;'>";
        echo "<input type='hidden' name='titulo' value='" . $prestamo['titulo'] . "'>";
        echo "<input type='hidden' name='accion' value='devolver'>";
        echo "<button type='submit' class='btn-success'>Marcar como Devuelto</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No hay préstamos activos</p>";
}
?>

<div id="modalModificar" class="modal">
    <div class="modal-content">
        <h3>Modificar Libro</h3>
        <form method="POST">
            <input type="hidden" name="accion" value="modificar">
            <input type="hidden" name="titulo_original" id="titulo_original">
            <label>Nuevo Título:</label>
            <input type="text" name="titulo_nuevo" id="titulo_nuevo"><br>
            <label>Nuevo Autor:</label>
            <input type="text" name="autor" id="autor"><br>
            <label>Nueva Categoría:</label>
            <input type="text" name="categoria" id="categoria"><br>
            <button type="submit" class="btn-primary">Guardar Cambios</button>
            <button type="button" onclick="cerrarModal()" class="btn-secondary">Cancelar</button>
        </form>
    </div>
</div>

<script>
function mostrarFormularioModificar(titulo, autor, categoria) {
    document.getElementById('modalModificar').style.display = 'block';
    document.getElementById('titulo_original').value = titulo;
    document.getElementById('titulo_nuevo').value = titulo;
    document.getElementById('autor').value = autor;
    document.getElementById('categoria').value = categoria;
}

function cerrarModal() {
    document.getElementById('modalModificar').style.display = 'none';
}
</script>

<h3>Agregar nuevo libro</h3>
<form method="POST">
    <label>Título:</label> <input type="text" name="titulo"><br>
    <label>Autor:</label> <input type="text" name="autor"><br>
    <label>Categoría:</label> <input type="text" name="categoria"><br>
    <button type="submit">Agregar</button>
</form>

<p><a href="index.php">Volver al Menú Principal</a></p>

<?php
?>
