<script setup lang="ts">
import { ref } from 'vue';
import { useField, useForm } from 'vee-validate';
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';
import { AxiosError } from 'axios';
import BaseInputFeild from '@/components/BaseInputFeild.vue';

const { handleSubmit, setErrors } = useForm({
  validationSchema: {
    password(value: string) {
      if (!value) return 'Password required';

      return true;
    },

    email(value: string) {
      if (!value) return 'Email required';
      if (/^[a-z.-]+@[a-z.-]+\.[a-z]+$/i.test(value)) return true;

      return 'Must be a valid e-mail.';
    },
  },
});

// Router
const router = useRouter();

// Store
const auth = useAuthStore();

// Refs
const alert = ref(false);
const errortext = ref('');
const loading = ref(false);
const show = ref(false);

// Vee validate feilds
const email = useField('email');
const password = useField('password');

// Submit method
const submit = handleSubmit(async (values) => {
  loading.value = true;
  alert.value = false;
  try {
    await auth.loginUser(values.email, values.password);
   
    router.replace('/');
    
  } catch (e) {
    if (e instanceof AxiosError) {
      alert.value = true;
      loading.value = false;

      if (e.response?.status === 401) {
        errortext.value = 'Invalid Credintials';
      } else {
        errortext.value = e.response?.data.message ?? 'Server error';
        setErrors(e.response?.data.errors);
      }
    }
  }

  // handleReset();
});
</script>

<template>
  <v-alert
    v-model="alert"
    class="pt-2 pb-2 mb-6"
    height="50"
    position="static"
    closable
    icon="mdi-alert"
    :text="errortext"
    type="error"
  />

  <form @submit.prevent="submit">
    <!-- Email feild -->
    <BaseInputFeild
      data-test="email"
      v-model="email.value.value"
      label="Email"
      :errorMessages="email.errors.value"
    />

    <!-- Password feild -->
    <BaseInputFeild
      data-test="password"
      :append-inner-icon="show ? 'mdi-eye-off' : 'mdi-eye'"
      :type="show ? 'text' : 'password'"
      @click:append-inner="show = !show"
      v-model="password.value.value"
      label="Password"
      :errorMessages="password.errors.value"
    />

    <!-- Login button -->
    <v-btn
      :disabled="loading"
      :loading="loading"
      class="text-none mb-4 rounded-full"
      color="blue-darken-4"
      type="submit"
      size="large"
      variant="flat"
      data-test="login-btn"
      block
    >
      Login
    </v-btn>

    <!-- Custom snack bar -->

    <!-- <v-btn :disabled="loading" :loading="loading" class="text-none mb-4 rounded-full" color="green" size="large"
            variant="flat" block @click="loading = !loading">
            Demo login
        </v-btn> -->
  </form>
</template>
