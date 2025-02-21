<?php
require_once "Libro.php";

class Biblioteca {
    private $libros = [];
    private $prestamos = [];
    private $archivoBiblioteca = "../data/libros.json";
    private $archivoPrestamos = "../data/prestamos.json";

    public function __construct() {
        $this->cargarDatos();
    }

    private function cargarDatos() {
        if (file_exists($this->archivoBiblioteca)) {
            $datos = json_decode(file_get_contents($this->archivoBiblioteca), true);
            foreach ($datos as $libro) {
                $nuevoLibro = new Libro($libro['titulo'], $libro['autor'], $libro['categoria']);
                if (!$libro['disponible']) {
                    $nuevoLibro->prestar();
                }
                $this->libros[] = $nuevoLibro;
            }
        }
        if (file_exists($this->archivoPrestamos)) {
            $this->prestamos = json_decode(file_get_contents($this->archivoPrestamos), true);
        }
    }

    private function guardarDatos() {
        $librosArray = array_map(function($libro) {
            return $libro->toArray();
        }, $this->libros);
        
        if (!is_dir("../data")) {
            mkdir("../data", 0777, true);
        }
        
        file_put_contents($this->archivoBiblioteca, json_encode($librosArray));
        file_put_contents($this->archivoPrestamos, json_encode($this->prestamos));
    }

    public function agregarLibro($titulo, $autor, $categoria) {
        $libro = new Libro($titulo, $autor, $categoria);
        $this->libros[] = $libro;
        $this->guardarDatos();
        return true;
    }

    public function buscarLibro($criterio, $valor) {
        return array_filter($this->libros, function($libro) use ($criterio, $valor) {
            $metodo = "get" . ucfirst($criterio);
            return stripos($libro->$metodo(), $valor) !== false;
        });
    }

    public function prestarLibro($titulo, $usuario) {
        // Primero verificar si el libro existe
        $libroExiste = false;
        foreach ($this->libros as $libro) {
            if (strtolower(trim($libro->getTitulo())) === strtolower(trim($titulo))) {
                $libroExiste = true;
                if ($libro->isDisponible()) {
                    $libro->prestar();
                    $this->prestamos[] = [
                        'titulo' => $libro->getTitulo(), // Usar el título exacto del libro
                        'usuario' => $usuario,
                        'fecha' => date('Y-m-d')
                    ];
                    $this->guardarDatos();
                    return [
                        "success" => true, 
                        "message" => "¡El libro '{$libro->getTitulo()}' ha sido prestado exitosamente!"
                    ];
                } else {
                    $prestamo = $this->buscarPrestamo($libro->getTitulo());
                    return [
                        "success" => false, 
                        "message" => "Este libro ya está prestado a {$prestamo['usuario']} desde {$prestamo['fecha']}"
                    ];
                }
            }
        }
        
        if (!$libroExiste) {
            return [
                "success" => false, 
                "message" => "El libro '$titulo' no existe en nuestra biblioteca."
            ];
        }
    }

    private function buscarPrestamo($titulo) {
        foreach ($this->prestamos as $prestamo) {
            if (strtolower(trim($prestamo['titulo'])) === strtolower(trim($titulo))) {
                return $prestamo;
            }
        }
        return null;
    }

    public function eliminarLibro($titulo) {
        $this->libros = array_filter($this->libros, function($libro) use ($titulo) {
            return $libro->getTitulo() !== $titulo;
        });
        $this->guardarDatos();
        return true;
    }

    public function modificarLibro($tituloOriginal, $tituloNuevo, $autor, $categoria) {
        foreach ($this->libros as $key => $libro) {
            if ($libro->getTitulo() === $tituloOriginal) {
                $this->libros[$key] = new Libro($tituloNuevo, $autor, $categoria);
                $this->guardarDatos();
                return true;
            }
        }
        return false;
    }

    public function listarLibros() {
        if (empty($this->libros)) {
            return [];
        }
        return $this->libros;
    }

    public function listarPrestamos() {
        return $this->prestamos;
    }

    public function devolverLibro($titulo) {
        foreach ($this->libros as $libro) {
            if ($libro->getTitulo() === $titulo && !$libro->isDisponible()) {
                $libro->devolver();
                // Eliminar el préstamo del registro
                $this->prestamos = array_filter($this->prestamos, function($prestamo) use ($titulo) {
                    return $prestamo['titulo'] !== $titulo;
                });
                $this->guardarDatos();
                return ["success" => true, "message" => "El libro '$titulo' ha sido devuelto exitosamente."];
            }
        }
        return ["success" => false, "message" => "No se pudo procesar la devolución del libro."];
    }
}
?>
