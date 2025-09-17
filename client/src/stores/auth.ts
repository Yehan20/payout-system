import type { UserInfo } from '@/types/type';
import axios from '../axios/axios';
import { defineStore } from 'pinia';
import router from '@/router';


export const useAuthStore = defineStore('user', {
  state: () => ({
    currentUser: null as UserInfo | null,
  }),

  actions: {
    async loginUser(email: string, password: string) {
      try {


        await axios.get('sanctum/csrf-cookie');
        const response = await axios.post('login', {
          email,
          password,
        });


        this.currentUser = response.data['user'];

      } catch (error) {
        console.log(error);
        throw error;
      }
    },

    async getUser() {
      try {
        const response = await axios.get('api/user');
        this.currentUser = response.data['user'];
      } catch (error) {
        return error;
      }
    },

    async logout() {

      try {
        await axios.post('logout');
        router.push({ name: 'login' });
        this.currentUser = null;

      } catch (error) {
        return error;
      }
    },


  },
});
