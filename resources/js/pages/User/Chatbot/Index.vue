<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import { ref, nextTick } from 'vue';
import LoadingSpinner from '@/Components/LoadingSpinner.vue';

interface Message {
  role: 'user' | 'assistant';
  content: string;
  timestamp: Date;
}

const messages = ref<Message[]>([
  {
    role: 'assistant',
    content: '¡Hola! Soy tu asistente técnico de SICDEC. Puedo ayudarte con:\n\n• Procedimientos de mantenimiento de inyectores y bombas diesel\n• Diagnóstico de problemas comunes\n• Información sobre componentes del sistema\n• Recomendaciones de mantenimiento preventivo\n\n¿En qué puedo ayudarte hoy?',
    timestamp: new Date()
  }
]);

const userInput = ref('');
const isLoading = ref(false);
const chatContainer = ref<HTMLElement | null>(null);
const errorMessage = ref('');

const scrollToBottom = () => {
  nextTick(() => {
    if (chatContainer.value) {
      chatContainer.value.scrollTop = chatContainer.value.scrollHeight;
    }
  });
};

const sendMessage = async () => {
  if (!userInput.value.trim() || isLoading.value) return;

  const messageText = userInput.value.trim();
  userInput.value = '';
  errorMessage.value = '';

  // Agregar mensaje del usuario
  messages.value.push({
    role: 'user',
    content: messageText,
    timestamp: new Date()
  });

  scrollToBottom();
  isLoading.value = true;

  try {
    // Preparar historial (excluir mensaje de bienvenida y el último mensaje del usuario)
    const history = messages.value.slice(1, -1).map(msg => ({
      role: msg.role,
      content: msg.content
    }));

    // Obtener CSRF token correctamente
    const metaTag = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = metaTag?.getAttribute('content') || '';

    if (!csrfToken) {
      throw new Error('CSRF token no encontrado. Recarga la página.');
    }

    const response = await fetch('/user/chatbot/chat', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'same-origin',
      body: JSON.stringify({
        message: messageText,
        history: history
      })
    });

    if (!response.ok) {
      if (response.status === 419) {
        throw new Error('Sesión expirada. Por favor, recarga la página.');
      }
      throw new Error(`Error del servidor: ${response.status}`);
    }

    const data = await response.json();

    if (data.success) {
      messages.value.push({
        role: 'assistant',
        content: data.message,
        timestamp: new Date()
      });
    } else {
      throw new Error(data.message || 'Error al procesar la respuesta.');
    }
  } catch (error) {
    console.error('Error en chatbot:', error);
    const errorMsg = error instanceof Error ? error.message : 'Error desconocido';
    errorMessage.value = errorMsg;
    
    messages.value.push({
      role: 'assistant',
      content: `❌ ${errorMsg}`,
      timestamp: new Date()
    });
  } finally {
    isLoading.value = false;
    scrollToBottom();
  }
};

const clearChat = () => {
  if (confirm('¿Estás seguro de que quieres limpiar la conversación?')) {
    messages.value = [
      {
        role: 'assistant',
        content: '¡Hola! Soy tu asistente técnico de SICDEC. ¿En qué puedo ayudarte?',
        timestamp: new Date()
      }
    ];
    errorMessage.value = '';
  }
};

const handleKeyDown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' && !event.shiftKey) {
    event.preventDefault();
    sendMessage();
  }
};

// Ejemplos de preguntas sugeridas
const exampleQuestions = [
  '¿Cómo calibro un inyector Bosch?',
  '¿Qué componentes necesitan mantenimiento?',
  '¿Cuáles son los pasos para limpiar una bomba diesel?',
  'Dame consejos de mantenimiento preventivo',
];

const askExample = (question: string) => {
  userInput.value = question;
  sendMessage();
};

const formatTime = (date: Date) => {
  return date.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });
};
</script>

