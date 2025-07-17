<?php //registrar.php

// Incluye la conexión a la base de datos y las clases necesarias
include("Modelo/conexion.php");
include("Modelo/Productos.php");
include("Modelo/SanitizarEntrada.php");

$pdo = new DB();  // Crea instancia de la conexión

// Sanitiza los datos recibidos por POST 
$datosSanitizados = [
    'codigo'   => SanitizarEntrada::limpiarString($_POST['codigo'] ?? ''),
    'producto' => SanitizarEntrada::limpiarString($_POST['producto'] ?? ''),
    'precio'   => SanitizarEntrada::limpiarFloat($_POST['precio'] ?? ''),
    'cantidad' => SanitizarEntrada::limpiarInt($_POST['cantidad'] ?? ''),
    'idp'      => isset($_POST['idp']) ? SanitizarEntrada::limpiarInt($_POST['idp']) : null,
    'Accion'   => $_POST['Accion'] ?? ''
];

// Crea instancia de Producto y asigna los datos recibidos directamente desde $_POST
$myProducto = new Producto($pdo);
$myProducto->RecibirDatos($_POST);

// Acción que se debe realizar (Guardar, Modificar,listar, eliminar y buscar)
$Accion = $_POST['Accion'] ?? '';

// Controla la lógica según la acción enviada
switch ($Accion) {

    case "Guardar":        
        $myProducto->setRequiredFields(['codigo', 'producto', 'precio', 'cantidad']);// Define campos obligatorios y valida
        $myProducto->validate();//valdia los datos

        if (!$myProducto->Errores) {
            $myProducto->RegistrarDatos();// Ajusta datos (tipos, trim)
            $myProducto->guardarProducto();// Inserta nuevo producto en BD
            $mensaje = "Producto Creado";

            $response = [//array asociativo
                "success" => true,
                "message" => $mensaje, // Mensaje para informar al usuario (ej. "Producto Creado")
                "accion"  => "Guardar" // Indica la acción que se realizó, útil para el frontend
            ];
        } else {
            $mensaje = "Producto no creado";

            $response = [//array asociativo
                "success" => false,
                "message" => $mensaje,
                "accion"  => "Guardar",
                "errores" => $myProducto->Errores // Envía el arreglo de errores para mostrar detalles
            ];
        }

        // Devuelve respuesta JSON y termina la ejecución
        header("Content-Type: application/json");
        echo json_encode($response);//convierte el array en texto formato json
        exit;
        break;

    case "Modificar":
        $myProducto->setRequiredFields(['codigo', 'producto', 'precio', 'cantidad']);// Define campos obligatorios y valida
        $myProducto->validate();//valdia los datos

        if (!$myProducto->Errores) {
            $myProducto->RegistrarDatos();// Ajusta datos (tipos, trim)
            $myProducto->actualizarProducto();// actualiza los datos del producto en BD

            $mensaje = "Producto Actualizado";

            $response = [//array asociativo
                "success" => true,
                "message" => $mensaje,
                "accion"  => "Modificar"
            ];
        } else {
            $mensaje = "El producto no fue actualizado";

            $response = [//array asociativo
                "success" => false,
                "message" => $mensaje,
                "accion"  => "Modificar",
                "errores" => $myProducto->Errores // Envía el arreglo de errores para mostrar detalles
            ];
        }

        // Devuelve respuesta JSON y termina la ejecución
        header("Content-Type: application/json");
        echo json_encode($response);//convierte el array en texto formato json
        exit;
        break;

    case "Listar":
        $busqueda  = $_POST['busqueda'] ?? '';// Busca productos según el texto de búsqueda recibido
        $productos = $myProducto->buscarProductos($busqueda);//busca producto por id
        $html      = "";

        // Construye filas HTML para cada producto encontrado
        foreach ($productos as $item) {
            $html .= "<tr>";
            $html .= "<td>{$item['id']}</td>";
            $html .= "<td>{$item['producto']}</td>";
            $html .= "<td>{$item['precio']}</td>";
            $html .= "<td>{$item['cantidad']}</td>";
            $html .= "<td>
                        <button class='btn btn-sm btn-success' onclick='EditarProducto(" . json_encode($item) . ")'>Editar</button>
                        <button class='btn btn-sm btn-danger' onclick='EliminarProducto({$item['id']})'>Eliminar</button>
                      </td>";
            $html .= "</tr>";
        }

        // Devuelve el HTML como JSON para ser usado en la vista
        echo json_encode($html);//convierte el array en texto formato json
        exit;
        break;

    case "Eliminar":
        $id = $_POST['id'] ?? null;// Obtiene el id a eliminar y valida
        if ($id !== null && is_numeric($id)) {
            $exito = $myProducto->eliminarProducto($id);//elimina producto por su id

            $response = [
                "success" => $exito,
                "accion"  => "Eliminar",
                "message" => $exito ? "Producto eliminado" : "Error al eliminar producto"
            ];
        } else {
            $response = [
                "success" => false,
                "accion"  => "Eliminar",
                "message" => "ID inválido" 
            ];
        }
        // Envía respuesta JSON 
        header("Content-Type: application/json");
        echo json_encode($response);//convierte el array en texto formato json
        exit;
        break;

    case "Buscar":
        $busqueda = $_POST['busqueda'] ?? '';
        $productos = $myProducto->buscarProductosMultiplesCampos($busqueda);// Busca productos usando múltiples campos
        $html = "";

        // Genera HTML para cada resultado encontrado
        foreach ($productos as $item) {
            $html .= "<tr>";
            $html .= "<td>{$item['id']}</td>";
            $html .= "<td>{$item['producto']}</td>";
            $html .= "<td>{$item['precio']}</td>";
            $html .= "<td>{$item['cantidad']}</td>";
            $html .= "<td>
                        <button class='btn btn-sm btn-success' onclick='EditarProducto(" . json_encode($item) . ")'>Editar</button>
                        <button class='btn btn-sm btn-danger' onclick='EliminarProducto({$item['id']})'>Eliminar</button>
                      </td>";
            $html .= "</tr>";
        }

        // Devuelve el HTML como JSON
        echo json_encode($html);//convierte el array en texto formato json
        exit;
        break;

    default:
        $response = [
            "success" => false,
            "message" => "El Producto no fue actualizado",
            "errors"  => ["con errores"],
            "accion"  => "Modificar"
        ];

        header("Content-Type: application/json");
        echo json_encode($response);//convierte el array en texto formato json
        exit;
}
?>