import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from '../axios/axios.js';

export const useUserStore = defineStore('user', () => {
    const token = ref(localStorage.getItem('token') || null);
    const isLoggedIn = ref(!!token.value);

    const login = (newToken) => {
        token.value = newToken;
        localStorage.setItem('token', newToken);
        isLoggedIn.value = true;
    };

    const logout = async () => {
        await axios.post('/logout');
        token.value = null;
        localStorage.removeItem('token');
        isLoggedIn.value = false;
    };

    const checkAuth = () => {
        token.value = localStorage.getItem('token');
        isLoggedIn.value = !!token.value;
    };

    return { token, isLoggedIn, login, logout, checkAuth };
});
