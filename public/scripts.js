document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector('.formulario');   

    form?.addEventListener('submit', async function (e) {
        e.preventDefault();

        const loader = document.getElementById('loader');
        const submitBtn = form.querySelector('button');

        loader.style.display = 'block';         // Mostrar spinner
        submitBtn.disabled = true;              // Desactivar botón
        submitBtn.innerText = 'Registrando...'; // Cambiar texto del botón

        const formData = new FormData(form);
        const res = await fetch('registrar.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json();

        loader.style.display = 'none';          // Ocultar spinner
        submitBtn.disabled = false;             // Reactivar botón
        submitBtn.innerText = 'Confirmar asistencia'; // Restaurar texto

        if (data.success) {
            await actualizarLista();
            await actualizarAlertas();
            form.reset();
            document.getElementById('mensajeError').innerHTML = '';
            lanzarConfeti();
        } else {
            document.getElementById('mensajeError').innerHTML = `
                <div class="alerta" style="background-color: #b71c1c;">
                    ${data.message}
                </div>
            `;
        }
    });


    window.actualizarLista = async function () {
        const res = await fetch('cargar_lista.php');
        const html = await res.text();
        document.getElementById('lista').innerHTML = html;
    }

    window.actualizarAlertas = async function () {
        const res = await fetch('cargar_alertas.php');
        const html = await res.text();

        const alertas = document.getElementById('alertas');
        alertas.innerHTML = html;
        alertas.classList.add('actualizado');
        setTimeout(() => {
            alertas.classList.remove('actualizado');
        }, 700);

        // 👇 Revisamos si el cupo está completo
        if (html.includes('¡Cupo completo!')) {
            const form = document.querySelector('.formulario');
            if (form) form.remove();
        }
    };

    function lanzarConfeti() {
        confetti({
            particleCount: 100,
            spread: 80,
            origin: { y: 0.6 },
            colors: ['#66bb6a', '#ffffff', '#ffee58']
        });
    }
});
