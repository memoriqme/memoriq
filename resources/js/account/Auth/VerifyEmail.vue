<template>
  <div class="auth-page">
    <app-brand to="/" class="auth-brand" />

    <section class="auth-card">
      <p class="eyebrow">Email verification</p>
      <h1>Verify your email</h1>

      <template v-if="auth.isVerified">
        <p class="auth-subtitle">Your email has been verified. You can now open the dashboard.</p>
        <router-link to="/dashboard" class="button button-primary full-width">Go to dashboard</router-link>
      </template>

      <template v-else>
        <p class="auth-subtitle">
          We sent a verification link to {{ auth.user?.email }}. Click it to finish setting up your account.
        </p>

        <div v-if="status" class="form-success">{{ status }}</div>
        <div v-if="errors" class="form-alert">Too many requests. Please wait before trying again.</div>

        <form @submit.prevent="submit">
          <button class="button button-primary full-width" type="submit" :disabled="processing">
            {{ processing ? 'Sending...' : 'Resend verification email' }}
          </button>
        </form>

        <button class="text-button full-width logout-link" type="button" @click="logout">Log out</button>
      </template>
    </section>
  </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { applyTheme, getStoredTheme } from '../../memoriq/theme';
import AppBrand from '../../components/AppBrand.vue';
import { useAuthStore } from '../../stores/authStore';

const auth = useAuthStore();
const router = useRouter();
const processing = ref(false);
const status = ref('');
const errors = ref(false);

onMounted(() => applyTheme(getStoredTheme()));

async function submit() {
  processing.value = true;
  status.value = '';
  errors.value = false;

  try {
    await axios.post('/email/verification-notification');
    status.value = 'A new verification link has been sent.';
  } catch (error) {
    errors.value = true;
  } finally {
    processing.value = false;
  }
}

async function logout() {
  await axios.post('/logout');
  auth.user = null;
  router.push({ name: 'Home' });
}
</script>
