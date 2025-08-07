<template>
  <div class="calendar-wrapper">
    <vue-cal
      style="height: 500px"
      :events="partidos"
      :disable-views="['years', 'year', 'week', 'day']"
      default-view="month"
      @cell-click="handleDateClick"
    >
      <!-- Sobrescribe el render de cada evento -->
      <template #event="{ event }">
        <div class="vuecal__event">
          {{ event.title }}
        </div>
      </template>
    </vue-cal>

    <!-- Modal personalizado -->
    <div v-if="mostrarModal" class="modal-overlay">
      <div class="modal-box">
        <h3>Selecciona el tipo de partido</h3>
        <p>Fecha: {{ fechaSeleccionada }}</p>
        <div class="modal-buttons">
          <button @click="guardarPartido(1)" class="btn btn-green">⚽ Oficial</button>
          <button @click="guardarPartido(0)" class="btn btn-orange">🟠 Amistoso</button>
          <button @click="eliminarPartido" class="btn btn-red">❌ Eliminar partido</button>
        </div>
        <button @click="cerrarModal" class="btn-cancel">Cancelar</button>
      </div>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import VueCal from 'vue-cal'
import 'vue-cal/dist/vuecal.css'

const API = 'http://localhost:8000/api'
const token = ref(localStorage.getItem('token') || '')
const partidos = ref([])
const mostrarModal = ref(false)
const fechaSeleccionada = ref(null)

const cargarPartidos = async () => {
  const res = await fetch(`${API}/partidos/publico`)
  const data = await res.json()

  partidos.value = data.map(p => ({
    start: p.fecha,
    end: p.fecha,
    title: p.es_oficial ? '⚽ Oficial' : '🟠 Amistoso', // Usamos title ahora
    // content: p.es_oficial ? '⚽ Oficial' : '🟠 Amistoso', // Puedes comentar o eliminar esta línea
    class: p.es_oficial ? 'evento-oficial' : 'evento-extra',
  }))

  // Agregar eventos jugados
  const partidosJugados = data.filter(p => p.jugado === 1)
  partidosJugados.forEach(p => {
    partidos.value.push({
      start: p.fecha,
      end: p.fecha,
      title: 'Partido Jugado',
      content: '✅ Jugado',
      class: 'evento-jugado'
    })
  })
}

const handleDateClick = (event) => {
  const rawDate = event instanceof Date ? event : null
  if (!rawDate) return
  fechaSeleccionada.value = rawDate.toISOString().slice(0, 10)
  mostrarModal.value = true
}

const guardarPartido = async (es_oficial) => {
  try {
    await fetch(`${API}/partidos/guardar`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token.value}`,
      },
      body: JSON.stringify({
        fecha: fechaSeleccionada.value,
        es_oficial,
        jugado: 0
      })
    })
    mostrarModal.value = false
    await cargarPartidos()
  } catch (error) {
    console.error('Error al guardar partido:', error)
  }
}

const eliminarPartido = async () => {
  try {
    await fetch(`${API}/partidos/eliminar/${fechaSeleccionada.value}`, {
      method: 'DELETE',
      headers: {
        Authorization: `Bearer ${token.value}`,
      }
    })
    mostrarModal.value = false
    await cargarPartidos()
  } catch (error) {
    console.error('Error al eliminar partido:', error)
  }
}

const cerrarModal = () => {
  mostrarModal.value = false
}

onMounted(cargarPartidos)
</script>


