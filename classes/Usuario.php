<?php
require_once "Biblioteca.php";

class Usuario {
    private string $nombre;
    private string $tipo;
    private Biblioteca $biblioteca;

    public function __construct(string $nombre, string $tipo, Biblioteca $biblioteca) {
        $this->nombre = $nombre;
        $this->tipo = $tipo;
        $this->biblioteca = $biblioteca;
    }

    public function getNombre(): string { return $this->nombre; }
    public function getTipo(): string { return $this->tipo; }

    public function listarLibros() {
        $libros = $this->biblioteca->listarLibros();
        if (empty($libros)) {
            echo "<p>No hay libros disponibles</p>";
        }
        return $libros;
    }

    public function prestarLibro(string $titulo) {
        $resultado = $this->biblioteca->prestarLibro($titulo, $this->nombre);
        // Ahora simplemente retornamos el resultado de la biblioteca
        return $resultado;
    }

    public function agregarLibro(string $titulo, string $autor, string $categoria) {
        if ($this->tipo === "Administrador") {
            return $this->biblioteca->agregarLibro($titulo, $autor, $categoria);
        }
        return false;
    }

    public function eliminarLibro($titulo) {
        if ($this->tipo === "Administrador") {
            return $this->biblioteca->eliminarLibro($titulo);
        }
        return false;
    }

    public function modificarLibro($tituloOriginal, $tituloNuevo, $autor, $categoria) {
        if ($this->tipo === "Administrador") {
            return $this->biblioteca->modificarLibro($tituloOriginal, $tituloNuevo, $autor, $categoria);
        }
        return false;
    }

    public function devolverLibro($titulo) {
        if ($this->tipo === "Administrador") {
            return $this->biblioteca->devolverLibro($titulo);
        }
        return ["success" => false, "message" => "Solo los administradores pueden procesar devoluciones."];
    }
}
?>
