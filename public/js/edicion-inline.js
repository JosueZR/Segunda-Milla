document.addEventListener("DOMContentLoaded", function() {
    // Solo iniciar si hay elementos editables en la página
    if (document.querySelectorAll('.texto-editable').length > 0) {
        
        tinymce.init({
            selector: '.texto-editable', // Clase que usaremos en el PHP
            inline: true,               // MODO EDICIÓN EN LÍNEA
            menubar: false,             // Ocultar barra de menú superior
            plugins: 'save image link lists', // Plugins esenciales
            toolbar: 'save | undo redo | bold italic | alignleft aligncenter alignright | bullist numlist',
            
            // Configuración del botón GUARDAR
            save_enablewhendirty: true,
            save_onsavecallback: function () {
                var editor = this;
                var contenido = editor.getContent();
                var nodo = editor.getElement(); // El elemento HTML real
                
                // Obtener datos del elemento (data-seccion, data-clave)
                var seccion = nodo.getAttribute('data-seccion');
                var clave = nodo.getAttribute('data-clave');

                // Enviar a PHP mediante AJAX
                fetch('../../php/admin/guardar_inline.php', { // Ajusta la ruta si estás en index.php
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ seccion: seccion, clave: clave, contenido: contenido })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Feedback visual (Borde verde temporal)
                        nodo.style.outline = "2px solid #2ecc71";
                        setTimeout(() => nodo.style.outline = "none", 2000);
                        editor.notificationManager.open({text: '¡Guardado correctamente!', type: 'success', timeout: 2000});
                    } else {
                        alert('Error al guardar: ' + data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        });
    }
});