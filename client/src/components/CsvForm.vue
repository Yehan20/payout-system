<script lang="ts" setup>
import { ref, shallowRef } from 'vue';
import { useRouter } from 'vue-router';
import { AxiosError } from 'axios';
import axios from '../axios/axios';


const router = useRouter();

const density = shallowRef('comfortable')
const file = ref<File | null>(null);
const alert = ref(false);
const alertMessage = ref('');
const alertColor = ref<'success' | 'error' | 'info' | 'warning'>('info');
const loading = ref(false);
const timeout = ref(2000);

const submit = async () => {

  if (!file.value) {
    alertColor.value = 'error';
    alertMessage.value = 'Please select a file to upload';
    alert.value = true;
    return;
  }

  loading.value = true;


  try {
    const formData = new FormData();
    formData.append('file', file.value);

    await axios.post('api/upload', formData);

    alertColor.value = 'success';
    alertMessage.value = 'File Uploaded processing has began ';
    alert.value = true;
    file.value = null;

    setTimeout(() => {
      router.push('/');
    }, timeout.value);
  } catch (e) {
    alertColor.value = 'error';

    if (e instanceof AxiosError) {
      alertMessage.value = e.response?.data.message ?? 'Unexpected error';
    } else {
      alertMessage.value = 'Unexpected error';
    }
    alert.value = true;
  } finally {
    loading.value = false;

  }
};
</script>

<template>
  <form class="mt-6" @submit.prevent="submit">
    <v-row>
      <v-col cols="12">
        <div class="pa-3">
          <h1>File</h1>
          <!-- Single file upload -->
          <v-form enctype="multipart/form-data" @submit.prevent="submit">
            <v-file-input v-model="file" label="Upload File" accept=".pdf,.doc,.docx,.csv" show-size
              density="comfortable" variant="outlined" />
          </v-form>

          <!-- Submit button -->
          <v-btn :disabled="loading" :loading="loading" class="text-none mt-3" color="blue-darken-4" type="submit"
            variant="flat">
            {{ loading ? 'Please Wait' : 'Upload File' }}
          </v-btn>

          <!-- Alert -->
          <v-alert v-model="alert" class="mt-3" closable
            :icon="alertColor === 'success' ? 'mdi-check-circle' : 'mdi-alert'" :text="alertMessage"
            :type="alertColor" />
        </div>


      </v-col>
    </v-row>
  </form>
</template>
