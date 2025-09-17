<script setup lang="ts">
import { onMounted, ref } from 'vue';
import type { DataTableHeader } from 'vuetify';
import { AxiosError } from 'axios';
import axios from '../axios/axios';


const itemsPerPage = ref(10);
const currentPage = ref(1);
const totalItems = ref(0);
const loading = ref(false);

const snackbar = ref(false);
const snackBarMessage = ref('');
const snackbarColor = ref<'success' | 'error'>('success');
const timeout = ref(1500);

// local variable for payments (instead of paymentStore)
const payments = ref<any[]>([]);

const headers = ref<DataTableHeader[]>([
  { title: 'ID', key: 'customer_id', align: 'start', sortable: false },
  { title: 'Name', key: 'customer_name', align: 'start', sortable: false },
  { title: 'Email', key: 'customer_email', align: 'start', sortable: false },
  { title: 'Amount', key: 'amount', align: 'end', sortable: false },
  { title: 'Currency', key: 'currency', align: 'center', sortable: false },
  { title: 'Amount ($)', key: 'amount_usd', align: 'end', sortable: false },
  { title: 'Reference No', key: 'reference_no', align: 'start', sortable: false },
  { title: 'Date Time', key: 'date_time', align: 'start', sortable: false },
  { title: 'Processed', key: 'processed', align: 'center', sortable: false },
]);

async function loadPayments(page: number, perPage: number) {
  loading.value = true;
  try {
    const { data } = await axios.get(`api/payments?page=${page}&per_page=${perPage}`);

    // assuming backend returns { data: [], meta: { current_page, total } }
    payments.value = data.data ?? [];
    currentPage.value = data.meta.current_page ?? 1;
    totalItems.value = data.meta.last_page ?? 0;
  } catch (e) {
    snackbar.value = true;
    snackbarColor.value = 'error';
    if (e instanceof AxiosError) {
      snackBarMessage.value = e.response?.data.message ?? 'Failed to load payments';
    } else {
      snackBarMessage.value = 'Unexpected error occurred';
    }
  } finally {
    loading.value = false;
  }
}

onMounted(async () => {
  if (!payments.value.length) {
    await loadPayments(1, itemsPerPage.value);
  }
});
</script>

<template>
  <v-card class="elevation-0 mb-4 pa-2 pa-md-6 bg-white">
    <h1>Payments</h1>

    <v-card max-width="1200" elevation="0" class="shadow rounded-lg border border-blue-darken-4 mt-4">
      <v-card-text>
        <v-data-table-server  class="mt-2" v-model:items-per-page="itemsPerPage" :headers="headers" :items="payments"
          height="700px" fixed-header :items-length="totalItems" :loading="loading" item-value="id" hover
          hide-default-footer>
          <!-- Format amount -->
          <template v-slot:[`item.amount`]="{ item }">
            {{ Number(item.amount).toLocaleString() }}
          </template>

          <!-- Format amount_usd -->
          <template v-slot:[`item.amount_usd`]="{ item }">
            ${{ item.amount_usd ? Number(item.amount_usd).toLocaleString() : '-' }}
          </template>

          <!-- Processed status -->
          <template v-slot:[`item.processed`]="{ item }">
            <v-chip :color="item.processed ? 'blue-darken-4' : 'red'" variant="flat" size="small" label>
              {{ item.processed ? 'Yes' : 'No' }}
            </v-chip>
          </template>
        </v-data-table-server>
      </v-card-text>
    </v-card>

    <!-- Pagination -->
    <v-pagination class="mt-4" size="small" active-color="blue-darken-4" :disabled="loading" variant="flat"
      v-model="currentPage" :length="totalItems"
      @update:model-value="async (page) => await loadPayments(page, itemsPerPage)" />


    <!-- SnackBar -->
    <BaseSnackBar location="top right" v-model="snackbar" :timeout="timeout" :color="snackbarColor">
      {{ snackBarMessage }}
    </BaseSnackBar>
  </v-card>
</template>

<style>
.v-table__wrapper table {
  width: 100% !important;
 
  border-collapse: collapse;
}

</style>
