import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from "../router/index.js";
import {createPinia} from "pinia";
import messages from '../locales';
import {createI18n} from "vue-i18n";
import VueAxios from "vue-axios";
import axios from "axios";

const pinia = createPinia();
const i18n = createI18n({
    locale: localStorage.getItem('lang') || 'ru',
    messages,
});

createApp(App)
    .use(i18n)
    .use(pinia)
    .use(VueAxios, axios)
    .use(router)
    .mount('#app');
