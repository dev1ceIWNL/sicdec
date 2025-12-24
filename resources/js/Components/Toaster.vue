<script setup lang="ts">
import { Toaster as SonnerToaster } from 'vue-sonner';
</script>

<template>
  <SonnerToaster 
    position="top-right"
    :duration="4000"
    :expand="true"
    rich-colors
    close-button
    :toast-options="{
      style: {
        fontSize: '14px',
        padding: '16px',
      },
    }"
  />
</template>

<style>
.custom-toast {
  border-radius: 8px !important;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
  min-width: 350px;
}

/* Colores personalizados */
[data-sonner-toast][data-type="success"] {
  background: #10b981 !important;
  color: white !important;
  border: none !important;
}

[data-sonner-toast][data-type="error"] {
  background: #ef4444 !important;
  color: white !important;
  border: none !important;
}

[data-sonner-toast][data-type="info"] {
  background: #3b82f6 !important;
  color: white !important;
  border: none !important;
}

[data-sonner-toast][data-type="warning"] {
  background: #f59e0b !important;
  color: white !important;
  border: none !important;
}

/* Botón de cerrar */
[data-sonner-toast] button[data-close-button] {
  background: rgba(255, 255, 255, 0.2) !important;
  color: white !important;
  border: none !important;
  border-radius: 4px !important;
}

[data-sonner-toast] button[data-close-button]:hover {
  background: rgba(255, 255, 255, 0.3) !important;
}

/* Título y descripción */
[data-sonner-toast] [data-title] {
  font-weight: 600;
  font-size: 15px;
  margin-bottom: 4px;
}

[data-sonner-toast] [data-description] {
  opacity: 0.9;
  font-size: 13px;
}

/* Icono */
[data-sonner-toast] [data-icon] {
  width: 20px;
  height: 20px;
}

/* Animación de entrada */
@keyframes slideIn {
  from {
    transform: translateX(100%);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

[data-sonner-toast] {
  animation: slideIn 0.3s ease-out;
}
</style>