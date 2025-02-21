<?php
require_once "Libro.php";

class Biblioteca {
    private array $libros = [];

    public function __construct() {
        // Se inicializa con algunos libros de prueba
        $this->libros[] = new Libro("1984", "George Orwell", "Distopía");
        $this->libros[] = new Libro("El principito", "Antoine de Saint-Exupéry", "Ficción");
        $this->libros[] = new Libro("Cien años de soledad", "Gabriel García Márquez", "Realismo Mágico");
    }

    public function agregarLibro(string $titulo, string $autor, string $categoria) {
        $this->libros[] = new Libro($titulo, $autor, $categoria);
        echo "Libro '{$titulo}' agregado a la biblioteca.<br>";
    }

    public function listarLibros() {
        echo "<h3>Libros en la biblioteca:</h3>";
        foreach ($this->libros as $libro) {
            $estado = $libro->estaDisponible() ? "Disponible" : "Prestado";
            echo "- {$libro->getTitulo()} ({$libro->getAutor()}) - {$estado}<br>";
        }
    }

    public function prestarLibro(string $titulo) {
        foreach ($this->libros as $libro) {
            if ($libro->getTitulo() === $titulo) {
                $libro->prestar();
                return;
            }
        }
        echo "El libro '{$titulo}' no existe en la biblioteca.<br>";
    }
}
?>
