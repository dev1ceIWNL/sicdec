<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useToast } from '@/composables/useToast';

const { success, error } = useToast();

const form = useForm({
  nombre: '',
  correo: '',
  contrasena: '',
  rol: 'usuario',
});

const submit = () => {
  form.post('/admin/usuarios', {
    onSuccess: () => {
      success('¡Usuario creado!', 'El usuario ha sido registrado correctamente');
    },
    onError: () => {
      error('Error al crear', 'Por favor, verifica los datos e intenta de nuevo');
    }
  });
};
</script>

<template>
  <AdminLayout>
    <Head title="Crear Usuario" />

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
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Nuevo Usuario</h1>
        <p class="text-sm sm:text-base text-gray-600 mt-1">Registra un nuevo usuario del sistema</p>
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
              placeholder="Ej: Juan Pérez González"
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
              placeholder="usuario@ejemplo.com"
            />
            <p v-if="form.errors.correo" class="mt-1 text-sm text-red-600">
              {{ form.errors.correo }}
            </p>
          </div>

          <!-- Contraseña y Rol en grid responsive -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
            <!-- Contraseña -->
            <div>
              <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
                Contraseña *
              </label>
              <input
                id="contrasena"
                v-model="form.contrasena"
                type="password"
                required
                minlength="6"
                class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-fast"
                placeholder="Mínimo 6 caracteres"
              />
              <p v-if="form.errors.contrasena" class="mt-1 text-sm text-red-600">
                {{ form.errors.contrasena }}
              </p>
            </div>

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
          </div>

          <p class="text-xs sm:text-sm text-gray-500">
            Los administradores tienen acceso completo al sistema
          </p>

          <!-- Botones Responsive -->
          <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full sm:w-auto bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition disabled:opacity-50 font-semibold"
            >
              <span class="flex items-center justify-center gap-2">
                <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ form.processing ? 'Guardando...' : 'Guardar Usuario' }}
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