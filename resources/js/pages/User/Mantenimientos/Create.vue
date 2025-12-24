<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { useToast } from '@/composables/useToast';
import { ref } from 'vue';

interface Componente {
  id_componente: number;
  numero_serie: string;
  nombre: string;
  tipo: string;
}

defineProps<{
  componentes: Componente[];
}>();

const { success, error } = useToast();

const tipoMantenimiento = ref<'componente' | 'banco'>('componente');

const form = useForm({
  fecha: new Date().toISOString().split('T')[0],
  tipo_mantenimiento: 'componente',
  id_componente: null as number | null,
  descripcion: '',
  notas_tecnicas: '',
});

const cambiarTipo = (tipo: 'componente' | 'banco') => {
  tipoMantenimiento.value = tipo;
  form.tipo_mantenimiento = tipo;
  if (tipo === 'banco') {
    form.id_componente = null;
  }
};

const submit = () => {
  form.post('/user/mantenimientos', {
    onSuccess: () => {
      success('¡Mantenimiento registrado!', 'El mantenimiento se ha guardado correctamente');
    },
    onError: () => {
      error('Error al guardar', 'Por favor, verifica los datos e intenta de nuevo');
    }
  });
};
</script>

<template>
  <UserLayout>
    <Head title="Registrar Mantenimiento" />

    <div>
      <!-- Header -->
      <div class="mb-6">
        <Link
          href="/user/mantenimientos/calendario"
          class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4 transition-smooth"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Volver al calendario
        </Link>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Registrar Mantenimiento</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Documenta el mantenimiento realizado</p>
      </div>

      <!-- Formulario -->
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          
          <!-- Tipo de mantenimiento -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
              Tipo de Mantenimiento *
            </label>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
              <button
                type="button"
                @click="cambiarTipo('componente')"
                class="p-4 rounded-lg border-2 transition"
                :class="tipoMantenimiento === 'componente' 
                  ? 'border-blue-500 bg-blue-50' 
                  : 'border-gray-300 hover:border-blue-300'"
              >
                <div class="flex items-center justify-center gap-2">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6" :class="tipoMantenimiento === 'componente' ? 'text-blue-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                  </svg>
                  <span class="text-sm sm:text-base font-semibold" :class="tipoMantenimiento === 'componente' ? 'text-blue-900' : 'text-gray-600'">
                    Componente
                  </span>
                </div>
                <p class="text-xs mt-2" :class="tipoMantenimiento === 'componente' ? 'text-blue-700' : 'text-gray-500'">
                  Inyector o Bomba específica
                </p>
              </button>

              <button
                type="button"
                @click="cambiarTipo('banco')"
                class="p-4 rounded-lg border-2 transition"
                :class="tipoMantenimiento === 'banco' 
                  ? 'border-purple-500 bg-purple-50' 
                  : 'border-gray-300 hover:border-purple-300'"
              >
                <div class="flex items-center justify-center gap-2">
                  <svg class="w-5 h-5 sm:w-6 sm:h-6" :class="tipoMantenimiento === 'banco' ? 'text-purple-600' : 'text-gray-400'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                  </svg>
                  <span class="text-sm sm:text-base font-semibold" :class="tipoMantenimiento === 'banco' ? 'text-purple-900' : 'text-gray-600'">
                    Banco de Pruebas
                  </span>
                </div>
                <p class="text-xs mt-2" :class="tipoMantenimiento === 'banco' ? 'text-purple-700' : 'text-gray-500'">
                  Equipo del laboratorio
                </p>
              </button>
            </div>
          </div>

          <!-- Fecha -->
          <div>
            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">
              Fecha del Mantenimiento *
            </label>
            <input
              id="fecha"
              v-model="form.fecha"
              type="date"
              required
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
            />
            <p v-if="form.errors.fecha" class="mt-1 text-sm text-red-600">
              {{ form.errors.fecha }}
            </p>
          </div>

          <!-- Seleccionar Componente (solo si tipo es 'componente') -->
          <div v-if="tipoMantenimiento === 'componente'">
            <label for="id_componente" class="block text-sm font-medium text-gray-700 mb-2">
              Componente *
            </label>
            <select
              id="id_componente"
              v-model="form.id_componente"
              required
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
            >
              <option :value="null" disabled>Selecciona un componente</option>
              <option v-for="componente in componentes" :key="componente.id_componente" :value="componente.id_componente">
                {{ componente.nombre }} - {{ componente.numero_serie }} ({{ componente.tipo }})
              </option>
            </select>
            <p v-if="form.errors.id_componente" class="mt-1 text-sm text-red-600">
              {{ form.errors.id_componente }}
            </p>
          </div>

          <!-- Descripción -->
          <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
              Descripción Breve *
            </label>
            <input
              id="descripcion"
              v-model="form.descripcion"
              type="text"
              required
              maxlength="255"
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
              placeholder="Ej: Calibración y ajuste de presión"
            />
            <p v-if="form.errors.descripcion" class="mt-1 text-sm text-red-600">
              {{ form.errors.descripcion }}
            </p>
          </div>

          <!-- Notas Técnicas -->
          <div>
            <label for="notas_tecnicas" class="block text-sm font-medium text-gray-700 mb-2">
              Notas Técnicas
            </label>
            <textarea
              id="notas_tecnicas"
              v-model="form.notas_tecnicas"
              rows="8"
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono transition-fast"
              placeholder="Banco usado: Banco A&#10;Procedimiento:&#10;- Paso 1&#10;- Paso 2&#10;&#10;Observaciones:&#10;- Observación 1&#10;&#10;Próximo mantenimiento: [fecha estimada]"
            ></textarea>
            <p class="mt-1 text-xs sm:text-sm text-gray-500">
              Incluye: banco usado, procedimiento, observaciones, mediciones, etc.
            </p>
          </div>

          <!-- Botones Responsive -->
          <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full sm:w-auto bg-green-600 text-white px-6 sm:px-8 py-3 rounded-lg hover:bg-green-700 transition disabled:opacity-50 font-semibold"
            >
              <span class="flex items-center justify-center gap-2">
                <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Guardando...' : 'Guardar Mantenimiento' }}
              </span>
            </button>
            <Link
              href="/user/mantenimientos/calendario"
              class="w-full sm:w-auto bg-gray-300 text-gray-700 px-6 sm:px-8 py-2 rounded-lg hover:bg-gray-400 transition font-semibold inline-flex items-center justify-center"
            >
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </div>
  </UserLayout>
</template>