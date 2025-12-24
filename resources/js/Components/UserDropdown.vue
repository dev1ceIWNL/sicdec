<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
const user = page.props.auth.user;
const isOpen = ref(false);

const toggleDropdown = () => {
  isOpen.value = !isOpen.value;
};

const closeDropdown = () => {
  isOpen.value = false;
};

// Cerrar dropdown al hacer clic fuera
const handleClickOutside = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.user-dropdown')) {
    closeDropdown();
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div class="relative user-dropdown">
    <!-- Botón del dropdown -->
    <button
      @click="toggleDropdown"
      class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 transition-smooth focus:outline-none focus:ring-2 focus:ring-blue-500"
    >
      <!-- Avatar -->
      <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
        {{ user.nombre.charAt(0).toUpperCase() }}
      </div>
      
      <!-- Info del usuario -->
      <div class="text-left hidden md:block">
        <p class="text-sm font-semibold text-gray-800">{{ user.nombre }}</p>
        <p class="text-xs text-gray-500">{{ user.rol === 'admin' ? 'Administrador' : 'Usuario' }}</p>
      </div>
      
      <!-- Icono flecha -->
      <svg 
        class="w-4 h-4 text-gray-600 transition-transform"
        :class="{ 'rotate-180': isOpen }"
        fill="none" 
        stroke="currentColor" 
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <!-- Menú desplegable -->
    <Transition name="fade">
      <div
        v-if="isOpen"
        class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50"
      >
        <!-- Info del usuario (móvil) -->
        <div class="px-4 py-3 border-b border-gray-200 md:hidden">
          <p class="text-sm font-semibold text-gray-800">{{ user.nombre }}</p>
          <p class="text-xs text-gray-500">{{ user.correo }}</p>
        </div>

        <!-- Opciones del menú -->
        <Link
          :href="user.rol === 'admin' ? '/admin/perfil' : '/user/perfil'"
          @click="closeDropdown"
          class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-fast"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-sm text-gray-700">Mi Perfil</span>
        </Link>

        <Link
          :href="user.rol === 'admin' ? '/admin/faq' : '/user/faq'"
          @click="closeDropdown"
          class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 transition-fast"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span class="text-sm text-gray-700">Ayuda / FAQ</span>
        </Link>

        <hr class="my-2 border-gray-200">

        <Link
          href="/logout"
          method="post"
          as="button"
          @click="closeDropdown"
          class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-50 transition-fast text-left"
        >
          <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span class="text-sm text-red-600 font-medium">Cerrar Sesión</span>
        </Link>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.2s ease;
}

.fade-enter-from {
  opacity: 0;
  transform: translateY(-10px);
}

.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>