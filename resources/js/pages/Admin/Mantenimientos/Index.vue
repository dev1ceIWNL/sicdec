<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from '@/composables/useToast';
import { ref } from 'vue';

interface Componente {
  id_componente: number;
  numero_serie: string;
  nombre: string;
  tipo: string;
}

interface Tecnico {
  id_usuario: number;
  nombre: string;
  correo: string;
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
  estado: 'pendiente' | 'aprobado' | 'rechazado';
  componente: Componente | null;
  tecnico: Tecnico;
  validacion: Validacion | null;
}

defineProps<{
  mantenimientos: Mantenimiento[];
}>();

const { success, error } = useToast();
const selectedMantenimiento = ref<Mantenimiento | null>(null);
const showModal = ref(false);

const form = useForm({
  decision: 'aprobado' as 'aprobado' | 'rechazado',
  comentario: '',
});

const openModal = (mantenimiento: Mantenimiento) => {
  selectedMantenimiento.value = mantenimiento;
  form.reset();
  form.decision = 'aprobado';
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedMantenimiento.value = null;
  form.reset();
};

const submit = () => {
  if (selectedMantenimiento.value) {
    form.post(`/admin/mantenimientos/${selectedMantenimiento.value.id_mantenimiento}/validar`, {
      onSuccess: () => {
        closeModal();
        if (form.decision === 'aprobado') {
          success('¡Mantenimiento aprobado!', 'La validación se ha registrado correctamente');
        } else {
          success('Mantenimiento rechazado', 'Se ha notificado al técnico');
        }
      },
      onError: () => {
        error('Error al validar', 'Por favor, intenta de nuevo');
      }
    });
  }
};

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
</script>

<template>
  <AdminLayout>
    <Head title="Validar Mantenimientos" />

    <div>
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Validar Mantenimientos</h1>
        <p class="text-gray-600 mt-1">Revisa y aprueba las solicitudes de mantenimiento</p>
      </div>

      <!-- Filtros rápidos -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 transition-smooth hover-lift">
          <p class="text-sm text-yellow-700 font-medium">Pendientes</p>
          <p class="text-2xl font-bold text-yellow-900">
            {{ mantenimientos.filter(m => m.estado === 'pendiente').length }}
          </p>
        </div>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 transition-smooth hover-lift">
          <p class="text-sm text-green-700 font-medium">Aprobados</p>
          <p class="text-2xl font-bold text-green-900">
            {{ mantenimientos.filter(m => m.estado === 'aprobado').length }}
          </p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 transition-smooth hover-lift">
          <p class="text-sm text-red-700 font-medium">Rechazados</p>
          <p class="text-2xl font-bold text-red-900">
            {{ mantenimientos.filter(m => m.estado === 'rechazado').length }}
          </p>
        </div>
      </div>

      <!-- Tabla de mantenimientos -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fecha
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tipo/Componente
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Técnico
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Descripción
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Estado
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="mantenimientos.length === 0">
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                  No hay mantenimientos registrados
                </td>
              </tr>
              <tr v-for="mantenimiento in mantenimientos" :key="mantenimiento.id_mantenimiento" class="hover:bg-gray-50 transition-fast">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ mantenimiento.id_mantenimiento }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ new Date(mantenimiento.fecha).toLocaleDateString() }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <div v-if="mantenimiento.componente">
                    <div class="font-medium">{{ mantenimiento.componente.nombre }}</div>
                    <div class="text-gray-500 text-xs">{{ mantenimiento.componente.numero_serie }}</div>
                  </div>
                  <div v-else>
                    <span class="inline-block text-xs bg-purple-100 text-purple-800 px-2 py-1 rounded-full">
                      Banco de Pruebas
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  <div>{{ mantenimiento.tecnico.nombre }}</div>
                  <div class="text-gray-500 text-xs">{{ mantenimiento.tecnico.correo }}</div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ mantenimiento.descripcion || 'Sin descripción' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span 
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                    :class="getEstadoBadge(mantenimiento.estado)"
                  >
                    {{ mantenimiento.estado.charAt(0).toUpperCase() + mantenimiento.estado.slice(1) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    v-if="mantenimiento.estado === 'pendiente'"
                    @click="openModal(mantenimiento)"
                    class="text-orange-600 hover:text-orange-900 transition-fast"
                  >
                    Validar
                  </button>
                  <span v-else class="text-gray-400 text-xs">
                    {{ mantenimiento.validacion ? 'Por ' + mantenimiento.validacion.admin.nombre : 'Validado' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal de Validación -->
    <Transition name="modal-backdrop">
      <div
        v-if="showModal && selectedMantenimiento"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        @click.self="closeModal"
      >
        <Transition name="modal-content">
          <div v-if="showModal" class="bg-white rounded-lg p-8 max-w-2xl w-full mx-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Validar Mantenimiento</h2>
            
            <!-- Información del mantenimiento -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2">
              <p><span class="font-semibold">ID:</span> {{ selectedMantenimiento.id_mantenimiento }}</p>
              <p>
                <span class="font-semibold">Tipo:</span> 
                {{ selectedMantenimiento.componente ? selectedMantenimiento.componente.nombre + ' (' + selectedMantenimiento.componente.numero_serie + ')' : 'Mantenimiento de Banco' }}
              </p>
              <p><span class="font-semibold">Técnico:</span> {{ selectedMantenimiento.tecnico.nombre }}</p>
              <p><span class="font-semibold">Fecha:</span> {{ new Date(selectedMantenimiento.fecha).toLocaleDateString() }}</p>
              <p><span class="font-semibold">Descripción:</span> {{ selectedMantenimiento.descripcion || 'Sin descripción' }}</p>
              <p v-if="selectedMantenimiento.notas_tecnicas" class="whitespace-pre-line">
                <span class="font-semibold">Notas Técnicas:</span><br>
                {{ selectedMantenimiento.notas_tecnicas }}
              </p>
            </div>

            <!-- Formulario de validación -->
            <form @submit.prevent="submit" class="space-y-4">
              <!-- Decisión -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Decisión *</label>
                <div class="flex gap-4">
                  <label class="flex items-center cursor-pointer">
                    <input
                      type="radio"
                      v-model="form.decision"
                      value="aprobado"
                      class="mr-2"
                    />
                    <span class="text-green-700 font-medium">Aprobar</span>
                  </label>
                  <label class="flex items-center cursor-pointer">
                    <input
                      type="radio"
                      v-model="form.decision"
                      value="rechazado"
                      class="mr-2"
                    />
                    <span class="text-red-700 font-medium">Rechazar</span>
                  </label>
                </div>
              </div>

              <!-- Comentario -->
              <div>
                <label for="comentario" class="block text-sm font-medium text-gray-700 mb-2">
                  Comentario
                </label>
                <textarea
                  id="comentario"
                  v-model="form.comentario"
                  rows="3"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-fast"
                  placeholder="Observaciones o razón de la decisión..."
                ></textarea>
              </div>

              <!-- Botones -->
              <div class="flex gap-4">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-orange-600 text-white px-6 py-2 rounded-lg hover:bg-orange-700 transition disabled:opacity-50 font-semibold"
                >
                  <span class="flex items-center gap-2">
                    <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ form.processing ? 'Guardando...' : 'Guardar Validación' }}
                  </span>
                </button>
                <button
                  type="button"
                  @click="closeModal"
                  class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-semibold"
                >
                  Cancelar
                </button>
              </div>
            </form>
          </div>
        </Transition>
      </div>
    </Transition>
  </AdminLayout>
</template>