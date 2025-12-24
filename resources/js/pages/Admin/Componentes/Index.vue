<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from '@/composables/useToast';

interface Componente {
  id_componente: number;
  numero_serie: string;
  nombre: string;
  descripcion: string;
  tipo: 'inyector' | 'bomba';
  marca: string;
  estatus: number;
  pdf_path: string | null;
}

defineProps<{
  componentes: Componente[];
}>();

const { success, error } = useToast();

const confirmDelete = (id: number, nombre: string) => {
  if (confirm(`¿Estás seguro de desactivar el componente "${nombre}"?`)) {
    useForm({}).delete(`/admin/componentes/${id}`, {
      onSuccess: () => {
        success('¡Componente desactivado!', 'El componente ha sido desactivado correctamente');
      },
      onError: () => {
        error('Error al desactivar', 'No se pudo desactivar el componente');
      }
    });
  }
};
</script>

<template>
  <AdminLayout>
    <Head title="Gestión de Componentes" />

    <div>
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Gestión de Componentes</h1>
          <p class="text-gray-600 mt-1">Administra inyectores y bombas diesel</p>
        </div>
        <Link
          href="/admin/componentes/crear"
          class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-smooth flex items-center gap-2 font-semibold hover-lift"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nuevo Componente
        </Link>
      </div>

      <!-- Tabla de componentes -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Manual PDF
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Número de Serie
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nombre
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tipo
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Marca
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
              <tr v-if="componentes.length === 0">
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                  No hay componentes registrados. 
                  <Link href="/admin/componentes/crear" class="text-blue-600 hover:underline font-medium">
                    Crear el primero
                  </Link>
                </td>
              </tr>
              <tr v-for="componente in componentes" :key="componente.id_componente" class="hover:bg-gray-50 transition-fast">
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <a 
                    v-if="componente.pdf_path" 
                    :href="`/storage/${componente.pdf_path}`" 
                    target="_blank"
                    class="text-red-600 hover:text-red-800 flex items-center gap-1 transition-fast"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    PDF
                  </a>
                  <span v-else class="text-gray-400 text-xs">Sin PDF</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ componente.id_componente }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ componente.numero_serie }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                  {{ componente.nombre }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span 
                    class="px-2 py-1 rounded-full text-xs font-semibold"
                    :class="componente.tipo === 'inyector' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'"
                  >
                    {{ componente.tipo === 'inyector' ? 'Inyector' : 'Bomba' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ componente.marca || 'N/A' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span 
                    class="px-2 py-1 rounded-full text-xs font-semibold"
                    :class="componente.estatus === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ componente.estatus === 1 ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                  <Link
                    :href="`/admin/componentes/${componente.id_componente}`"
                    class="text-blue-600 hover:text-blue-900 transition-fast"
                  >
                    Editar
                  </Link>
                  <button
                    v-if="componente.estatus === 1"
                    @click="confirmDelete(componente.id_componente, componente.nombre)"
                    class="text-red-600 hover:text-red-900 transition-fast"
                  >
                    Desactivar
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>