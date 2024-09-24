import {createRouter, createWebHistory} from "vue-router";
import Home from "../src/components/Home.vue";
import Login from "../src/components/Login.vue";
import Vacancies from "../src/components/Vacancies.vue";

const routes = [
    {path: '/', component: Home},
    {path: '/login', component: Login},
    {path: '/vacancies', component: Vacancies},
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
