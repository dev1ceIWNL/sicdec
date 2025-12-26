<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { computed, ref } from 'vue';

interface Componente {
  id_componente: number;
  numero_serie: string;
  nombre: string;
  tipo: string;
}

interface Tecnico {
  id_usuario: number;
  nombre: string;
}

interface Mantenimiento {
  id_mantenimiento: number;
  fecha: string;
  descripcion: string;
  notas_tecnicas: string;
  estado: string;
  componente: Componente;
  tecnico: Tecnico;
}

const props = defineProps<{
  mes: number;
  anio: number;
  mantenimientos: Record<number, Mantenimiento[]>;
  sugerencias: Record<number, Array<{ componente: string; ultima_fecha: string }>>;
}>();

const meses = [
  'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
  'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
];

const selectedDay = ref<number | null>(null);
const showModal = ref(false);

// Calcular días del mes
const diasDelMes = computed(() => {
  return new Date(props.anio, props.mes, 0).getDate();
});

// Calcular primer día de la semana (0 = Domingo, 1 = Lunes, etc.)
const primerDiaSemana = computed(() => {
  return new Date(props.anio, props.mes - 1, 1).getDay();
});

// Generar array de días
const dias = computed(() => {
  const dias = [];
  // Días vacíos al inicio
  for (let i = 0; i < primerDiaSemana.value; i++) {
    dias.push(null);
  }
  // Días del mes
  for (let i = 1; i <= diasDelMes.value; i++) {
    dias.push(i);
  }
  return dias;
});

// Verificar si un día tiene mantenimientos
const tieneMantenimientos = (dia: number) => {
  return props.mantenimientos[dia] && props.mantenimientos[dia].length > 0;
};

// Verificar si un día tiene sugerencias
const tieneSugerencias = (dia: number) => {
  return props.sugerencias[dia] && props.sugerencias[dia].length > 0;
};

// Navegar a mes anterior
const mesAnterior = () => {
  let nuevoMes = props.mes - 1;
  let nuevoAnio = props.anio;
  
  if (nuevoMes < 1) {
    nuevoMes = 12;
    nuevoAnio--;
  }
  
  // Limitar a 48 meses atrás
  const fechaActual = new Date();
  const fechaLimite = new Date(fechaActual.getFullYear(), fechaActual.getMonth() - 48, 1);
  const fechaNueva = new Date(nuevoAnio, nuevoMes - 1, 1);
  
  if (fechaNueva >= fechaLimite) {
    router.get(`/user/mantenimientos/calendario?mes=${nuevoMes}&anio=${nuevoAnio}`);
  }
};

// Navegar a mes siguiente
const mesSiguiente = () => {
  let nuevoMes = props.mes + 1;
  let nuevoAnio = props.anio;
  
  if (nuevoMes > 12) {
    nuevoMes = 1;
    nuevoAnio++;
  }
  
  // No permitir avanzar más del mes actual
  const fechaActual = new Date();
  const fechaNueva = new Date(nuevoAnio, nuevoMes - 1, 1);
  
  if (fechaNueva <= fechaActual) {
    router.get(`/user/mantenimientos/calendario?mes=${nuevoMes}&anio=${nuevoAnio}`);
  }
};

// Verificar si puede navegar atrás (hasta 48 meses)
const puedeRetroceder = computed(() => {
  const fechaActual = new Date();
  const fechaLimite = new Date(fechaActual.getFullYear(), fechaActual.getMonth() - 48, 1);
  const fechaCalendario = new Date(props.anio, props.mes - 1, 1);
  return fechaCalendario > fechaLimite;
});

// Verificar si puede avanzar (no más del mes actual)
const puedeAvanzar = computed(() => {
  const fechaActual = new Date();
  const fechaCalendario = new Date(props.anio, props.mes - 1, 1);
  return fechaCalendario < new Date(fechaActual.getFullYear(), fechaActual.getMonth(), 1);
});

// Abrir modal con detalles del día
const abrirDetalle = (dia: number) => {
  if (tieneMantenimientos(dia) || tieneSugerencias(dia)) {
    selectedDay.value = dia;
    showModal.value = true;
  }
};

const cerrarModal = () => {
  showModal.value = false;
  selectedDay.value = null;
};

