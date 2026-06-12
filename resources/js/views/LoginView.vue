<template>
  <div class="auth-page">
    <app-brand to="/" class="auth-brand" />

    <section class="auth-card">
      <p class="eyebrow">Welcome back</p>
      <h1>Log in to your AI memory</h1>
      <p class="auth-subtitle">Use your existing Memoriq account to open the dashboard preview.</p>

      <form @submit.prevent="submit">
        <label class="form-field">
          Email
          <input v-model="form.email" type="email" autocomplete="email" required autofocus />
        </label>

        <label class="form-field">
          Password
          <input v-model="form.password" type="password" autocomplete="current-password" required />
        </label>

        <label class="check-field">
          <input v-model="form.remember" type="checkbox" />
          Remember me
        </label>

        <div v-if="errorMessage" class="form-alert">{{ errorMessage }}</div>

        <button class="button button-primary full-width" type="submit" :disabled="processing">
          {{ processing ? 'Logging in...' : 'Log in' }}
        </button>
      </form>

      <div class="auth-links">
        <router-link to="/forgot-password">Forgot password?</router-link>
        <router-link to="/register">Create an account</router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { applyTheme, getStoredTheme } from '../memoriq/theme';
import AppBrand from '../components/AppBrand.vue';
import { useAuthStore } from '../stores/authStore';

const router = useRouter();
const auth = useAuthStore();
const processing = ref(false);
const errors = ref(null);
const form = reactive({
  email: '',
  password: '',
  remember: true,
});

const errorMessage = computed(() => {
  if (!errors.value) return '';
  const first = Object.values(errors.value)[0];
  return Array.isArray(first) ? first[0] : first;
});

onMounted(() => applyTheme(getStoredTheme()));

async function submit() {
  processing.value = true;
  errors.value = null;

  try {
    const response = await axios.post('/login', form);
    if (response.statusText === 'OK' || response.status === 204 || response.status === 200) {
      await auth.fetchUser();
      const redirect = router.currentRoute.value.query.redirect;
      window.location = redirect || '/dashboard';
      return;
    }
  } catch (error) {
    if (error.response?.data?.message === 'Too Many Attempts.') {
      errors.value = { email: ['Too many attempts, please wait.'] };
    } else {
      errors.value = error.response?.data?.errors || { email: [error.response?.data?.message || 'Unable to log in.'] };
    }
  } finally {
    processing.value = false;
  }
}
</script>
