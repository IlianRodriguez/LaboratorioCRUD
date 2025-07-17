//script.js

// Obtiene referencias a elementos HTML importantes
const registrar = document.getElementById('registrar'); // botón para guardar/modificar
const frm = document.getElementById('frm');  // formulario con datos del producto
const resultado = document.getElementById("resultado"); // contenedor donde se muestra la tabla de productos

// Evento que se ejecuta al hacer clic en el botón Registrar
registrar.addEventListener("click", () => {

    // Captura y limpia valores de los inputs del formulario
    const codigo = document.getElementById("codigo").value.trim();
    const producto = document.getElementById("producto").value.trim();
    const precio = document.getElementById("precio").value.trim();
    const cantidad = document.getElementById("cantidad").value.trim();

    // Envia los datos del formulario a registrar.php usando fetch y método POST
    fetch("registrar.php", {
        method: "POST",
        body: new FormData(frm)// envía todos los datos del formulario
    }).then(response => response.json())// espera respuesta JSON
    .then(response => { // Si la respuesta indica éxito
        if (response.success) { 
            switch (response.accion) {
                case "Guardar":
                case "Modificar":
                    // Define título del mensaje según la acción
                    const titulo = response.accion === "Guardar" ? "Registrado" : "Actualizado";

                    console.log(// Muestra en consola los datos guardados o actualizados
                        response.accion === "Guardar"
                            ? "Producto guardado:"
                            : "Producto actualizado",
                        response.data
                    );

                    Swal.fire({// Muestra mensaje emergente de éxito con SweetAlert2
                        icon: "success",
                        title: titulo,
                        showConfirmButton: false,
                        timer: 1500
                    });

                    frm.reset();// Limpia formulario y restablece estado a "Registrar"
                    document.getElementById("registrar").value = "Registrar";
                    document.getElementById("Accion").value = "Guardar";
                    listarProductos();// Actualiza la lista de productos en pantalla
                    break;

                default:// Si la acción no es reconocida, muestra advertencia
                    Swal.fire({
                        icon: "warning", 
                        title: "Acción realizada con fallas",
                        text: response.message
                    });
            }

        } else {// Si la respuesta indica errores
            if (response.errores) {// Construye un texto con todos los mensajes de error
                let mensaje = "";
                for (const campo in response.errores) {
                    mensaje += `• ${response.errores[campo]}\n`;
                }

                Swal.fire({// Muestra alerta con los errores del formulario
                    icon: "error",
                    title: "Errores en el formulario",
                    text: mensaje
                });
            } else {// Si no hay errores detallados, muestra mensaje genérico de error
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: response.message
                });
            }

            console.warn("Errores:", response.errores || response.message);
        }
    });
});

// Función para listar productos mostrando la tabla en el contenedor 'resultado'
function listarProductos(busqueda = "") {
    const formData = new FormData();
    formData.append("Accion", "Listar");  // Indica la acción al servidor
    formData.append("busqueda", busqueda); // Texto de búsqueda (vacío por defecto)
 
    // Solicita los productos a registrar.php vía POST
    fetch("registrar.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json()) 
    .then(response => {
        resultado.innerHTML = response;// Inserta la tabla HTML recibida dentro del contenedor resultado
    });
}

// Función para eliminar un producto dado su id
function EliminarProducto(id) {
    Swal.fire({// Muestra confirmación con SweetAlert2
        title: '¿Estás seguro de eliminar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {// Si confirma eliminación
            const formData = new FormData();
            formData.append("Accion", "Eliminar");
            formData.append("id", id);

            // Envía petición para eliminar producto
            fetch("registrar.php", {
                method: "POST",
                body: formData
            }).then(response => response.json())
              .then(response => {
                  if (response.success) {
                      // Refresca la lista y muestra mensaje de éxito
                      listarProductos();
                      Swal.fire({
                          icon: 'success',
                          title: 'Eliminado',
                          showConfirmButton: false,
                          timer: 1500
                      });
                  } else {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: response.message
                      });
                  }
              });
        }
    });
}

// Función para cargar datos de un producto en el formulario para editarlo
function EditarProducto(producto) {
    document.getElementById("idp").value = producto.id;
    document.getElementById("codigo").value = producto.codigo;
    document.getElementById("producto").value = producto.producto;
    document.getElementById("precio").value = producto.precio;
    document.getElementById("cantidad").value = producto.cantidad;
    document.getElementById("Accion").value = "Modificar";

    document.getElementById("registrar").value = "Actualizar";
}

// Evento para buscar productos mientras se escribe en el input 'buscar'
buscar.addEventListener("keyup", () => {
    const valor = buscar.value.trim();

    const formData = new FormData();
    if (valor === "") {
        formData.append("Accion", "Listar");// Si está vacío, lista todos
    } else {
        formData.append("Accion", "Buscar");// Si hay texto, busca
        formData.append("busqueda", valor);
    }

    // Solicita resultados al servidor y actualiza la tabla
    fetch("registrar.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(response => {
        resultado.innerHTML = response;
    });
});

// Inicializa mostrando todos los productos al cargar la página
listarProductos()