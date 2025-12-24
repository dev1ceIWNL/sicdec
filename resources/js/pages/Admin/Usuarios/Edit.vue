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

const props = defineProps<{
  usuario: Usuario;
}>();

const { success, error } = useToast();

const form = useForm({
  nombre: props.usuario.nombre,
  correo: props.usuario.correo,
  contrasena: '',
  rol: props.usuario.rol,
  estatus: props.usuario.estatus,
});

const submit = () => {
  form.put(`/admin/usuarios/${props.usuario.id_usuario}`, {
    onSuccess: () => {
      success('¡Usuario actualizado!', 'Los cambios se han guardado correctamente');
    },
    onError: () => {
      error('Error al actualizar', 'Por favor, verifica los datos e intenta de nuevo');
    }
  });
};
</script>

<template>
  <AdminLayout>
    <Head title="Editar Usuario" />

    <div>
      <!-- Header -->
      <div class="mb-6">
        <Link
          href="/admin/usuarios"
          class="text-green-600 hover:text-green-800 flex items-center gap-2 mb-4 transition-smooth"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          Volver a la lista
        </Link>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Editar Usuario</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Modifica los datos del usuario #{{ usuario.id_usuario }}</p>
      </div>

      <!-- Formulario -->
      <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:p-8">
        <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">
          <!-- Nombre -->
          <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
              Nombre Completo *
            </label>
            <input
              id="nombre"
              v-model="form.nombre"
              type="text"
              required
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-fast"
            />
            <p v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">
              {{ form.errors.nombre }}
            </p>
          </div>

          <!-- Correo -->
          <div>
            <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
              Correo Electrónico *
            </label>
            <input
              id="correo"
              v-model="form.correo"
              type="email"
              required
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-fast"
            />
            <p v-if="form.errors.correo" class="mt-1 text-sm text-red-600">
              {{ form.errors.correo }}
            </p>
          </div>

          <!-- Contraseña -->
          <div>
            <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
              Nueva Contraseña
            </label>
            <input
              id="contrasena"
              v-model="form.contrasena"
              type="password"
              minlength="6"
              class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-fast"
              placeholder="Dejar vacío para no cambiar"
            />
            <p class="mt-1 text-xs sm:text-sm text-gray-500">
              Solo completa este campo si deseas cambiar la contraseña
            </p>
            <p v-if="form.errors.contrasena" class="mt-1 text-sm text-red-600">
              {{ form.errors.contrasena }}
            </p>
          </div>

          <!-- Rol y Estado en grid responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Rol -->
            <div>
              <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                Rol *
              </label>
              <select
                id="rol"
                v-model="form.rol"
                required
                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-fast"
              >
                <option value="usuario">Usuario</option>
                <option value="admin">Administrador</option>
              </select>
              <p v-if="form.errors.rol" class="mt-1 text-sm text-red-600">
                {{ form.errors.rol }}
              </p>
            </div>

            <!-- Estado -->
            <div>
              <label for="estatus" class="block text-sm font-medium text-gray-700 mb-2">
                Estado *
              </label>
              <select
                id="estatus"
                v-model="form.estatus"
                required
                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-fast"
              >
                <option :value="1">Activo</option>
                <option :value="0">Inactivo</option>
              </select>
              <p v-if="form.errors.estatus" class="mt-1 text-sm text-red-600">
                {{ form.errors.estatus }}
              </p>
            </div>
          </div>

          <!-- Botones Responsive -->
          <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full sm:w-auto bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 font-semibold"
            >
              <span class="flex items-center justify-center gap-2">
                <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Actualizando...' : 'Actualizar Usuario' }}
              </span>
            </button>
            <Link
              href="/admin/usuarios"
              class="w-full sm:w-auto bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition font-semibold inline-flex items-center justify-center"
            >
              Cancelar
            </Link>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>