<?php
echo "<h1>Bienvenido a la Biblioteca</h1>";
?>

<div class="login-form">
    <h2>Ingrese sus datos</h2>
    <form method="POST" action="index.php">
        <input type="text" name="nombre" placeholder="Ingrese su nombre" required>
        <select name="tipo" required>
            <option value="Cliente">Cliente</option>
            <option value="Administrador">Administrador</option>
        </select>
        <button type="submit">Ingresar</button>
    </form>
</div>
