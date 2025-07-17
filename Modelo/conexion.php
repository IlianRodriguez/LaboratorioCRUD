<?php //Modelo/ConexionBaseDatos.php


                                     // Clase encargada de gestionar la conexión a la base de datos
class DB {

	private $conexion;              // Almacena la conexión PDO
	private $debug = false;         // Bandera para mostrar mensajes de depuración

	public function __construct() {
		                           // Parámetros de conexión a la base de datos
		$sql_host = "localhost";   // Servidor de base de datos
		$sql_name = "productosdb"; // Nombre de la base de datos
		$sql_user = "root";	       // Usuario de la base de datos
		$sql_pass = "";            // Contraseña del usuario
		$charset = 'utf8mb4';      // Codificación de caracteres

		$dsn = "mysql:host=$sql_host;dbname=$sql_name;charset=utf8mb4"; // Data Source Name para la conexión PDO
		try {
			$this->conexion = new PDO($dsn, $sql_user, $sql_pass); //Crea nueva conexion
			$this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Modo errores y exepciones
			if ($this->debug) { //conecta y muestra mensaje de éxito
				echo "Conexión exitosa a la base de datos<br>";
			}
		} catch (PDOException $e) { //detiene la ejecución y muestra  mensaje de error
			echo "Error de conexión: " . $e->getMessage();
			exit;
		}
	}

    public function insertSeguro($tb_name, $data) {
        // Columnas y placeholders para consulta preparada
        $columns = implode(", ", array_keys($data));// convertir un arreglo en una cadena de texto
        $placeholders = ":" . implode(", :", array_keys($data));

        // Consulta preparada para evitar inyecciones
        $sql = "INSERT INTO $tb_name ($columns) VALUES ($placeholders)";

        try { 
            $stmt = $this->conexion->prepare($sql);

           // Vincula valores del array $data con los placeholder
            foreach ($data as $key => $value) { 
                $stmt->bindValue(":$key", $value);
            }

            $stmt->execute();
            return true;
        } catch (PDOException $e) {// Error en inserción
            echo "Error en INSERT: " . $e->getMessage();
            return false;
        }
    }

	public function updateSeguro($tabla, $data, $condiciones) {
        $set = [];  // Prepara la parte SET de la consulta
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $setSQL = implode(", ", $set); // convertir un arreglo en una cadena de texto
        $where = [];
        foreach ($condiciones as $key => $value) {
            $where[] = "$key = :cond_$key"; // Se usa "cond_" para evitar conflicto con los nombres del SET
        }
        $whereSQL = implode(" AND ", $where); // Une condiciones con AND
        $sql = "UPDATE $tabla SET $setSQL WHERE $whereSQL"; // Arma la consulta final SQL

        try {
            $stmt = $this->conexion->prepare($sql);

            foreach ($data as $key => $value) { // Asocia cada valor del arreglo $data a su placeholder correspondiente
                $stmt->bindValue(":$key", $value);
            }

            foreach ($condiciones as $key => $value) { // Asocia cada condición con su placeholder único
                $stmt->bindValue(":cond_$key", $value);
            }
            // Ejecuta la consulta y devuelve true si todo va bien
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en UPDATE: " . $e->getMessage();// Muestra el error en caso de fallo
            return false;
        }

    }

    public function selectSeguro($tabla, $columnas = ['*'], $campo = "", $valor = "") {
        $cols = implode(", ", $columnas); // convertir un arreglo en una cadena de texto
        $sql = "SELECT $cols FROM $tabla";
    
        if (!empty($campo) && !empty($valor)) {
            $sql .= " WHERE $campo LIKE :valor ORDER BY id DESC";
        } else {
            $sql .= " ORDER BY id DESC";
        }
    
        try {
            $stmt = $this->conexion->prepare($sql);
            if (!empty($campo) && !empty($valor)) {// Si hay valor de búsqueda, se vincula con comodines
                $stmt->bindValue(":valor", "%$valor%");
            }
    
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);// Retorna todos los resultados como array asociativo
        } catch (PDOException $e) {
            echo "Error en BUSQUEDA: " . $e->getMessage();
            return false;
        }
    } 

    public function deleteSeguro($tabla, $condiciones) {
        $where = [];
        foreach ($condiciones as $key => $value) {
            $where[] = "$key = :cond_$key";
        }
        $whereSQL = implode(" AND ", $where); // convertir un arreglo en una cadena de texto
        $sql = "DELETE FROM $tabla WHERE $whereSQL";
    
        try {
            $stmt = $this->conexion->prepare($sql);
            foreach ($condiciones as $key => $value) {// Asocia los valores a los placeholders
                $stmt->bindValue(":cond_$key", $value);
            }
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error en DELETE: " . $e->getMessage();
            return false;
        }
    }

    public function selectSeguroMultiple($tabla, $columnas = ['*'], $camposBusqueda = [], $valor = "") {
        $cols = implode(", ", $columnas); // convertir un arreglo en una cadena de texto
        $sql = "SELECT $cols FROM $tabla";

        // Arma condiciones WHERE con varios campos usando OR
        if (!empty($camposBusqueda) && !empty($valor)) {
            $whereParts = [];
            foreach ($camposBusqueda as $campo) {
                $whereParts[] = "$campo LIKE :valor";
            }
            $sql .= " WHERE " . implode(" OR ", $whereParts);
        }
        // Orden descendente por ID
        $sql .= " ORDER BY id DESC";
    
        try {
            $stmt = $this->conexion->prepare($sql);
            if (!empty($camposBusqueda) && !empty($valor)) {// Vincula el valor si hay búsqueda
                $stmt->bindValue(":valor", "%$valor%");
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en BUSQUEDA múltiple: " . $e->getMessage();
            return false;
        }
    }
}