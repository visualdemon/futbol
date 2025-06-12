document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector('.formulario');   
    const loader = document.getElementById('loader');
    const mensajeError = document.getElementById('mensajeError');
    const btnActualizar = document.getElementById('btnActualizarLista');

    if (form) {
        const submitBtn = form.querySelector('button');

        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            loader.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.innerText = 'Registrando...';
            mensajeError.innerHTML = '';

            const formData = new FormData(form);

            try {
                const res = await fetch('registrar.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    form.reset();
                    await actualizarLista();
                    await actualizarAlertas();
                    lanzarConfeti();
                } else {
                    mensajeError.innerHTML = `
                        <div class="alerta" style="background-color: #b71c1c;">
                            ${data.message}
                        </div>
                    `;
                }
            } catch (err) {
                mensajeError.innerHTML = `
                    <div class="alerta" style="background-color: #b71c1c;">
                        ❌ Error inesperado. Intenta nuevamente.
                    </div>
                `;
            } finally {
                loader.style.display = 'none';
                submitBtn.disabled = false;
                submitBtn.innerText = 'Confirmar asistencia';
            }
        });
    }

    // ✅ Este bloque debe estar AFUERA del form.addEventListener
    if (btnActualizar) {
        btnActualizar.addEventListener('click', async () => {
            loader.style.display = 'block';
            btnActualizar.disabled = true;
            btnActualizar.innerText = '🔄 Actualizando...';

            await actualizarLista();
            await actualizarAlertas();

            loader.style.display = 'none';
            btnActualizar.disabled = false;
            btnActualizar.innerText = '🔄 Actualizar lista de Asistentes confirmados...';
        });
    }

    async function actualizarLista() {
        const res = await fetch('cargar_lista.php');
        const html = await res.text();
        document.getElementById('lista').innerHTML = html;
    }

    async function actualizarAlertas() {
        const res = await fetch('cargar_alertas.php');
        const html = await res.text();

        const alertas = document.getElementById('alertas');
        alertas.innerHTML = html;
        alertas.classList.add('actualizado');
        setTimeout(() => alertas.classList.remove('actualizado'), 700);

        if (html.includes('¡Cupo completo!')) {
            const form = document.querySelector('.formulario');
            if (form) form.remove();
        }
    }

    function lanzarConfeti() {
        confetti({
            particleCount: 100,
            spread: 80,
            origin: { y: 0.6 },
            colors: ['#66bb6a', '#ffffff', '#ffee58']
        });
    }
});
