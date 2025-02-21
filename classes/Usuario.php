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
        $this->biblioteca->listarLibros();
    }

    public function prestarLibro(string $titulo) {
        if ($this->tipo === "Cliente") {
            $this->biblioteca->prestarLibro($titulo);
        } else {
            echo "Los administradores no pueden pedir prestados libros.<br>";
        }
    }

    public function agregarLibro(string $titulo, string $autor, string $categoria) {
        if ($this->tipo === "Administrador") {
            $this->biblioteca->agregarLibro($titulo, $autor, $categoria);
        } else {
            echo "Los clientes no pueden agregar libros.<br>";
        }
    }
}
?>
