import LandingView from './views/LandingView.vue';
import DashboardView from './views/DashboardView.vue';
import ShareView from './views/ShareView.vue';
import LoginView from './views/LoginView.vue';
import RegisterView from './views/RegisterView.vue';
import PrivacyView from './views/PrivacyView.vue';
import TermsView from './views/TermsView.vue';

import ForgotPassword from './account/Auth/ForgotPassword.vue';
import ResetPassword from './account/Auth/ResetPassword.vue';
import VerifyEmail from './account/Auth/VerifyEmail.vue';
import VerifiedEmail from './account/Auth/VerifiedEmail.vue';

export default {
  routes: [
    {
      path: '/',
      name: 'Home',
      component: LandingView,
      meta: { title: 'Memoriq - Private AI Memory Vault' },
    },
    {
      path: '/privacy',
      name: 'Privacy',
      component: PrivacyView,
      meta: { title: 'Memoriq - Privacy Policy' },
    },
    {
      path: '/terms',
      name: 'Terms',
      component: TermsView,
      meta: { title: 'Memoriq - Terms of Service' },
    },
    {
      path: '/dashboard',
      name: 'Dashboard',
      component: DashboardView,
      meta: { title: 'Memoriq - Dashboard', requiresAuth: true },
    },
    {
      path: '/settings',
      name: 'Settings',
      component: DashboardView,
      meta: { title: 'Memoriq - Account Settings', requiresAuth: true },
    },
    {
      path: '/share',
      name: 'Share',
      component: ShareView,
      meta: { title: 'Memoriq - Save Shared Chat', requiresAuth: true },
    },
    {
      path: '/login',
      component: LoginView,
      name: 'Login',
      meta: { title: 'Memoriq - Login', guestOnly: true },
    },
    {
      path: '/register',
      component: RegisterView,
      name: 'Register',
      meta: { title: 'Memoriq - Create Account', guestOnly: true },
    },
    {
      path: '/forgot-password',
      component: ForgotPassword,
      name: 'ForgotPassword',
      meta: { title: 'Memoriq - Password Recovery', guestOnly: true },
    },
    {
      path: '/password/reset/:token',
      component: ResetPassword,
      name: 'ResetPassword',
      meta: { title: 'Memoriq - Create a New Password', guestOnly: true },
    },
    {
      path: '/email/verify',
      component: VerifyEmail,
      name: 'EmailVerify',
      meta: { title: 'Memoriq - Verify Your Email Address', requiresAuth: true },
    },
    {
      path: '/email/verified',
      component: VerifiedEmail,
      name: 'EmailVerified',
      meta: { title: 'Memoriq - Email Successfully Verified' },
    },
  ],
};
