<template>
  <div class="auth-page">
    <app-brand to="/" class="auth-brand" />

    <section class="auth-card">
      <p class="eyebrow">Password recovery</p>
      <h1>Reset your password</h1>
      <p class="auth-subtitle">Enter your email and we will send a password reset link.</p>

      <form @submit.prevent="submit">
        <label class="form-field">
          Email
          <input v-model="email" type="email" autocomplete="email" required autofocus />
        </label>

        <div v-if="status" class="form-success">{{ status }}</div>
        <div v-if="errorMessage" class="form-alert">{{ errorMessage }}</div>

        <button class="button button-primary full-width" type="submit" :disabled="processing">
          {{ processing ? 'Sending...' : 'Send reset link' }}
        </button>
      </form>

      <div class="auth-links">
        <router-link to="/login">Back to login</router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { applyTheme, getStoredTheme } from '../../memoriq/theme';
import AppBrand from '../../components/AppBrand.vue';

const email = ref('');
const status = ref('');
const errors = ref(null);
const processing = ref(false);

const errorMessage = computed(() => {
  if (!errors.value) return '';
  const first = Object.values(errors.value)[0];
  return Array.isArray(first) ? first[0] : first;
});

onMounted(() => applyTheme(getStoredTheme()));

async function submit() {
  processing.value = true;
  status.value = '';
  errors.value = null;

  try {
    const response = await axios.post('/forgot-password', { email: email.value });
    status.value = response.data.message || 'Password reset link sent.';
  } catch (error) {
    errors.value = error.response?.data?.errors || { email: [error.response?.data?.message || 'Unable to send reset link.'] };
  } finally {
    processing.value = false;
  }
}
</script>
