<script setup lang="ts">
import { Link, Head } from '@inertiajs/vue3';
import Toaster from '@/Components/Toaster.vue';
import UserDropdown from '@/Components/UserDropdown.vue';
</script>

<template>
  <div class="bg-gray-100">
    <Toaster /> 
  </div>
  <div class="bg-gray-100">
<!-- Navbar -->
<nav class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo (sin link) -->
      <div class="flex items-center gap-3">
        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <div class="hidden sm:block">
          <h1 class="text-xl font-bold text-white">Energy & Controls</h1>
          <p class="text-xs text-blue-100">SICDEC</p>
        </div>
      </div>
      
      <!-- Botón Inicio + Dropdown -->
      <div class="flex items-center gap-2 sm:gap-4">
        <!-- Botón Inicio Desktop -->
        <Link
          href="/user/home"
          class="hidden sm:flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-lg transition-smooth text-white font-medium"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="hidden md:inline">Inicio</span>
        </Link>

        <!-- Botón Inicio Móvil (solo icono) -->
        <Link
          href="/user/home"
          class="sm:hidden p-2 bg-white/10 hover:bg-white/20 rounded-lg transition-smooth text-white"
          title="Volver al Inicio"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
        </Link>

        <UserDropdown />
      </div>
    </div>
  </div>
</nav>

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <p class="text-center text-sm text-gray-500">
          Energy & Controls de México S.A. de C.V. - SICDEC
        </p>
      </div>
    </footer>
  </div>
</template>