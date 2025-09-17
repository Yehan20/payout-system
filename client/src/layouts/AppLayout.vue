<script setup lang="ts">
import TheSidebar from '@/components/TheSidebar.vue';

import { useAuthStore } from '@/stores/auth';
import { ref, watch } from 'vue';
import { useDisplay } from 'vuetify';



const auth = useAuthStore();
const { mdAndDown } = useDisplay();

const group = ref(null);
const drawer = ref(false);

const loading = ref(false);

const handleLogout = async () => {
  loading.value = true;
  try {
    await auth.logout();
  } catch (e) {
    console.log(e);
    loading.value = false;
  }
};

watch(group, () => {
  drawer.value = false;
});
</script>

<template>
  <v-card>
    <v-layout>
      <!-- Side bar -->
      <TheSidebar v-model="drawer" />

      <v-app-bar elevation="0" color="dark" class="text-body" style="font-size: 0.9rem;"
        title="">



        <v-spacer />

        <v-btn data-test="logout-btn" class="me-2" @click="handleLogout" :disabled="loading" :loading="loading">
          <v-icon>mdi-logout</v-icon>
          Logout
        </v-btn>

        <v-app-bar-nav-icon v-if="mdAndDown" variant="text" @click.stop="drawer = !drawer" />
      </v-app-bar>

      <!-- Child router -->
      <v-main class="" style="min-height: 100vh; background: #f4f7fd">
        <router-view v-slot="{ Component }">
          <v-fade-transition hide-on-leave>
            <component :is="Component" />
          </v-fade-transition>
        </router-view>
      </v-main>
    </v-layout>
  </v-card>
</template>
