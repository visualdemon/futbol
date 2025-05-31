# ⚽ Fútbol Jueves - App de Registro de Asistentes para Partidos Semanales

¡Bienvenido a la app definitiva para organizar partidos de fútbol entre amigos!  
Este pequeño sistema te permite gestionar las confirmaciones de asistencia para tus partidos de los jueves. Con un máximo de 12 cupos, alertas dinámicas y efectos visuales, nunca más tendrás que adivinar quién va a jugar.

![screenshot](https://your-screenshot-url.com) <!-- opcional: puedes subir una captura del sistema -->

---

## 🚀 Características

✅ Registro limitado a los días permitidos (martes a jueves antes de las 9:00 p.m.)  
✅ Alerta visual cuando se completa el cupo  
✅ Control manual de apertura/cierre del registro  
✅ Lista dinámica de asistentes  
✅ Efecto de confeti cuando alguien se registra 🎉  
✅ Sin base de datos compleja ni frameworks innecesarios  
✅ Ideal para equipos de barrio, amigos, clubes deportivos o entrenadores

---

## 🧠 ¿Cómo funciona?

1. Los jugadores ingresan su nombre para confirmar asistencia.
2. El sistema permite máximo 12 personas (10 titulares + 2 suplentes).
3. Cuando se llena el cupo, el formulario desaparece y se muestra un mensaje de éxito.
4. Puedes activar o desactivar el registro manualmente en el archivo `config/registro_manual.txt`.

---

## 🛠️ Requisitos

- PHP 7.4 o superior
- Servidor local (como XAMPP, Laragon, MAMP, etc.)
- Navegador moderno
- Opcional: MySQL si deseas extender funcionalidades

---

## 🧪 Instalación local

```bash
git clone https://github.com/visualdemon/futbol.git
cd futbol