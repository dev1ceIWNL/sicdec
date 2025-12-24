<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import type { PageProps } from '@/types';

const page = usePage<PageProps>();
const user = page.props.auth.user;
</script>

<template>
  <UserLayout>
    <Head title="Mi Perfil" />

    <div>
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Mi Perfil</h1>
        <p class="text-gray-600 mt-1">Información de tu cuenta</p>
      </div>

      <!-- Card de Perfil -->
      <div class="bg-white rounded-lg shadow-lg p-8 max-w-2xl">
        <div class="flex items-center gap-6 mb-8">
          <!-- Avatar grande -->
          <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold">
            {{ user.nombre.charAt(0).toUpperCase() }}
          </div>
          
          <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ user.nombre }}</h2>
            <p class="text-gray-600">{{ user.correo }}</p>
            <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
              Técnico
            </span>
          </div>
        </div>

        <!-- Información detallada -->
        <div class="space-y-4">
          <div class="border-t border-gray-200 pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de la Cuenta</h3>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-gray-500">ID de Usuario</p>
                <p class="text-base font-medium text-gray-800">#{{ user.id_usuario }}</p>
              </div>
              
              <div>
                <p class="text-sm text-gray-500">Rol</p>
                <p class="text-base font-medium text-gray-800">Técnico</p>
              </div>
              
              <div>
                <p class="text-sm text-gray-500">Estado</p>
                <span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-sm font-semibold rounded">
                  {{ user.estatus === 1 ? 'Activo' : 'Inactivo' }}
                </span>
              </div>
            </div>
          </div>

          <!-- Sección de acciones (futuro) -->
          <div class="border-t border-gray-200 pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
            <p class="text-sm text-gray-500">
              Próximamente podrás editar tu perfil y cambiar tu contraseña desde aquí.
            </p>
          </div>
        </div>
      </div>
    </div>
    </UserLayout>
</template>