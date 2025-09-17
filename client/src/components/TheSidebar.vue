<script setup lang="ts">
import { useAuthStore } from '@/stores/auth';
import { useDisplay } from 'vuetify';
import TheMiniFooter from './TheMiniFooter.vue';

const model = defineModel<boolean>();

const { mdAndDown } = useDisplay();

const auth = useAuthStore();

const items = [
  {
    title: 'Dashboard',
    value: '/',
  },
  {
    title: 'Payments',
    value: '/payments',
  },
];
</script>

<template>
  <!-- Mobile Drawer -->
  <v-navigation-drawer v-if="mdAndDown" theme="dark" v-model="model"
    :location="$vuetify.display.mobile ? 'left' : undefined" temporary>
    <v-card class="bg-transparent mx-auto text-center pa-4" elevation="0" rounded="0">
      <v-avatar rounded="50" size="50" color="white">
        <v-icon size="40" color="deep-purple-darken-4">mdi-account</v-icon>
      </v-avatar>

      <v-list-item class="text-white" :subtitle="auth.currentUser?.email" :title="auth.currentUser?.name" />
    </v-card>

    <v-list class="pa-0 mt-4">
      <v-btn v-for="item in items" :key="item.value" :to="item.value" variant="text"
        class="text-left justify-center w-100 mb-3 px-4 py-2 rounded" :ripple="false" exact link
        exact-active-class="border-b-2 border-purple">
        {{ item.title }}
      </v-btn>
    </v-list>

    <TheMiniFooter />

  </v-navigation-drawer>

  <!-- Desktop Drawer -->
  <v-navigation-drawer v-if="!mdAndDown"
    class="pa-5  pb-2x text-center d-flex flex-column justify-space-between" color="blue-darken-4" theme="dark"
    style="" permanant>
    <v-card class="bg-transparent" elevation="0" rounded="0">
      <v-avatar rounded="50" size="70" color="white">
        <v-icon size="40" color="deep-purple-darken-4">mdi-account</v-icon>
      </v-avatar>

      <v-list-item class="text-white my-3" :subtitle="auth.currentUser?.email" :title="auth.currentUser?.name">
      </v-list-item>
    </v-card>

    <v-list class="pa-0 mt-4">
      <v-btn v-for="item in items" :key="item.value" :to="item.value" variant="text"
        class="text-cemter justify-center w-100 mb-3 px-4 py-2 rounded" :ripple="false" exact link
        exact-active-class="border-b-2 border-purple">
        {{ item.title }}
      </v-btn>

    </v-list>

    <TheMiniFooter />

  </v-navigation-drawer>
</template>
