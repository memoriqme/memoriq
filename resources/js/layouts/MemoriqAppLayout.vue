<template>
  <div
    class="app-shell"
    :class="{
      'app-shell--settings-scroll': isSettingsRoute,
    }"
  >
    <main class="app-main">
      <div class="app-main-content">
        <slot />
      </div>
    </main>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { applyTheme, getStoredTheme } from '../memoriq/theme';

const route = useRoute();
const isSettingsRoute = computed(() => route.name === 'Settings');

onMounted(() => {
  applyTheme(getStoredTheme());
});
</script>
