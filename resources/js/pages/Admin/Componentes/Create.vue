<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from '@/composables/useToast';

const { success, error } = useToast();

const form = useForm({
  numero_serie: '',
  nombre: '',
  descripcion: '',
  tipo: 'inyector',
  marca: '',
  archivo_pdf: null as any,
});

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  if (target.files && target.files[0]) {
    form.archivo_pdf = target.files[0] as any;
  }
};

const submit = () => {
  form.post('/admin/componentes', {
    forceFormData: true,
    onSuccess: () => {
      success('¡Componente creado!', 'El componente se ha registrado correctamente');
    },
    onError: () => {
      error('Error al crear', 'Por favor, verifica los datos e intenta de nuevo');
    }
  });
};
</script>

<template>
  <AdminLayout>
    <Head title="Crear Componente" />

    <div>
      <!-- Header -->
      <div class="mb-6">
        <Link
          href="/admin/componentes"
          class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-4 transition-smooth"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Volver a la lista
        </Link>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Nuevo Componente</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Registra un nuevo inyector o bomba diesel</p>
      </div>

      <!-- Formulario -->
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          <!-- Número de Serie -->
          <div>
            <label for="numero_serie" class="block text-sm font-medium text-gray-700 mb-2">
              Número de Serie *
            </label>
            <input
              id="numero_serie"
              v-model="form.numero_serie"
              type="text"
              required
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
              placeholder="Ej: INY-2024-001"
            />
            <p v-if="form.errors.numero_serie" class="mt-1 text-sm text-red-600">
              {{ form.errors.numero_serie }}
            </p>
          </div>

          <!-- Nombre -->
          <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
              Nombre *
            </label>
            <input
              id="nombre"
              v-model="form.nombre"
              type="text"
              required
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
              placeholder="Ej: Inyector Common Rail"
            />
            <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">
              {{ form.errors.nombre }}
            </p>
          </div>

          <!-- Tipo y Marca en grid responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Tipo -->
            <div>
              <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">
                Tipo *
              </label>
              <select
                id="tipo"
                v-model="form.tipo"
                required
                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
              >
                <option value="inyector">Inyector</option>
                <option value="bomba">Bomba</option>
              </select>
              <p v-if="form.errors.tipo" class="mt-1 text-sm text-red-600">
                {{ form.errors.tipo }}
              </p>
            </div>

            <!-- Marca -->
            <div>
              <label for="marca" class="block text-sm font-medium text-gray-700 mb-2">
                Marca
              </label>
              <input
                id="marca"
                v-model="form.marca"
                type="text"
                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
                placeholder="Ej: Bosch, Delphi, Denso"
              />
              <p v-if="form.errors.marca" class="mt-1 text-sm text-red-600">
                {{ form.errors.marca }}
              </p>
            </div>
          </div>

          <!-- Descripción -->
          <div>
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
              Descripción
            </label>
            <textarea
              id="descripcion"
              v-model="form.descripcion"
              rows="3"
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
              placeholder="Descripción adicional del componente"
            ></textarea>
            <p v-if="form.errors.descripcion" class="mt-1 text-sm text-red-600">
              {{ form.errors.descripcion }}
            </p>
          </div>

          <!-- Archivo PDF -->
          <div>
            <label for="archivo_pdf" class="block text-sm font-medium text-gray-700 mb-2">
              Manual de Despiece y Calibración (PDF)
            </label>
            <input
              id="archivo_pdf"
              type="file"
              accept=".pdf"
              @change="handleFileChange"
              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
            />
            <p class="mt-1 text-xs sm:text-sm text-gray-500">Tamaño máximo: 10MB</p>
            <p v-if="form.errors.archivo_pdf" class="mt-1 text-sm text-red-600">
              {{ form.errors.archivo_pdf }}
            </p>
          </div>

          <!-- Botones Responsive -->
          <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 font-semibold order-1 sm:order-1"
            >
              <span class="flex items-center justify-center gap-2">
                <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Guardando...' : 'Guardar Componente' }}
              </span>
            </button>
            <Link
              href="/admin/componentes"
              class="w-full sm:w-auto bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-semibold inline-flex items-center justify-center order-2 sm:order-2"
            >
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>