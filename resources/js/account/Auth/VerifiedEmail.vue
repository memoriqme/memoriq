<template>
  <div class="auth-page">
    <app-brand to="/" class="auth-brand" />

    <section class="auth-card">
      <p class="eyebrow">Email verified</p>
      <h1>Your account is ready</h1>
      <p class="auth-subtitle">Thanks for verifying your email. You can now continue to Memoriq.</p>
      <router-link :to="auth.isLoggedIn ? '/dashboard' : '/login'" class="button button-primary full-width">
        {{ auth.isLoggedIn ? 'Go to dashboard' : 'Log in' }}
      </router-link>
    </section>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { applyTheme, getStoredTheme } from '../../memoriq/theme';
import AppBrand from '../../components/AppBrand.vue';
import { useAuthStore } from '../../stores/authStore';

const auth = useAuthStore();

onMounted(() => applyTheme(getStoredTheme()));
</script>
