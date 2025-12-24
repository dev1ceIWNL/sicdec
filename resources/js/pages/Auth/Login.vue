<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast';
import Toaster from '@/Components/Toaster.vue';

const { error: showError } = useToast();

const form = useForm({
  correo: '',
  contrasena: '',
});

const submit = () => {
  form.post('/login', {
    preserveScroll: true,
    onError: () => {
      showError('Error de autenticación', 'Credenciales incorrectas. Por favor, verifica tus datos.');
    }
  });
};
</script>

<template>
  <Head title="Login - Energy Control" />
  
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-800 px-4">
    <Toaster />
    
    <div class="w-full max-w-md">
      <!-- Card de Login -->
      <Transition name="scale">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
          <!-- Logo/Título -->
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Energy & Controls</h1>
            <p class="text-gray-600">SICDEC</p>
          </div>

          <!-- Formulario -->
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Email -->
            <div>
              <label for="correo" class="block text-sm font-medium text-gray-700 mb-2">
                Correo Electrónico
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                  </svg>
                </div>
                <input
                  id="correo"
                  v-model="form.correo"
                  type="email"
                  required
                  class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
                  placeholder="usuario@ejemplo.com"
                />
              </div>
              <p v-if="form.errors.correo" class="mt-2 text-sm text-red-600">
                {{ form.errors.correo }}
              </p>
            </div>

            <!-- Contraseña -->
            <div>
              <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
                Contraseña
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                  </svg>
                </div>
                <input
                  id="contrasena"
                  v-model="form.contrasena"
                  type="password"
                  required
                  class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-fast"
                  placeholder="••••••••"
                />
              </div>
            </div>

            <!-- Botón de Login -->
            <button
              type="submit"
              :disabled="form.processing"
              class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-smooth disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl"
            >
              <span class="flex items-center justify-center gap-2">
                <svg v-if="form.processing" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span v-if="!form.processing">Iniciar Sesión</span>
                <span v-else>Iniciando sesión...</span>
              </span>
            </button>
          </form>

          <!-- Footer -->
          <div class="mt-6 text-center text-sm text-gray-600">
            <p>Sistema Integral de Componentes Diesel para Energy & Controls</p>
          </div>
        </div>
      </Transition>
    </div>
  </div>
</template>