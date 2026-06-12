<template>
  <button
    type="button"
    class="hamburger-btn"
    :aria-label="buttonLabel"
    :title="buttonLabel"
    @click="handleClick"
  >
    <svg v-if="isSettingsRoute" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M6 6l12 12M18 6L6 18" />
    </svg>
    <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
      <path d="M4 7h16M4 12h16M4 17h16" />
    </svg>
  </button>
</template>

<script setup>
import { computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const isSettingsRoute = computed(() => route.name === 'Settings');
const buttonLabel = computed(() => (isSettingsRoute.value ? 'Close settings' : 'Open settings'));

function handleClick() {
  if (isSettingsRoute.value) {
    router.push({ name: 'Dashboard' });
    return;
  }

  router.push({ name: 'Settings' });
}
</script>
