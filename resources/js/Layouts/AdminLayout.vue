<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import UserDropdown from '@/Components/UserDropdown.vue';
import { ref } from 'vue';

const sidebarOpen = ref(false);
const sidebarCollapsed = ref(false);

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
  sidebarOpen.value = false;
};

const toggleCollapse = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
};
</script>

<template>
  <div class="flex h-screen bg-gray-100 overflow-hidden">
    <!-- Overlay para móvil/tablet -->
    <Transition name="fade">
      <div
        v-if="sidebarOpen"
        @click="closeSidebar"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"
      ></div>
    </Transition>

    <!-- Sidebar -->
    <aside
      class="fixed lg:static inset-y-0 left-0 z-40 bg-gradient-to-b from-gray-800 to-gray-900 text-white transform transition-all duration-300 ease-in-out"
      :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarCollapsed ? 'w-20' : 'w-64'
      ]"
    >
      <div class="flex flex-col h-full">
        <!-- Logo/Header del Sidebar -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
          <Transition name="fade" mode="out-in">
            <h2 v-if="!sidebarCollapsed" class="text-xl font-bold">Administrador</h2>
            <div v-else class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
          </Transition>
          <button @click="closeSidebar" class="lg:hidden text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Botón de colapsar (solo desktop) -->
        <div class="hidden lg:flex items-center justify-center py-3 border-b border-gray-700">
          <button
            @click="toggleCollapse"
            class="p-2 rounded-lg hover:bg-gray-700 transition-colors"
            :title="sidebarCollapsed ? 'Expandir' : 'Minimizar'"
          >
            <svg 
              class="w-5 h-5 transition-transform duration-300"
              :class="{ 'rotate-180': sidebarCollapsed }"
              fill="none" 
              stroke="currentColor" 
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
            </svg>
          </button>
        </div>

        <!-- Navegación -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
          <!-- Dashboard -->
          <Link
            href="/admin/dashboard"
            @click="closeSidebar"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-all group relative"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <Transition name="fade" mode="out-in">
              <span v-if="!sidebarCollapsed">Dashboard</span>
            </Transition>
            
            <!-- Tooltip cuando está colapsado -->
            <Transition name="fade">
              <div
                v-if="sidebarCollapsed"
                class="absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50"
              >
                Dashboard
              </div>
            </Transition>
          </Link>

          <!-- Componentes -->
          <Link
            href="/admin/componentes"
            @click="closeSidebar"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-all group relative"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
            <Transition name="fade" mode="out-in">
              <span v-if="!sidebarCollapsed">Componentes</span>
            </Transition>
            
            <Transition name="fade">
              <div
                v-if="sidebarCollapsed"
                class="absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50"
              >
                Componentes
              </div>
            </Transition>
          </Link>

          <!-- Usuarios -->
          <Link
            href="/admin/usuarios"
            @click="closeSidebar"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-all group relative"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <Transition name="fade" mode="out-in">
              <span v-if="!sidebarCollapsed">Usuarios</span>
            </Transition>
            
            <Transition name="fade">
              <div
                v-if="sidebarCollapsed"
                class="absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50"
              >
                Usuarios
              </div>
            </Transition>
          </Link>

          <!-- Validar Mantenimientos -->
          <Link
            href="/admin/mantenimientos"
            @click="closeSidebar"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition-all group relative"
          >
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <Transition name="fade" mode="out-in">
              <span v-if="!sidebarCollapsed">Validar Mantenimientos</span>
            </Transition>
            
            <Transition name="fade">
              <div
                v-if="sidebarCollapsed"
                class="absolute left-full ml-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50"
              >
                Validar Mantenimientos
              </div>
            </Transition>
          </Link>
        </nav>
      </div>
    </aside>

    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Navbar -->
      <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex items-center">
              <!-- Botón hamburguesa (móvil/tablet) -->
              <button
                @click="toggleSidebar"
                class="lg:hidden mr-3 text-gray-600 hover:text-gray-900 focus:outline-none"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>

              <h1 class="text-xl sm:text-2xl font-bold text-blue-600">Energy & Control</h1>
              <span class="hidden md:block ml-4 text-sm text-gray-600">Sistema Integral de Componentes Diesel para Energy & Controls</span>
            </div>
            
            <div class="flex items-center">
              <UserDropdown />
            </div>
          </div>
        </div>
      </nav>

      <!-- Área de Contenido -->
      <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
        <div class="container-responsive mx-auto px-4 sm:px-6 lg:px-8 py-4">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>