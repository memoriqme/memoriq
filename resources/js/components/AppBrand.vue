<template>
  <router-link :to="to" class="brand">
    <memoriq-logo />
    <span>Memoriq</span>
  </router-link>
</template>

<script setup>
import MemoriqLogo from './MemoriqLogo.vue';

defineProps({
  to: {
    type: String,
    default: '/dashboard',
  },
});
</script>
