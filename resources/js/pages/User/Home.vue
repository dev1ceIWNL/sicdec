<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
</script>

<template>
  <UserLayout>
    <Head title="Inicio - Técnico" />

    <div class="space-y-8">
      <!-- Bienvenida -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-xl p-8 text-white">
        <h1 class="text-3xl font-bold mb-2">Sistema Integral de Componentes Diesel para Energy & Controls</h1>
        <p class="text-blue-100">Gestiona mantenimientos de inyectores y bombas diesel de forma eficiente.</p>
      </div>

      <!-- Menú de opciones principales -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Calendario de Mantenimientos -->
        <Link
          href="/user/mantenimientos/calendario"
          class="bg-white rounded-lg shadow-lg hover:shadow-xltransition-all duration-300 p-6 group hover-lift"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="bg-purple-100 rounded-full p-3 group-hover:bg-purple-200 transition">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <svg class="w-6 h-6 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-800 mb-2">Calendario</h2>
          <p class="text-gray-600 text-sm">Visualiza y programa mantenimientos mensuales</p>
        </Link>

        <!-- Nuevo Mantenimiento -->
        <Link
          href="/user/mantenimientos/crear"
          class="bg-white rounded-lg shadow-lg hover:shadow-xltransition-all duration-300 p-6 group hover-lift"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 rounded-full p-3 group-hover:bg-green-200 transition">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <svg class="w-6 h-6 text-gray-400 group-hover:text-green-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-800 mb-2">Registrar</h2>
          <p class="text-gray-600 text-sm">Nuevo mantenimiento realizado</p>
        </Link>

        <!-- Búsqueda de Componentes -->
        <Link
          href="/user/componentes"
          class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 group hover-lift"
        >
          <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 rounded-full p-3 group-hover:bg-blue-200 transition">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </div>
          <h2 class="text-xl font-bold text-gray-800 mb-2">Búsqueda</h2>
          <p class="text-gray-600 text-sm">Encuentra componentes</p>
        </Link>

        <!-- Chatbot de Ayuda -->
         
      <Link
      
         href="/user/chatbot"
          class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 group hover-lift"
          >
        <div class="flex items-center justify-between mb-4">
        <div class="bg-orange-100 rounded-full p-3 group-hover:bg-orange-200 transition">
        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
       </svg>
       </div>
       <svg class="w-6 h-6 text-gray-400 group-hover:text-orange-600 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
       </svg>
        </div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">Asistente IA</h2>
        <p class="text-gray-600 text-sm">Consulta procedimientos y dudas</p>
      </Link>
      </div>

      <!-- Información rápida -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Guía rápida -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Guía Rápida
          </h3>
          <ul class="space-y-3 text-sm text-gray-600">
            <li class="flex items-start">
              <span class="text-blue-600 mr-2">•</span>
              <span>Registra cada mantenimiento realizado en el calendario</span>
            </li>
            <li class="flex items-start">
              <span class="text-blue-600 mr-2">•</span>
              <span>Incluye detalles del banco de pruebas usado en las notas técnicas</span>
            </li>
            <li class="flex items-start">
              <span class="text-blue-600 mr-2">•</span>
              <span>Las sugerencias de mantenimiento aparecen automáticamente cada 25 días</span>
            </li>
            <li class="flex items-start">
              <span class="text-blue-600 mr-2">•</span>
              <span>Los administradores aprobarán tus registros de mantenimiento</span>
            </li>
          </ul>
        </div>

        <!-- Recordatorios -->
        <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg shadow-lg p-6 border border-yellow-200">
          <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Recordatorios
          </h3>
          <div class="space-y-3">
            <div class="bg-white rounded-lg p-3 shadow-sm">
              <p class="text-sm text-gray-700 font-medium">Mantenimiento Preventivo</p>
              <p class="text-xs text-gray-500 mt-1">Cada ~25 días o 160-240 horas de operación</p>
            </div>
            <div class="bg-white rounded-lg p-3 shadow-sm">
              <p class="text-sm text-gray-700 font-medium">Incluir en Notas</p>
              <p class="text-xs text-gray-500 mt-1">Banco usado, procedimiento realizado, observaciones</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </UserLayout>
</template>