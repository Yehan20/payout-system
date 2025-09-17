<script lang="ts" setup>
// Basecomponent for text feild contains common used classes

defineProps<{
  errorMessages: string[];
  label: string;
}>();

// two  way binding between parent and child
const model = defineModel();
</script>

<template>
  <v-text-field
    class="mb-3"
    v-bind="$attrs"
    variant="outlined"
    v-model="model"
    density="comfortable"
    :error-messages="errorMessages"
    :max-errors="errorMessages.length"
    :label="label"
  />
</template>