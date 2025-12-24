<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from '@/composables/useToast';

interface Usuario {
  id_usuario: number;
  nombre: string;
  correo: string;
  rol: 'admin' | 'usuario';
  estatus: number;
}

defineProps<{
  usuarios: Usuario[];
}>();

const { success, error } = useToast();

const confirmDelete = (id: number, nombre: string) => {
  if (confirm(`¿Estás seguro de desactivar al usuario "${nombre}"?`)) {
    useForm({}).delete(`/admin/usuarios/${id}`, {
      onSuccess: () => {
        success('¡Usuario desactivado!', 'El usuario ha sido desactivado correctamente');
      },
      onError: () => {
        error('Error al desactivar', 'No se pudo desactivar el usuario');
      }
    });
  }
};
</script>

<template>
  <AdminLayout>
    <Head title="Gestión de Usuarios" />

    <div>
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Gestión de Usuarios</h1>
          <p class="text-gray-600 mt-1">Administra los usuarios del sistema</p>
        </div>
        <Link
          href="/admin/usuarios/crear"
          class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-smooth flex items-center gap-2 font-semibold hover-lift"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Nuevo Usuario
        </Link>
      </div>

      <!-- Tabla de usuarios -->
      <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  ID
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nombre
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Correo
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Rol
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
              <tr v-if="usuarios.length === 0">
                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                  No hay usuarios registrados. 
                  <Link href="/admin/usuarios/crear" class="text-green-600 hover:underline font-medium">
                    Crear el primero
                  </Link>
                </td>
              </tr>
              <tr v-for="usuario in usuarios" :key="usuario.id_usuario" class="hover:bg-gray-50 transition-fast">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ usuario.id_usuario }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ usuario.nombre }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ usuario.correo }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span 
                    class="px-3 py-1 rounded-full text-xs font-semibold"
                    :class="usuario.rol === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'"
                  >
                    {{ usuario.rol === 'admin' ? 'Administrador' : 'Usuario' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                  <span 
                    class="px-2 py-1 rounded-full text-xs font-semibold"
                    :class="usuario.estatus === 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ usuario.estatus === 1 ? 'Activo' : 'Inactivo' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                  <Link
                    :href="`/admin/usuarios/${usuario.id_usuario}`"
                    class="text-blue-600 hover:text-blue-900 transition-fast"
                  >
                    Editar
                  </Link>
                  <button
                    v-if="usuario.estatus === 1"
                    @click="confirmDelete(usuario.id_usuario, usuario.nombre)"
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