document.addEventListener("DOMContentLoaded", function() {
    
    // Seleccionamos tanto imágenes normales como de noticias
    const imagenes = document.querySelectorAll('.imagen-editable, .imagen-noticia-editable');

    imagenes.forEach(img => {
        img.addEventListener('click', function(e) {
            e.preventDefault();

            // Input invisible
            let input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';

            input.onchange = function(evento) {
                let file = evento.target.files[0];
                if (!file) return;

                let formData = new FormData();
                formData.append('imagen', file);

                // --- DETERMINAR DESTINO Y DATOS ---
                let rutaAjax = '';
                
                // CASO A: Es una NOTICIA (tiene data-id)
                if (img.classList.contains('imagen-noticia-editable')) {
                    formData.append('id', img.getAttribute('data-id'));
                    
                    // Ajuste de ruta según donde estemos
                    rutaAjax = window.location.pathname.includes('/public/') 
                        ? '../php/admin/subir_imagen_noticia.php' 
                        : 'php/admin/subir_imagen_noticia.php';
                } 
                // CASO B: Es una SECCIÓN NORMAL (tiene data-seccion)
                else {
                    formData.append('seccion', img.getAttribute('data-seccion'));
                    formData.append('clave', img.getAttribute('data-clave'));
                    
                    rutaAjax = window.location.pathname.includes('/public/') 
                        ? '../php/admin/subir_imagen_ajax.php' 
                        : 'php/admin/subir_imagen_ajax.php';
                }

                // Feedback visual
                img.style.opacity = '0.5';
                document.body.style.cursor = 'wait';

                // Enviar AJAX
                fetch(rutaAjax, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Actualizar imagen con truco anti-caché
                        img.src = data.url + '?t=' + new Date().getTime();
                        
                        // Si es noticia, a veces hay que ajustar la ruta visual si estamos en public
                        if (img.classList.contains('imagen-noticia-editable') && !data.url.startsWith('http')) {
                            // Pequeño ajuste visual si la respuesta no trae 'public/' al inicio y estamos dentro
                            if (window.location.pathname.includes('/public/') && !data.url.includes('public/')) {
                                // En este caso específico, subir_imagen_noticia devuelve la ruta relativa bien,
                                // así que no debería dar problema, pero por seguridad:
                                // img.src = data.url; 
                            }
                        }
                        
                        alert('✅ Imagen actualizada correctamente');
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => { console.error(error); alert('Error de conexión'); })
                .finally(() => {
                    img.style.opacity = '1';
                    document.body.style.cursor = 'default';
                });
            };

            input.click();
        });
    });
});