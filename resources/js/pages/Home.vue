<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
</script>

<template>
  <Head title="Home" />
  
  <div class="min-h-screen bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Bienvenido - Usuario</h1>
        <p class="text-gray-600 mb-6">Has iniciado sesión correctamente como usuario regular.</p>
        
        <form method="POST" action="/logout">
          <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
            Cerrar Sesión
          </button>
        </form>
      </div>
    </div>
  </div>
</template>