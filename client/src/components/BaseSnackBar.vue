<script lang="ts" setup>
defineProps<{
  timeout: number;
}>();

const snackbar = defineModel<boolean>('snackbar');
</script> 

<template>
  <v-snackbar
    :attach="true"
    v-model="snackbar"
    :timeout="timeout"
    color="success"
    variant="flat"
    location="bottom center"
    class="mt-2 rounded-lg"
    max-width="400"
    v-bind="$attrs"
  >
    <div class="d-flex justify-center align-center w-100">
      <span class="text-white font-weight-medium text-center">
        <slot />
      </span>
    </div>
  </v-snackbar>
</template>

<style lang="scss" scoped></style>
