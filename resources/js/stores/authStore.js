import { defineStore } from 'pinia';
import axios from 'axios';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    /**
     * The user object. Null if not logged in.
     * @type {object|null}
     */
    user: null,
  }),
  getters: {
    /**
     * Checks if a user is currently logged in.
     * @returns {boolean}
     */
    isLoggedIn: (state) => !!state.user,

    /**
     * Checks if the logged-in user has verified their email.
     * @returns {boolean}
     */
    isVerified: (state) => !!state.user?.email_verified_at,
  },
  actions: {
    /**
     * Fetches the current user from the /api/user endpoint.
     * Sets the user state to null if the request fails (e.g., not authenticated).
     */
    async fetchUser() {
      try {
        const { data } = await axios.get('/api/user');
        this.user = data;
      } catch (error) {
        this.user = null;
      }
    },
  },
});