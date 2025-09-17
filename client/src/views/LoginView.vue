<script lang="ts" setup>
import LoginForm from '@/components/LoginForm.vue';
import GuestLayout from '@/layouts/GuestLayout.vue';
</script>

<template>
  <GuestLayout>


    <template #form-section>
      <div class="mb-6">
        <h1>Login</h1>
        <p>Process payments</p>
      </div>

      <LoginForm />

    </template>
  </GuestLayout>
</template>

<style lang="scss" scoped></style>
