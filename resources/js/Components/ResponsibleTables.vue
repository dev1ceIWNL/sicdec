<script setup lang="ts">
defineProps<{
  headers: string[];
}>();
</script>

<template>
  <div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Vista Desktop (pantallas grandes) -->
    <div class="hidden lg:block overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th
              v-for="(header, index) in headers"
              :key="index"
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              {{ header }}
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <slot name="desktop" />
        </tbody>
      </table>
    </div>

    <!-- Vista Tablet/Móvil (cards) -->
    <div class="lg:hidden divide-y divide-gray-200">
      <slot name="mobile" />
    </div>
  </div>
</template>