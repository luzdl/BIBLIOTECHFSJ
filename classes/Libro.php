<?php
class Libro {
    private $titulo;
    private $autor;
    private $categoria;
    private $disponible;

    public function __construct($titulo, $autor, $categoria) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->disponible = true;
    }

    public function getTitulo() { return $this->titulo; }
    public function getAutor() { return $this->autor; }
    public function getCategoria() { return $this->categoria; }
    public function isDisponible() { return $this->disponible; }
    
    public function prestar() {
        if ($this->disponible) {
            $this->disponible = false;
            return true;
        }
        return false;
    }

    public function devolver() {
        $this->disponible = true;
    }

    public function toArray() {
        return [
            'titulo' => $this->titulo,
            'autor' => $this->autor,
            'categoria' => $this->categoria,
            'disponible' => $this->disponible
        ];
    }
}
?>
