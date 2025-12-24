<script setup lang="ts">
interface Props {
  size?: 'sm' | 'md' | 'lg' | 'xl';
  color?: 'blue' | 'white' | 'gray' | 'green' | 'orange' | 'purple';
  text?: string;
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  color: 'blue',
});

const sizeClasses = {
  sm: 'w-4 h-4 border-2',
  md: 'w-8 h-8 border-3',
  lg: 'w-12 h-12 border-4',
  xl: 'w-16 h-16 border-4',
};

const colorClasses = {
  blue: 'border-blue-600',
  white: 'border-white',
  gray: 'border-gray-600',
  green: 'border-green-600',
  orange: 'border-orange-600',
  purple: 'border-purple-600',
};
</script>

<template>
  <div class="flex flex-col items-center justify-center gap-3">
    <div
      class="rounded-full border-t-transparent animate-spin"
      :class="[sizeClasses[size], colorClasses[color]]"
    ></div>
    <p v-if="text" class="text-sm text-gray-600 animate-pulse-subtle">
      {{ text }}
    </p>
  </div>
</template>