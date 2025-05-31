
# 🎯 Registro de Asistencia para Partidos de Fútbol - Jueves 9PM

Sistema ligero en PHP para gestionar cupos limitados de asistentes a los partidos semanales. Ideal para grupos de amigos que juegan regularmente y quieren evitar el caos del WhatsApp. 😅

---

## 🎥 Demo

> ✅ Confirma tu asistencia  
> 🎉 Avisa cuando el cupo esté completo  
> 📋 Muestra titulares y suplentes automáticamente  
> 🔄 Botón para actualizar la lista al instante

---

## 📦 Funcionalidades clave

🟢 Registro habilitado automáticamente de martes a jueves antes de las 9PM  
🔐 Opción para activar el registro manual desde un archivo de texto  
📆 Control de fechas con soporte para el “próximo jueves”  
✅ Alerta visual y confetti al confirmar  
⚽ Cupo máximo de 12 jugadores (10 titulares + 2 suplentes)  
📱 Diseño responsivo para móviles  

---

## 🧪 Instalación local

```bash
git clone https://github.com/visualdmeon/futbol.git
cd futbol
```

1. Abre la carpeta en tu servidor local (`/public` debe ser el punto de entrada).
2. Asegúrate de que el archivo `config/registro_manual.txt` exista.
3. Crea la base de datos y tabla:

```sql
CREATE TABLE asistentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    fecha_partido DATE NOT NULL
);
```

---

## 🗂️ Estructura del proyecto

```
📁 futbol-jueves/
 ├── 📁 public/
 │    ├── index.php
 │    ├── scripts.js
 │    ├── styles.css
 ├── 📁 includes/
 │    ├── db.php
 │    └── helpers.php
 ├── 📁 config/
 │    └── registro_manual.txt
```

---

## 💡 Posibles mejoras

- 📊 Estadísticas históricas de partidos
- 👥 Ranking por asistencia
- 🕹️ Modo admin con gestión de temporadas
- 📱 App móvil con notificaciones push

---

## 🤝 Contribuciones

¿Te gustó? ¿Quieres mejorarlo?  
¡Haz un fork, prueba cambios y lanza un Pull Request!  
También puedes abrir un issue para ideas o reportes.

---

## 🧑‍💻 Autor

**Wilber Jurado Guerrero**  
👨‍💼 Ingeniero, desarrollador, apasionado por el deporte y la tecnología  
🌐 [ingeniaestudios.com](https://ingeniaestudios.com)  
📩 wjurado@ingeniaestudios.com

---

## ⭐ ¿Te gusta este proyecto?

Dale una estrella ⭐ en GitHub para apoyarlo  
¡y compártelo con tu parche de fútbol! ⚽🔥