// Obtener mantenimientos del día seleccionado
const mantenimientosDia = computed(() => {
  if (selectedDay.value && props.mantenimientos[selectedDay.value]) {
    return props.mantenimientos[selectedDay.value];
  }
  return [];
});

// Obtener sugerencias del día seleccionado
const sugerenciasDia = computed(() => {
  if (selectedDay.value && props.sugerencias[selectedDay.value]) {
    return props.sugerencias[selectedDay.value];
  }
  return [];
});

const getEstadoColor = (estado: string) => {
  switch (estado) {
    case 'aprobado': return 'text-green-600';
    case 'rechazado': return 'text-red-600';
    default: return 'text-yellow-600';
  }
};
</script>

<template>
  <UserLayout>
    <Head title="Calendario de Mantenimientos" />

    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Calendario de Mantenimientos</h1>
          <p class="text-sm sm:text-base text-gray-600 mt-1">Visualiza y gestiona los mantenimientos mensuales</p>
        </div>
        <Link
          href="/user/mantenimientos/crear"
          class="w-full sm:w-auto bg-green-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Registrar Mantenimiento
        </Link>
      </div>

      <!-- Navegación de mes -->
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6">
        <div class="flex justify-between items-center mb-6">
          <button
            @click="mesAnterior"
            :disabled="!puedeRetroceder"
            class="p-2 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>

          <h2 class="text-xl sm:text-2xl font-bold text-gray-800">
            {{ meses[mes - 1] }} {{ anio }}
          </h2>

          <button
            @click="mesSiguiente"
            :disabled="!puedeAvanzar"
            class="p-2 rounded-lg hover:bg-gray-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </div>

        <!-- Días de la semana -->
        <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-2">
          <div v-for="dia in ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb']" :key="dia"
            class="text-center font-semibold text-gray-600 text-xs sm:text-sm py-2"
          >
            {{ dia }}
          </div>
        </div>

        <!-- Días del mes -->
        <div class="grid grid-cols-7 gap-1 sm:gap-2">
       <div
  v-for="(dia, index) in dias"
  :key="index"
  @click="dia && (tieneMantenimientos(dia) || tieneSugerencias(dia)) ? abrirDetalle(dia) : null"
  class="aspect-square border border-gray-200 rounded-lg p-1 sm:p-2 relative transition"
  :class="{
    'bg-gray-50': !dia,
    'bg-white hover:bg-gray-50': dia && !tieneMantenimientos(dia) && !tieneSugerencias(dia),
    'bg-blue-50 border-blue-300 hover:bg-blue-100 cursor-pointer': dia && tieneMantenimientos(dia),
    'bg-yellow-50 border-yellow-300 hover:bg-yellow-100 cursor-pointer': dia && !tieneMantenimientos(dia) && tieneSugerencias(dia),
  }"
>
  <span v-if="dia" class="text-xs sm:text-base font-semibold text-gray-700">{{ dia }}</span>
  
  <!-- Indicador de mantenimientos - Esquina superior derecha -->
  <span v-if="dia && tieneMantenimientos(dia)" class="absolute top-1 right-1 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-blue-600 rounded-full shadow-md block"></span>

  <!-- Indicador de sugerencias - Esquina superior derecha -->
  <span v-if="dia && !tieneMantenimientos(dia) && tieneSugerencias(dia)" class="absolute top-1 right-1 w-2.5 h-2.5 sm:w-3 sm:h-3 bg-yellow-500 rounded-full shadow-md block"></span>
