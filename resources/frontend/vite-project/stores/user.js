import { defineStore } from 'pinia';
import { ref } from 'vue';
import axios from '../axios/axios.js';

export const useUserStore = defineStore('user', () => {
    const token = ref(localStorage.getItem('token') || null);
    const isLoggedIn = ref(!!token.value);
    const userProfile = ref(null);
    const isModerator = ref(false);

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
        userProfile.value = null;
        isModerator.value = false;
    };

    const checkAuth = async () => {
        token.value = localStorage.getItem('token');
        isLoggedIn.value = !!token.value;
        if (isLoggedIn.value) {
            try {
                const response = await axios.get('/user/profile');
                userProfile.value = response.data.data;
                isModerator.value = userProfile.value.role === 'moderator';
            } catch (error) {
                console.error('Ошибка при проверке авторизации:', error);
                await logout();
            }
        }
    };

    return { token, isLoggedIn, userProfile, isModerator, login, logout, checkAuth };
});