<template>
  <UserLayout>
    <Head title="Asistente IA - SICDEC" />

    <div class="h-[calc(100vh-8rem)] flex flex-col">
      <!-- Header -->
      <div class="bg-white rounded-t-lg shadow-lg p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 flex items-center gap-3">
            <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-full p-2 sm:p-3">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
              </svg>
            </div>
            Asistente Técnico IA
          </h1>
          <p class="text-sm sm:text-base text-gray-600 mt-1">Experto en mantenimiento de sistemas diesel</p>
        </div>
        <button
          @click="clearChat"
          class="w-full sm:w-auto bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-semibold flex items-center justify-center gap-2"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
          </svg>
          Limpiar
        </button>
      </div>

      <!-- Chat Area -->
      <div class="flex-1 bg-gray-50 overflow-hidden flex flex-col">
        <!-- Messages -->
        <div ref="chatContainer" class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4">
          <div
            v-for="(message, index) in messages"
            :key="index"
            class="flex"
            :class="message.role === 'user' ? 'justify-end' : 'justify-start'"
          >
            <div
              class="max-w-full sm:max-w-3xl rounded-lg p-3 sm:p-4 shadow-sm"
              :class="message.role === 'user' 
                ? 'bg-blue-600 text-white' 
                : 'bg-white text-gray-800 border border-gray-200'"
            >
              <!-- Avatar y nombre -->
              <div class="flex items-center gap-2 mb-2">
                <div
                  class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-semibold flex-shrink-0"
                  :class="message.role === 'user' 
                    ? 'bg-blue-700 text-white' 
                    : 'bg-orange-100 text-orange-600'"
                >
                  {{ message.role === 'user' ? 'TÚ' : '🤖' }}
                </div>
                <span class="text-xs sm:text-sm font-semibold">
                  {{ message.role === 'user' ? 'Tú' : 'Asistente' }}
                </span>
                <span class="text-xs opacity-70">{{ formatTime(message.timestamp) }}</span>
              </div>

              <!-- Contenido del mensaje -->
              <div class="prose prose-sm max-w-none" :class="message.role === 'user' ? 'prose-invert' : ''">
                <div class="whitespace-pre-wrap text-sm sm:text-base">{{ message.content }}</div>
              </div>
            </div>
          </div>
          
          <!-- Loading indicator -->
          <div v-if="isLoading" class="flex justify-start">
            <div class="bg-white rounded-lg p-4 shadow-sm border border-gray-200">
              <LoadingSpinner size="sm" color="orange" text="Pensando..." />
            </div>
          </div>

          <!-- Preguntas de ejemplo (solo si no hay más mensajes) -->
          <div v-if="messages.length === 1 && !isLoading" class="space-y-3">
            <p class="text-center text-gray-500 text-sm font-medium">Preguntas sugeridas:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
              <button
                v-for="(question, index) in exampleQuestions"
                :key="index"
                @click="askExample(question)"
                class="bg-white border-2 border-gray-200 rounded-lg p-3 text-left hover:border-orange-400 hover:bg-orange-50 transition text-sm text-gray-700"
              >
                <svg class="w-4 h-4 text-orange-500 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ question }}
              </button>
            </div>
          </div>
        </div>

        <!-- Error message -->
        <Transition name="fade">
          <div v-if="errorMessage" class="px-4 sm:px-6 py-2">
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs sm:text-sm text-red-700 flex items-start gap-2">
              <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ errorMessage }}</span>
            </div>
          </div>
        </Transition>

        <!-- Input Area -->
        <div class="bg-white border-t border-gray-200 p-4 sm:p-6">
          <div class="max-w-4xl mx-auto">
            <div class="flex flex-col sm:flex-row gap-3">
              <textarea
                v-model="userInput"
                @keydown="handleKeyDown"
                :disabled="isLoading"
                placeholder="Escribe tu pregunta aquí... (Enter para enviar, Shift+Enter para nueva línea)"
                rows="2"
                class="flex-1 px-4 py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent resize-none disabled:bg-gray-100 disabled:cursor-not-allowed"
              ></textarea>
              <button
                @click="sendMessage"
                :disabled="!userInput.trim() || isLoading"
                class="w-full sm:w-auto bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700 transition disabled:opacity-50 disabled:cursor-not-allowed font-semibold flex items-center justify-center gap-2"
              >
                <svg v-if="!isLoading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <div v-else class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <span class="hidden sm:inline">{{ isLoading ? 'Enviando...' : 'Enviar' }}</span>
                <span class="sm:hidden">{{ isLoading ? '...' : 'Enviar' }}</span>
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-2 text-center">
              El asistente puede cometer errores. Verifica información importante.
            </p>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>

<style scoped>
.prose {
  max-width: none;
}

.prose-invert {
  color: white;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>