import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '@/stores/auth';

import items from './items';

const router = createRouter({
  history: createWebHistory(),
  routes: items,
});

// Before each
router.beforeEach(async function (to, from) {
  // update title based on the route name
  console.log('From', from);

  document.title = `Payment System | `

  const auth = useAuthStore();

  // Auth required gaurd should check for user in the store if not there fetch if fails redirect back to login\
  if (to.meta.authRequired && !auth.currentUser) {
    await auth.getUser();

    if (!auth.currentUser) {
      return {
        name: 'login',
      };
    }
  }

  return true;
});

// after navigation is successfull remove the loader
router.afterEach(() => {
  //
  const mainloader = document.querySelector('#main-loader');
  if (mainloader) {
    mainloader.remove();
  }
});

export default router;