</div>
        <!-- Leyenda -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-6 mt-6 justify-center text-xs sm:text-sm">
          <div class="flex items-center justify-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-blue-600 rounded-full"></div>
            <span class="text-gray-600">Mantenimientos realizados</span>
          </div>
          <div class="flex items-center justify-center gap-2">
            <div class="w-3 h-3 sm:w-4 sm:h-4 bg-yellow-500 rounded-full"></div>
            <span class="text-gray-600">Sugerencias preventivas</span>
          </div>
        </div>
      </div>
    </div>
    </div>
    

    <!-- Modal de detalles -->
    <Transition name="modal-backdrop">
      <div
        v-if="showModal && selectedDay"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="cerrarModal"
      >
        <Transition name="modal-content">
          <div v-if="showModal" class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header del modal -->
            <div class="sticky top-0 bg-white border-b border-gray-200 p-4 sm:p-6 flex justify-between items-center z-10">
              <h3 class="text-xl sm:text-2xl font-bold text-gray-800">
                {{ selectedDay }} de {{ meses[mes - 1] }}, {{ anio }}
              </h3>
              <button @click="cerrarModal" class="text-gray-400 hover:text-gray-600 transition-fast">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Contenido del modal -->
            <div class="p-4 sm:p-6 space-y-6">
              <!-- Mantenimientos realizados -->
              <div v-if="mantenimientosDia.length > 0">
                <h4 class="font-semibold text-base sm:text-lg text-gray-800 mb-3 flex items-center">
                  <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                  Mantenimientos Realizados ({{ mantenimientosDia.length }})
                </h4>
                <div class="space-y-3">
                  <div
                    v-for="mantenimiento in mantenimientosDia"
                    :key="mantenimiento.id_mantenimiento"
                    class="bg-blue-50 border border-blue-200 rounded-lg p-4"
                  >
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-2 sm:gap-0 mb-2">
                      <div>
                        <p class="font-semibold text-gray-800">
                          {{ mantenimiento.componente ? mantenimiento.componente.nombre : 'Mantenimiento de Banco' }}
                        </p>
                        <p v-if="mantenimiento.componente" class="text-sm text-gray-600">
                          {{ mantenimiento.componente.numero_serie }}
                        </p>
                        <span v-else class="inline-block text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full mt-1">
                          Banco de Pruebas
                        </span>
                      </div>
                      <span
                        class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap"
                        :class="{
                          'bg-green-100 text-green-800': mantenimiento.estado === 'aprobado',
                          'bg-red-100 text-red-800': mantenimiento.estado === 'rechazado',
                          'bg-yellow-100 text-yellow-800': mantenimiento.estado === 'pendiente'
                        }"
                      >
                        {{ mantenimiento.estado.charAt(0).toUpperCase() + mantenimiento.estado.slice(1) }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-700 mb-2">
                      <span class="font-medium">Técnico:</span> {{ mantenimiento.tecnico.nombre }}
                    </p>
                    <p class="text-sm text-gray-700 mb-2">
                      <span class="font-medium">Descripción:</span> {{ mantenimiento.descripcion }}
                    </p>
                    <div v-if="mantenimiento.notas_tecnicas" class="text-sm text-gray-700 bg-white p-3 rounded border border-blue-200">
                      <span class="font-medium">Notas Técnicas:</span>
                      <pre class="whitespace-pre-wrap mt-1 font-sans">{{ mantenimiento.notas_tecnicas }}</pre>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Sugerencias preventivas -->
              <div v-if="sugerenciasDia.length > 0">
                <h4 class="font-semibold text-base sm:text-lg text-gray-800 mb-3 flex items-center">
                  <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                  Sugerencias de Mantenimiento Preventivo
                </h4>
                <div class="space-y-3">
                  <div
                    v-for="(sugerencia, index) in sugerenciasDia"
                    :key="index"
                    class="bg-yellow-50 border border-yellow-200 rounded-lg p-4"
                  >
                    <p class="font-semibold text-gray-800 mb-1">{{ sugerencia.componente }}</p>
                    <p class="text-sm text-gray-600">
                      Último mantenimiento: {{ new Date(sugerencia.ultima_fecha).toLocaleDateString() }}
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                      ⚠️ Han transcurrido aproximadamente 25 días. Considere realizar mantenimiento preventivo.
                    </p>
                  </div>
                </div>
              </div>

              <!-- Si no hay contenido -->
              <div v-if="mantenimientosDia.length === 0 && sugerenciasDia.length === 0" class="text-center py-8 text-gray-500">
                No hay información para este día
              </div>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </UserLayout>
</template>

<style scoped>
.modal-backdrop-enter-active,
.modal-backdrop-leave-active {
  transition: opacity 0.3s ease;
}

.modal-backdrop-enter-from,
.modal-backdrop-leave-to {
  opacity: 0;
}

.modal-content-enter-active,
.modal-content-leave-active {
  transition: all 0.3s ease;
}

.modal-content-enter-from,
.modal-content-leave-to {
  opacity: 0;
  transform: scale(0.95);
}
</style>