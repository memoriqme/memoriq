<template>
  <div class="auth-page">
    <app-brand to="/" class="auth-brand" />

    <section class="auth-card">
      <p class="eyebrow">Password reset</p>
      <h1>Create a new password</h1>

      <form @submit.prevent="submit">
        <label class="form-field">
          Email
          <input v-model="form.email" type="email" autocomplete="email" required />
        </label>

        <label class="form-field">
          New password
          <input v-model="form.password" type="password" autocomplete="new-password" required autofocus />
        </label>

        <label class="form-field">
          Confirm password
          <input v-model="form.password_confirmation" type="password" autocomplete="new-password" required />
        </label>

        <div v-if="errorMessage" class="form-alert">{{ errorMessage }}</div>

        <button class="button button-primary full-width" type="submit" :disabled="processing">
          {{ processing ? 'Resetting...' : 'Reset password' }}
        </button>
      </form>
    </section>
  </div>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { applyTheme, getStoredTheme } from '../../memoriq/theme';
import AppBrand from '../../components/AppBrand.vue';

const route = useRoute();
const router = useRouter();
const processing = ref(false);
const errors = ref(null);
const form = reactive({
  token: route.params.token,
  email: route.query.email || '',
  password: '',
  password_confirmation: '',
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
    await axios.post('/reset-password', form);
    router.push({ name: 'Login' });
  } catch (error) {
    errors.value = error.response?.data?.errors || { password: [error.response?.data?.message || 'Unable to reset password.'] };
    form.password = '';
    form.password_confirmation = '';
  } finally {
    processing.value = false;
  }
}
</script>
