<?php //Modelo/SanitizarEntrada

// Clase con métodos estáticos para limpiar y validar datos de entrada
class SanitizarEntrada {
    
    // Limpia un string: elimina espacios extras y caracteres no permitidos

    public static function limpiarString($valor) {
        return trim(filter_var($valor, FILTER_SANITIZE_STRING));
    }

    // Limpia un valor para que sea un entero válido, eliminando caracteres no numéricos
    public static function limpiarInt($valor) {
        return filter_var($valor, FILTER_SANITIZE_NUMBER_INT);
    }

    // Limpia un valor para que sea un número flotante válido, permitiendo decimales
    public static function limpiarFloat($valor) {
        return filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    // Valida que un texto solo contenga letras (mayúsculas, minúsculas, acentos y espacios)
    public static function validarDescripcion($texto) {
        return preg_match('/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/u', $texto);
    }    

    // Valida que el precio tenga formato numérico válido con hasta 2 decimales (coma o punto)
    public static function validarPrecio($precio) {
        return preg_match('/^\d+([.,]\d{1,2})?$/', $precio);
    }
    
    // Valida que la cantidad sea un número entero positivo (solo dígitos)
    public static function validarCantidad($cantidad) {
        return preg_match('/^\d+$/', $cantidad);
    }
}

?>