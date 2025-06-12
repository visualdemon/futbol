<script setup>
import BaseLayout from '../components/BaseLayout.vue'
import Calendario from '../components/CalendarView.vue'
</script>

<template>
  <BaseLayout>
    <div class="p-4">
      <h1 class="text-2xl font-bold mb-4">Panel de Control</h1>
      <p>Bienvenido, admin 👋</p>

      <Calendario />
    </div>
  </BaseLayout>
</template>
