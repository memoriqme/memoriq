// resources/js/app.js

import { createApp } from 'vue';
import { createPinia } from 'pinia'; // 1. Import Pinia
import { createRouter, createWebHistory } from 'vue-router';
import routes from './routes.js';
import App from './App.vue';
import { useAuthStore } from './stores/authStore'; // 2. Import your new auth store

// --- Axios Setup (No changes needed) ---
import axios from 'axios';
window.axios = axios;
window.axios.defaults.withCredentials = true;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

// --- Vue Router Setup ---
const router = createRouter({
    history: createWebHistory(),
    routes: routes.routes,
    scrollBehavior(to, from, savedPosition) {
        if (to.hash) {
            return {
                el: to.hash,
                behavior: 'smooth',
                top: 80,
            };
        }
        if (savedPosition) {
            return savedPosition;
        }
        return { top: 0 };
    },
});

router.beforeEach(async (to, from, next) => {
    const auth = useAuthStore();

    if (auth.user === null) {
        await auth.fetchUser();
    }

    if (auth.isLoggedIn && !auth.user.email_verified_at && to.meta.requiresAuth && to.name !== 'EmailVerify') {
        return next({ name: 'EmailVerify' });
    }

    if (to.meta.requiresAuth && !auth.isLoggedIn) {
        return next({ name: 'Login', query: { redirect: to.fullPath } });
    }

    if (auth.isLoggedIn && (to.meta.guestOnly || to.name === 'Home')) {
        return next({ name: 'Dashboard' });
    }

    if (to.meta && to.meta.title) {
        document.title = to.meta.title;
    }
    
    next();
});

// --- Vue App Initialization ---
const pinia = createPinia(); // 4. Create a Pinia instance
const app = createApp(App);

app.use(pinia); // 5. Use Pinia
app.use(router);
app.mount('#app');

if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
  });
}