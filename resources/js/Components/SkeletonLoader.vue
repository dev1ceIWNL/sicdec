<script setup lang="ts">
interface Props {
  type?: 'text' | 'card' | 'table' | 'avatar';
  rows?: number;
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  rows: 3,
});
</script>

<template>
  <!-- Skeleton para texto -->
  <div v-if="type === 'text'" class="space-y-3">
    <div
      v-for="i in rows"
      :key="i"
      class="h-4 bg-gray-200 rounded animate-pulse"
      :style="{ width: i === rows ? '60%' : '100%' }"
    ></div>
  </div>

  <!-- Skeleton para tarjeta -->
  <div v-else-if="type === 'card'" class="bg-white rounded-lg shadow-lg p-6 space-y-4">
    <div class="h-6 bg-gray-200 rounded animate-pulse w-3/4"></div>
    <div class="space-y-2">
      <div class="h-4 bg-gray-200 rounded animate-pulse"></div>
      <div class="h-4 bg-gray-200 rounded animate-pulse w-5/6"></div>
    </div>
    <div class="h-10 bg-gray-200 rounded animate-pulse w-1/3"></div>
  </div>

  <!-- Skeleton para tabla -->
  <div v-else-if="type === 'table'" class="space-y-2">
    <div
      v-for="i in rows"
      :key="i"
      class="h-12 bg-gray-200 rounded animate-pulse"
    ></div>
  </div>

  <!-- Skeleton para avatar -->
  <div v-else-if="type === 'avatar'" class="flex items-center gap-3">
    <div class="w-12 h-12 bg-gray-200 rounded-full animate-pulse"></div>
    <div class="space-y-2 flex-1">
      <div class="h-4 bg-gray-200 rounded animate-pulse w-1/4"></div>
      <div class="h-3 bg-gray-200 rounded animate-pulse w-1/3"></div>
    </div>
  </div>
</template>