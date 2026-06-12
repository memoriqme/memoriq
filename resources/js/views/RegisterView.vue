<template>
  <div class="auth-page">
    <app-brand to="/" class="auth-brand" />

    <section class="auth-card">
      <p class="eyebrow">Create account</p>
      <h1>Start your Memoriq vault</h1>

      <form @submit.prevent="submit">
        <label class="form-field">
          Email
          <input v-model="form.email" type="email" autocomplete="email" required />
        </label>

        <label class="form-field">
          Password
          <input v-model="form.password" type="password" autocomplete="new-password" required />
        </label>

        <label class="form-field">
          Confirm password
          <input v-model="form.password_confirmation" type="password" autocomplete="new-password" required />
        </label>

        <label class="check-field">
          <input v-model="form.newsletter" type="checkbox" />
          Product updates by email
        </label>

        <div v-if="errorMessage" class="form-alert">{{ errorMessage }}</div>

        <button class="button button-primary full-width" type="submit" :disabled="processing">
          {{ processing ? 'Creating account...' : 'Create account' }}
        </button>
      </form>

      <div class="auth-links">
        <router-link to="/login">Already have an account?</router-link>
      </div>
    </section>
  </div>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, reactive, ref } from 'vue';
import { applyTheme, getStoredTheme } from '../memoriq/theme';
import AppBrand from '../components/AppBrand.vue';

const processing = ref(false);
const errors = ref(null);
const form = reactive({
  email: '',
  password: '',
  password_confirmation: '',
  terms: true,
  newsletter: false,
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
    await axios.post('/register', form);
    window.location = '/email/verify';
  } catch (error) {
    errors.value = error.response?.data?.errors || { email: [error.response?.data?.message || 'Unable to create account.'] };
  } finally {
    processing.value = false;
  }
}
</script>
