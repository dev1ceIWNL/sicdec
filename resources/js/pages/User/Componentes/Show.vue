<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

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

interface Tecnico {
  id_usuario: number;
  nombre: string;
}

interface Admin {
  id_usuario: number;
  nombre: string;
}

interface Validacion {
  id_validacion: number;
  fecha_validacion: string;
  decision: string;
  comentario: string;
  admin: Admin;
}

interface Mantenimiento {
  id_mantenimiento: number;
  fecha: string;
  descripcion: string;
  notas_tecnicas: string;
  estado: string;
  tecnico: Tecnico;
  validacion: Validacion | null;
}

const props = defineProps<{
  componente: Componente;
  mantenimientos: Mantenimiento[];
  ultimoMantenimiento: Mantenimiento | null;
  diasDesdeUltimo: number | null;
}>();

const getEstadoBadge = (estado: string) => {
  switch (estado) {
    case 'pendiente':
      return 'bg-yellow-100 text-yellow-800';
    case 'aprobado':
      return 'bg-green-100 text-green-800';
    case 'rechazado':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
  }
};

const getAlertaMantenimiento = () => {
  if (!props.diasDesdeUltimo) return null;
  
  if (props.diasDesdeUltimo >= 25) {
    return {
      tipo: 'danger',
      mensaje: `Han pasado ${props.diasDesdeUltimo} días desde el último mantenimiento. Se recomienda mantenimiento preventivo.`,
      clase: 'bg-red-50 border-red-200 text-red-800'
    };
  } else if (props.diasDesdeUltimo >= 20) {
    return {
      tipo: 'warning',
      mensaje: `Han pasado ${props.diasDesdeUltimo} días desde el último mantenimiento. Próximo a requerir mantenimiento.`,
      clase: 'bg-yellow-50 border-yellow-200 text-yellow-800'
    };
  } else {
    return {
      tipo: 'success',
      mensaje: `Último mantenimiento hace ${props.diasDesdeUltimo} días. En buen estado.`,
      clase: 'bg-green-50 border-green-200 text-green-800'
    };
  }
};

const alerta = getAlertaMantenimiento();
</script>

