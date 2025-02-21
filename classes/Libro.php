<?php
class Libro {
    private string $titulo;
    private string $autor;
    private string $categoria;
    private bool $disponible;

    public function __construct(string $titulo, string $autor, string $categoria) {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->categoria = $categoria;
        $this->disponible = true;
    }

    public function getTitulo(): string { return $this->titulo; }
    public function getAutor(): string { return $this->autor; }
    public function getCategoria(): string { return $this->categoria; }
    public function estaDisponible(): bool { return $this->disponible; }

    public function prestar(): void {
        if ($this->disponible) {
            $this->disponible = false;
            echo "El libro '{$this->titulo}' ha sido prestado.<br>";
        } else {
            echo "El libro '{$this->titulo}' no está disponible.<br>";
        }
    }
}
?>
