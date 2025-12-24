<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

interface Stats {
  total_componentes: number;
  componentes_activos: number;
  total_mantenimientos: number;
  mantenimientos_pendientes: number;
  total_usuarios: number;
  usuarios_activos: number;
}

defineProps<{
  quote: { message: string; author: string };
  stats: Stats;
}>();
</script>

<template>
  <AdminLayout>
    <Head title="Dashboard" />

    <div>
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Dashboard Administrativo</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Bienvenido a SICDEC</p>
      </div>


      <!-- Stats Grid - Responsive -->
      <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 hover-lift">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm text-gray-500 uppercase">Total Componentes</p>
              <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ stats.total_componentes }}</p>
              <p class="text-xs sm:text-sm text-green-600 mt-1">{{ stats.componentes_activos }} activos</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3 sm:p-4">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 hover-lift">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm text-gray-500 uppercase">Mantenimientos</p>
              <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ stats.total_mantenimientos }}</p>
              <p class="text-xs sm:text-sm text-orange-600 mt-1">{{ stats.mantenimientos_pendientes }} pendientes</p>
            </div>
            <div class="bg-orange-100 rounded-full p-3 sm:p-4">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 hover-lift sm:col-span-2 xl:col-span-1">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs sm:text-sm text-gray-500 uppercase">Usuarios</p>
              <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ stats.total_usuarios }}</p>
              <p class="text-xs sm:text-sm text-green-600 mt-1">{{ stats.usuarios_activos }} activos</p>
            </div>
            <div class="bg-green-100 rounded-full p-3 sm:p-4">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Acciones Rápidas -->
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-4">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
          <a
            href="/admin/componentes/crear"
            class="flex items-center gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-smooth group"
          >
            <div class="bg-blue-100 rounded-full p-2 group-hover:bg-blue-200 transition">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
              </svg>
            </div>
            <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-blue-700">Nuevo Componente</span>
          </a>

          <a
            href="/admin/usuarios/crear"
            class="flex items-center gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-smooth group"
          >
            <div class="bg-green-100 rounded-full p-2 group-hover:bg-green-200 transition">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
            </div>
            <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-green-700">Nuevo Usuario</span>
          </a>

          <a
            href="/admin/mantenimientos"
            class="flex items-center gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg hover:border-orange-500 hover:bg-orange-50 transition-smooth group"
          >
            <div class="bg-orange-100 rounded-full p-2 group-hover:bg-orange-200 transition">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </div>
            <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-orange-700">Validar Mantenimientos</span>
          </a>

          <a
            href="/admin/componentes"
            class="flex items-center gap-3 p-3 sm:p-4 border border-gray-200 rounded-lg hover:border-purple-500 hover:bg-purple-50 transition-smooth group"
          >
            <div class="bg-purple-100 rounded-full p-2 group-hover:bg-purple-200 transition">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
            <span class="text-sm sm:text-base font-medium text-gray-700 group-hover:text-purple-700">Ver Componentes</span>
          </a>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>