<template>
  <UserLayout>
    <Head :title="componente.nombre" />

    <div class="space-y-6">
      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm">
        <Link href="/user/componentes" class="text-blue-600 hover:text-blue-800">
          Búsqueda
        </Link>
        <span class="text-gray-400">/</span>
        <span class="text-gray-600">{{ componente.nombre }}</span>
      </div>

      <!-- Header -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-start">
          <div>
            <div class="flex items-center gap-3 mb-2">
              <h1 class="text-3xl font-bold text-gray-800">{{ componente.nombre }}</h1>
              <span
                class="px-3 py-1 rounded-full text-sm font-semibold"
                :class="componente.estatus ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'"
              >
                {{ componente.estatus ? 'Activo' : 'Inactivo' }}
              </span>
            </div>
            <p class="text-gray-600 font-mono">Serie: {{ componente.numero_serie }}</p>
          </div>

          <!-- Badge de tipo -->
          <span
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-lg font-medium"
            :class="componente.tipo === 'Inyector' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700'"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
            {{ componente.tipo }}
          </span>
        </div>
      </div>

      <!-- Alerta de mantenimiento -->
      <div v-if="alerta" class="rounded-lg border-2 p-4" :class="alerta.clase">
        <div class="flex items-start gap-3">
          <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <div>
            <p class="font-semibold">{{ alerta.mensaje }}</p>
            <Link href="/user/mantenimientos/crear" class="text-sm underline mt-1 inline-block">
              Registrar nuevo mantenimiento
            </Link>
          </div>
        </div>
      </div>

      <!-- Información y Manual -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información del componente -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-4">Información del Componente</h2>
          
          <div class="grid grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600 font-medium">Marca</p>
              <p class="text-gray-800">{{ componente.marca }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 font-medium">Modelo</p>
              <p class="text-gray-800">{{ componente.modelo }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 font-medium">Tipo</p>
              <p class="text-gray-800">{{ componente.tipo }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600 font-medium">Número de Serie</p>
              <p class="text-gray-800 font-mono">{{ componente.numero_serie }}</p>
            </div>
          </div>

          <div class="mt-4">
            <p class="text-sm text-gray-600 font-medium mb-1">Descripción</p>
            <p class="text-gray-800">{{ componente.descripcion || 'Sin descripción' }}</p>
          </div>
        </div>

        <!-- Manual PDF -->
        <div class="bg-white rounded-lg shadow-lg p-6">
          <h2 class="text-xl font-bold text-gray-800 mb-4">Manual Técnico</h2>
          
          <div v-if="componente.pdf_path" class="space-y-4">
            <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4 flex items-center gap-3">
              <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              <div class="flex-1">
                <p class="font-semibold text-gray-800">Manual disponible</p>
                <p class="text-xs text-gray-600">Formato PDF</p>
              </div>
            </div>

            
            <a
              :href="`/storage/${componente.pdf_path}`"
              target="_blank"
              rel="noopener noreferrer"
              class="block w-full bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition text-center font-semibold"
            >
              Descargar Manual
            </a>

            <a
              :href="`/storage/${componente.pdf_path}`"
              target="_blank"
              rel="noopener noreferrer"
              class="block w-full bg-gray-200 text-gray-700 px-4 py-3 rounded-lg hover:bg-gray-300 transition text-center font-semibold"
            >
              Ver en Navegador
            </a>
          </div>

          <div v-else class="text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-500">No hay manual disponible</p>
          </div>
        </div>
      </div>

      <!-- Historial de mantenimientos -->
      <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
          <h2 class="text-xl font-bold text-gray-800">
            Historial de Mantenimientos ({{ mantenimientos.length }})
          </h2>
          <Link
            href="/user/mantenimientos/crear"
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-semibold"
          >
            + Registrar Mantenimiento
          </Link>
        </div>

        <div v-if="mantenimientos.length === 0" class="text-center py-12">
          <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
          <p class="text-gray-500 mb-2">No hay mantenimientos registrados</p>
          <p class="text-sm text-gray-400">Este componente aún no tiene historial</p>
        </div>

        <div v-else class="space-y-4">
          <div
            v-for="mantenimiento in mantenimientos"
            :key="mantenimiento.id_mantenimiento"
            class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition"
          >
            <div class="flex justify-between items-start mb-3">
              <div>
                <p class="font-semibold text-gray-800">{{ mantenimiento.descripcion }}</p>
                <p class="text-sm text-gray-600">
                  {{ new Date(mantenimiento.fecha).toLocaleDateString('es-ES', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                  }) }}
                </p>
              </div>
              <span
                class="px-3 py-1 rounded-full text-xs font-semibold"
                :class="getEstadoBadge(mantenimiento.estado)"
              >
                {{ mantenimiento.estado.charAt(0).toUpperCase() + mantenimiento.estado.slice(1) }}
              </span>
            </div>

            <div class="text-sm text-gray-600 mb-2">
              <span class="font-medium">Técnico:</span> {{ mantenimiento.tecnico.nombre }}
            </div>

            <div v-if="mantenimiento.notas_tecnicas" class="bg-gray-50 rounded p-3 text-sm text-gray-700 whitespace-pre-line">
              {{ mantenimiento.notas_tecnicas }}
            </div>

            <div v-if="mantenimiento.validacion" class="mt-3 pt-3 border-t border-gray-200 text-sm">
              <p class="text-gray-600">
                <span class="font-medium">Validado por:</span> {{ mantenimiento.validacion.admin.nombre }}
                <span class="text-gray-400 ml-2">
                  {{ new Date(mantenimiento.validacion.fecha_validacion).toLocaleDateString() }}
                </span>
              </p>
              <p v-if="mantenimiento.validacion.comentario" class="text-gray-600 mt-1">
                <span class="font-medium">Comentario:</span> {{ mantenimiento.validacion.comentario }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>