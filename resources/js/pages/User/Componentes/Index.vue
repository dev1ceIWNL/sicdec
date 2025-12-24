<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { ref, watch } from 'vue';

interface Componente {
  id_componente: number;
  numero_serie: string;
  nombre: string;
  tipo: string;
  marca: string;
  modelo: string;
  descripcion: string;
  estatus: number;
  pdf_path: string | null;
}

interface Filters {
  search: string | null;
  tipo: string | null;
  estatus: string | null;
}

const props = defineProps<{
  componentes: Componente[];
  filters: Filters;
}>();

const search = ref(props.filters.search || '');
const tipoFilter = ref(props.filters.tipo || '');
const estatusFilter = ref(props.filters.estatus || '');

// Buscar cuando cambian los filtros
const buscar = () => {
  router.get('/user/componentes', {
    search: search.value || undefined,
    tipo: tipoFilter.value || undefined,
    estatus: estatusFilter.value || undefined,
  }, {
    preserveState: true,
    preserveScroll: true,
  });
};

// Limpiar filtros
const limpiarFiltros = () => {
  search.value = '';
  tipoFilter.value = '';
  estatusFilter.value = '';
  router.get('/user/componentes');
};
</script>

<template>
  <UserLayout>
    <Head title="Búsqueda de Componentes" />

    <div class="space-y-6">
      <!-- Header -->
      <div>
        <h1 class="text-3xl font-bold text-gray-800">Búsqueda de Componentes</h1>
        <p class="text-gray-600 mt-1">Encuentra inyectores, bombas y consulta sus manuales</p>
      </div>

      <!-- Buscador y Filtros -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Búsqueda por texto -->
          <div class="md:col-span-2">
            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
              Buscar
            </label>
            <div class="relative">
              <input
                id="search"
                v-model="search"
                type="text"
                @keyup.enter="buscar"
                placeholder="Nombre, número de serie..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
              <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Filtro por tipo -->
          <div>
            <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
              Tipo
            </label>
            <select
              id="tipo"
              v-model="tipoFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Todos</option>
              <option value="Inyector">Inyector</option>
              <option value="Bomba">Bomba</option>
            </select>
          </div>

          <!-- Filtro por estado -->
          <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-2">
              Estado
            </label>
            <select
              id="estatus"
              v-model="estatusFilter"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
              <option value="">Todos</option>
              <option value="1">Activo</option>
              <option value="0">Inactivo</option>
            </select>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex gap-3 mt-4">
          <button
            @click="buscar"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition flex items-center gap-2"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            Buscar
          </button>
          <button
            @click="limpiarFiltros"
            class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition"
          >
            Limpiar
          </button>
        </div>
      </div>

      <!-- Resultados -->
      <div>
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">
            Resultados ({{ componentes.length }})
          </h2>
        </div>

        <!-- Sin resultados -->
        <div v-if="componentes.length === 0" class="bg-white rounded-lg shadow-lg p-12 text-center">
          <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <p class="text-gray-500 text-lg">No se encontraron componentes</p>
          <button @click="limpiarFiltros" class="mt-4 text-blue-600 hover:text-blue-800">
            Limpiar filtros
          </button>
        </div>

        <!-- Grid de tarjetas -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <Link
            v-for="componente in componentes"
            :key="componente.id_componente"
            :href="`/user/componentes/${componente.id_componente}`"
            class="bg-white rounded-lg shadow-lg hover:shadow-xl transition-all duration-300 p-6 group cursor-pointer hover-lift"
          >
            <!-- Header de la tarjeta -->
            <div class="flex justify-between items-start mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-800 group-hover:text-blue-600 transition">
                  {{ componente.nombre }}
                </h3>
                <p class="text-sm text-gray-500 font-mono">{{ componente.numero_serie }}</p>
              </div>
              <span
                class="px-3 py-1 rounded-full text-xs font-semibold"
                :class="componente.estatus ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
              >
                {{ componente.estatus ? 'Activo' : 'Inactivo' }}
              </span>
            </div>

            <!-- Tipo -->
            <div class="mb-3">
              <span
                class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-sm font-medium"
                :class="componente.tipo === 'Inyector' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700'"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                {{ componente.tipo }}
              </span>
            </div>

            <!-- Detalles -->
            <div class="space-y-1 mb-4 text-sm text-gray-600">
              <p><span class="font-medium">Marca:</span> {{ componente.marca }}</p>
              <p><span class="font-medium">Modelo:</span> {{ componente.modelo }}</p>
            </div>

            <!-- Descripción -->
            <p class="text-sm text-gray-600 line-clamp-2 mb-4">
              {{ componente.descripcion || 'Sin descripción' }}
            </p>

            <!-- Footer -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
              <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg v-if="componente.pdf_path" class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span v-if="componente.pdf_path">Manual disponible</span>
                <span v-else class="text-gray-400">Sin manual</span>
              </div>
              
              <span class="text-blue-600 group-hover:text-blue-800 font-medium flex items-center gap-1">
                Ver detalles
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </span>
            </div>
          </Link>
        </div>
      </div>
    </div>
  </UserLayout>
</template>