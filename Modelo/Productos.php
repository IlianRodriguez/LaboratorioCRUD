<?php //Modelo/Productos.php

// Clase que maneja operaciones sobre la tabla "productos"
class Producto {

    private $codigo;
    private $producto;
    private $precio;
    private $cantidad;
    private $pdo; // Instancia para interactuar con la base de datos 
    private $tabla;// Nombre de la tabla en la base de datos
    public $Errores = [];// Almacena errores de validación
    private $requiredFields = [];// Campos obligatorios para validar

    // Constructor recibe una instancia de la conexión PDO o clase DB
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->tabla = "productos";
    }

    // Inserta un nuevo producto en la base de datos usando el método insertSeguro
    public function guardarProducto(){
        $data = array(
            "codigo" => $this->codigo,
            "producto" => $this->producto,
            "precio" => $this->precio,
            "cantidad" => $this->cantidad
        );

        $this->pdo->insertSeguro("productos", $data);
    }

    // Actualiza un producto existente según su id
    public function actualizarProducto() {
        $data = array(
            "codigo"   => $this->codigo,
            "producto" => $this->producto,
            "precio"   => $this->precio,
            "cantidad" => $this->cantidad
        );
    
        $condiciones = array("id" => $this->idp);
    
        $this->pdo->updateSeguro("productos", $data, $condiciones);
    }

    // Busca productos por nombre usando el método selectSeguro
    public function buscarProductos($busqueda = "") { 
        return $this->pdo->selectSeguro($this->tabla, ['id', 'codigo','producto', 'precio', 'cantidad'], 'producto', $busqueda);
    }      

    // Elimina un producto dado su id
    public function eliminarProducto($id) {
        return $this->pdo->deleteSeguro($this->tabla, ["id" => $id]);
    }

    // Busca productos usando varios campos para la búsqueda con OR
    public function buscarProductosMultiplesCampos($busqueda = "") {
        $campos = ['id', 'producto', 'precio']; 
        return $this->pdo->selectSeguroMultiple($this->tabla, ['id', 'codigo', 'producto', 'precio', 'cantidad'], $campos, $busqueda);
    }
    
    // Establece qué campos serán obligatorios para validar
    public function setRequiredFields(array $fields) {
        $this->requiredFields = $fields;
    }

    // Valida los campos obligatorios según reglas específicas
    //Almacena los erroes dentro del array Erroes
    public function validate() {
        $this->Errores = [];

        foreach ($this->requiredFields as $campo) {
            $valor = $this->$campo;

            // Verifica que el campo no esté vacío
            if (empty($valor)) {
                $this->Errores[$campo] = "El campo $campo es obligatorio.";
                continue;
            }

            switch ($campo) {
                case 'producto':
                    if (!SanitizarEntrada::validarDescripcion($valor)) {
                        $this->Errores[$campo] = "El producto solo debe contener letras y espacios.";
                    }
                    break;

                case 'precio':
                    if (!SanitizarEntrada::validarPrecio($valor)) {
                        $this->Errores[$campo] = "El precio debe tener hasta 2 decimales y usar punto o coma.";
                    }
                    break;

                case 'cantidad':
                    if (!SanitizarEntrada::validarCantidad($valor)) {
                        $this->Errores[$campo] = "La cantidad debe ser un número entero positivo.";
                    }
                    break;
            }
        }
    }

    // Recibe datos en forma de arreglo y los asigna a las propiedades
    public function RecibirDatos($data) {
        $this->codigo   = $data['codigo'] ?? '';
        $this->producto = $data['producto'] ?? '';
        $this->precio   = $data['precio'] ?? '';
        $this->cantidad = $data['cantidad'] ?? '';
        $this->idp      = $data['idp'] ?? null;
    }

    // Normaliza los datos recibidos a tipos adecuados para almacenar
    public function RegistrarDatos() {
        $this->producto = trim($this->producto);// Elimina espacios al inicio y fin
        $this->precio   = floatval(str_replace(',', '.', $this->precio));// Convierte precio a float, soportando coma
        $this->cantidad = intval($this->cantidad);// Convierte cantidad a entero
    }
}
?>