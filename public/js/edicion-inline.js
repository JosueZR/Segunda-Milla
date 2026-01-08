document.addEventListener("DOMContentLoaded", function() {
    // Solo iniciar si hay elementos editables en la página
    if (document.querySelectorAll('.texto-editable').length > 0) {
        
        tinymce.init({
            selector: '.texto-editable', 
            inline: true,              
            menubar: false,            
            plugins: 'save image link lists', 
            toolbar: 'save | undo redo | bold italic | alignleft aligncenter alignright | bullist numlist',
            
            save_enablewhendirty: true,
            save_onsavecallback: function () {
                var editor = this;
                var contenido = editor.getContent();
                var nodo = editor.getElement(); 
                
                var seccion = nodo.getAttribute('data-seccion');
                var clave = nodo.getAttribute('data-clave');

                // --- CORRECCIÓN AQUÍ ---
                // BORRA la ruta vieja ('../../php/...') y pon: rutaBackend
                fetch(rutaBackend, { 
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({ seccion: seccion, clave: clave, contenido: contenido })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        nodo.style.outline = "2px solid #2ecc71";
                        setTimeout(() => nodo.style.outline = "none", 2000);
                        editor.notificationManager.open({text: '¡Guardado correctamente!', type: 'success', timeout: 2000});
                    } else {
                        alert('Error al guardar: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error de conexión. Revisa la consola (F12).');
                });
            }
        });
    }
});