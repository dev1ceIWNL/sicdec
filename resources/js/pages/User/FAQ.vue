<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import { ref } from 'vue';

interface FAQItem {
  id: number;
  pregunta: string;
  respuesta: string;
  categoria: string;
}

const categorias = ['General', 'Mantenimientos', 'Componentes', 'Chatbot'];

const faqs: FAQItem[] = [
  {
    id: 1,
    pregunta: '¿Cómo registro un mantenimiento?',
    respuesta: 'Desde el inicio, selecciona "Registrar" o accede al "Calendario" y haz clic en el día deseado. Selecciona si el mantenimiento es para un componente específico o para el banco de pruebas. Completa la descripción y las notas técnicas con todos los detalles del procedimiento realizado.',
    categoria: 'Mantenimientos'
  },
  {
    id: 2,
    pregunta: '¿Qué son las notas técnicas?',
    respuesta: 'Las notas técnicas son el registro detallado del mantenimiento. Debes incluir: banco usado, procedimiento paso a paso, observaciones, mediciones tomadas, y cualquier detalle relevante. Esto ayuda al seguimiento y es revisado por los administradores.',
    categoria: 'Mantenimientos'
  },
  {
    id: 3,
    pregunta: '¿Cómo busco el manual de un componente?',
    respuesta: 'Ve a "Búsqueda" desde el inicio. Puedes buscar por número de serie, nombre o filtrar por tipo (inyector/bomba). Al seleccionar un componente, si tiene manual PDF disponible, verás un botón para descargarlo o visualizarlo.',
    categoria: 'Componentes'
  },
  {
    id: 4,
    pregunta: '¿Qué es un mantenimiento de banco?',
    respuesta: 'Los mantenimientos de banco son aquellos realizados directamente al equipo del laboratorio (banco de pruebas) en lugar de a un componente específico. Al registrar, simplemente selecciona "Banco de Pruebas" en lugar de elegir un componente.',
    categoria: 'Mantenimientos'
  },
  {
    id: 5,
    pregunta: '¿Cómo uso el Asistente IA?',
    respuesta: 'El Asistente IA puede ayudarte con procedimientos de calibración, dudas técnicas y consultas sobre componentes. Simplemente escribe tu pregunta de forma clara. Puedes preguntar sobre procedimientos específicos, valores de calibración, o troubleshooting general.',
    categoria: 'Chatbot'
  },
  {
    id: 6,
    pregunta: '¿Puedo ver mantenimientos anteriores?',
    respuesta: 'Sí, desde el "Calendario" puedes ver todos los mantenimientos registrados. Haz clic en cualquier día para ver los detalles completos, incluyendo quién lo realizó, las notas técnicas y el estado de validación.',
    categoria: 'Mantenimientos'
  },
  {
    id: 7,
    pregunta: '¿Qué significa el estado de validación?',
    respuesta: 'Cada mantenimiento tiene tres estados: Pendiente (esperando revisión del administrador), Aprobado (verificado y aceptado), o Rechazado (requiere corrección). Recibirás notificaciones sobre el estado de tus registros.',
    categoria: 'Mantenimientos'
  },
  {
    id: 8,
    pregunta: '¿Cómo cambio mi contraseña?',
    respuesta: 'Actualmente esta función está en desarrollo. Próximamente podrás cambiar tu contraseña desde tu perfil. Por ahora, contacta a un administrador si necesitas cambiarla.',
    categoria: 'General'
  },
  {
    id: 9,
    pregunta: '¿El Asistente IA tiene acceso a los manuales?',
    respuesta: 'El Asistente IA está entrenado con conocimiento general sobre mantenimiento de sistemas diesel. Para consultas específicas de un componente, es mejor descargar y consultar directamente su manual PDF desde la sección de búsqueda.',
    categoria: 'Chatbot'
  }
];

const categoriaSeleccionada = ref<string>('General');
const expandido = ref<number[]>([]);

const faqsFiltrados = ref(faqs.filter(f => f.categoria === categoriaSeleccionada.value));

const cambiarCategoria = (categoria: string) => {
  categoriaSeleccionada.value = categoria;
  faqsFiltrados.value = faqs.filter(f => f.categoria === categoria);
  expandido.value = [];
};

const toggleFAQ = (id: number) => {
  const index = expandido.value.indexOf(id);
  if (index > -1) {
    expandido.value.splice(index, 1);
  } else {
    expandido.value.push(id);
  }
};
</script>

<template>
  <UserLayout>
    <Head title="Ayuda / FAQ" />

    <div>
       <Breadcrumb :items="[
        { label: 'Centro de Ayuda' }
      ]" />
      <!-- Header -->
      <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Centro de Ayuda</h1>
        <p class="text-gray-600 mt-1">Preguntas frecuentes y guías del sistema</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar de categorías -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow-lg p-4 sticky top-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Categorías</h3>
            <nav class="space-y-2">
              <button
                v-for="categoria in categorias"
                :key="categoria"
                @click="cambiarCategoria(categoria)"
                class="w-full text-left px-4 py-3 rounded-lg transition-smooth"
                :class="categoriaSeleccionada === categoria 
                  ? 'bg-blue-100 text-blue-800 font-semibold' 
                  : 'text-gray-700 hover:bg-gray-100'"
              >
                {{ categoria }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Contenido FAQ -->
        <div class="lg:col-span-3">
          <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">
              {{ categoriaSeleccionada }}
            </h2>

            <div class="space-y-4">
              <div
                v-for="faq in faqsFiltrados"
                :key="faq.id"
                class="border border-gray-200 rounded-lg overflow-hidden transition-smooth hover:shadow-md"
              >
                <button
                  @click="toggleFAQ(faq.id)"
                  class="w-full flex items-center justify-between p-4 text-left hover:bg-gray-50 transition-fast"
                >
                  <span class="font-semibold text-gray-800 pr-4">{{ faq.pregunta }}</span>
                  <svg
                    class="w-5 h-5 text-gray-600 flex-shrink-0 transition-transform"
                    :class="{ 'rotate-180': expandido.includes(faq.id) }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>

                <Transition name="slide-up">
                  <div v-if="expandido.includes(faq.id)" class="px-4 pb-4 pt-2 bg-gray-50">
                    <p class="text-gray-700 leading-relaxed">{{ faq.respuesta }}</p>
                  </div>
                </Transition>
              </div>
            </div>

            <!-- Mensaje si no hay FAQs -->
            <div v-if="faqsFiltrados.length === 0" class="text-center py-12">
              <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <p class="text-gray-500">No hay preguntas en esta categoría aún.</p>
            </div>
          </div>

          <!-- Contacto adicional -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">¿Necesitas ayuda adicional?</h3>
            <p class="text-blue-800 mb-4">
              Si tienes dudas que no están aquí, contacta a un administrador o usa el Asistente IA para consultas técnicas.
            </p>
            <div class="flex items-center gap-2 text-blue-700">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span class="font-medium">soporte@energycontrol.com</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>