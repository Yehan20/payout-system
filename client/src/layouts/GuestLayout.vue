<script lang="ts" setup></script>

<template>
  <v-layout class="auth-layout">
    <v-main class="d-flex align-center justify-center pa-0">
      <v-row no-gutters class="w-100 fill-height">
   

        <!-- Right: Form Section -->
        <v-col
          cols="12"
          md="12"
          class="form-section d-flex flex-column justify-center align-center md:px-8"
        >
          <v-sheet class="pa-6 rounded-lg w-100" max-width="700">
            <slot name="form-section" />
          </v-sheet>
        </v-col>
      </v-row>
    </v-main>
  </v-layout>
</template>

<style scoped>
.auth-layout {
  min-height: 100vh;
}

.form-section {
  background-color: #f9f9f9;
}

.v-messages__message {
  font-size: 0.75rem !important;
  font-weight: 500;
  color: red !important;
}

.v-field--error .v-field__outline {
  border-width: 2px !important;
  border-color: red !important;
}
</style>
