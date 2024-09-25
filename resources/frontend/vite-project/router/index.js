import {createRouter, createWebHistory} from "vue-router";
import Home from "../src/components/Home.vue";
import Login from "../src/components/Login.vue";
import Vacancies from "../src/components/Vacancies.vue";
import VacancyDetails from "../src/components/VacancyDetails.vue";
import AdminPanel from "../src/components/AdminPanel.vue";

const routes = [
    {path: '/', component: Home},
    {path: '/login', component: Login},
    {path: '/vacancies', component: Vacancies},
    {path: '/vacancies/:id', component: VacancyDetails},
    {path: '/admin', component: AdminPanel},
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;
