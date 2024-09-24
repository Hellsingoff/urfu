import axios from 'axios';

// Создаем экземпляр axios
const axiosInstance = axios.create({
    baseURL: 'http://localhost/api',
});

axiosInstance.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    const lang = localStorage.getItem('lang') || 'ru';

    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    config.headers['Accept-Language'] = lang;
    config.headers['Accept'] = 'application/json';

    return config;
}, error => {
    return Promise.reject(error);
});

export default axiosInstance;
