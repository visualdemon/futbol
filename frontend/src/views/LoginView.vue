<template>
  <BaseLayout>
    <div class="bg-white shadow-lg rounded-xl p-6 w-full max-w-sm">
      <h1 class="text-xl font-bold mb-4">Iniciar sesión</h1>
      <form @submit.prevent="login">
        <input
          v-model="password"
          type="password"
          placeholder="Contraseña"
          class="border p-2 rounded w-full mb-4"
        />
        <button
          :disabled="loading"
          type="submit"
          class="bg-blue-600 text-white w-full py-2 rounded hover:bg-blue-700"
        >
          {{ loading ? 'Ingresando...' : 'Entrar' }}
        </button>
      </form>
      <p v-if="error" class="text-red-600 mt-3">{{ error }}</p>
    </div>
  </BaseLayout>
</template>

<script setup>
import { ref } from 'vue'
import BaseLayout from '../components/BaseLayout.vue'
import { useRouter } from 'vue-router'
const router = useRouter()

const password = ref('')
const loading = ref(false)
const error = ref(null)

const login = async () => {
  loading.value = true
  error.value = null

  try {
    const response = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password: password.value })
    })

    const data = await response.json()

    if (response.ok) {
      localStorage.setItem('token', data.token)
      router.push('/admin')
    } else {
      error.value = data.error || 'Error desconocido'
    }
  } catch (err) {
    error.value = 'Fallo la conexión con el servidor'
  } finally {
    loading.value = false
  }
}
</script>
