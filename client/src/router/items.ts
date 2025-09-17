import AppLayout from '@/layouts/AppLayout.vue';
import HomeView from '@/views/HomeView.vue';
const items = [


  {
    path: '/login',
    name: 'login',
    component: () => import('../views/LoginView.vue'),
  },

  {
    path: '/',
    component: AppLayout,
    meta: {
      authRequired: true,
    },
    // HomeView
    children: [
      { path: '', component: HomeView, name: 'home' },

      {
        path: 'payments',
        name: 'payments',

        component: () => import('../views/PaymentView.vue'),
      },
      {
        path: ':pathMatch(.*)*',
        name: 'notfound',
        component: () => import('../views/NotFoundView.vue'),
      },
    ],
  },

  {
    path: '/:pathMatch(.*)*',
    name: 'notfound',
    component: () => import('../views/NotFoundView.vue'),
  },
];

export default items;
