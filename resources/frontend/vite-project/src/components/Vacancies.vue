<template>
    <div class="vacancies-page">
        <h1>{{ $t('vacancies.title') }}</h1>

        <!-- Фильтры -->
        <div class="filters">
            <input v-model="filters.text" type="text" placeholder="Поиск по тексту..." />

            <select v-model="filters.category_id">
                <option value="">{{ $t('vacancies.category') }}</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
            </select>

            <select v-model="filters.organization_id">
                <option value="">{{ $t('vacancies.organization') }}</option>
                <option v-for="organization in organizations" :key="organization.id" :value="organization.id">{{ organization.name }}</option>
            </select>

            <Multiselect v-model="filters.skills" :options="skills" label="name" track-by="id" multiple placeholder="Выберите навыки" />

            <button @click="applyFilters">{{ $t('vacancies.filterButton') }}</button>
        </div>

        <!-- Вакансии -->
        <div v-if="loading" class="loading">Загрузка...</div>
        <div v-else>
            <div v-if="vacancies.length === 0" class="no-vacancies">{{ $t('vacancies.noVacancies') }}</div>
            <ul class="vacancy-list">
                <li v-for="vacancy in vacancies" :key="vacancy.id" class="vacancy-item">
                    <h2>{{ vacancy.name }}</h2>
                    <p><strong>{{ $t('vacancies.category') }}:</strong> {{ vacancy.category.name }}</p>
                    <p><strong>{{ $t('vacancies.organization') }}:</strong> {{ vacancy.organization.name }}</p>
                    <p><strong>{{ $t('vacancies.skills') }}:</strong> {{ vacancy.skills.map(skill => skill.name).join(', ') }}</p>
                </li>
            </ul>
            <div class="pagination">
                <button @click="prevPage" :disabled="currentPage === 1" class="pagination-btn">
                    {{ $t('pagination.prev') }}
                </button>
                <span>{{ currentPage }} / {{ pagination.lastPage }}</span>
                <button @click="nextPage" :disabled="currentPage === pagination.lastPage" class="pagination-btn">
                    {{ $t('pagination.next') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import {ref, onMounted, watch} from 'vue';
import {useI18n} from 'vue-i18n';
import axios from '../../axios/axios.js';
import Multiselect from '@vueform/multiselect';

const {t, locale} = useI18n();

const vacancies = ref([]);
const categories = ref([]);
const organizations = ref([]);
const skills = ref([]);
const loading = ref(true);
const pagination = ref({});
const currentPage = ref(1);
const filters = ref({
    text: '',
    category_id: '',
    organization_id: '',
    skills: [],
});

const fetchFiltersData = async () => {
    try {
        const [categoriesRes, organizationsRes, skillsRes] = await Promise.all([
            axios.get('/categories?without_pagination=1'),
            axios.get('/organizations?without_pagination=1'),
            axios.get('/skills?without_pagination=1'),
        ]);
        categories.value = categoriesRes.data.data;
        organizations.value = organizationsRes.data.data;
        skills.value = skillsRes.data.data;
    } catch (error) {
        console.error('Ошибка при загрузке фильтров:', error);
    }
};

const fetchVacancies = async (page = 1) => {
    loading.value = true;

    const params = {
        page,
        category_id: filters.value.category_id || undefined,
        organization_id: filters.value.organization_id || undefined,
        text: filters.value.text || undefined,
    };

    filters.value.skills.forEach((skill, index) => {
        params[`skills[${index}]`] = skill.id;
    });

    try {
        const response = await axios.get('/vacancies', {params});
        vacancies.value = response.data.data;
        pagination.value = response.data.pagination;
        currentPage.value = page;
    } catch (error) {
        console.error('Ошибка при загрузке вакансий:', error);
    } finally {
        loading.value = false;
    }
};

const applyFilters = () => {
    fetchVacancies(1);
};

const prevPage = () => {
    if (currentPage.value > 1) {
        fetchVacancies(currentPage.value - 1);
    }
};

const nextPage = () => {
    if (currentPage.value < pagination.value.lastPage) {
        fetchVacancies(currentPage.value + 1);
    }
};

onMounted(() => {
    fetchFiltersData();
    fetchVacancies(currentPage.value);
});

watch(locale, () => {
    fetchVacancies(currentPage.value);
});
</script>

<style scoped>
.loading {
    text-align: center;
}

.no-vacancies {
    text-align: center;
    font-size: 1.2rem;
}

.vacancy-list {
    list-style-type: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
}

.vacancy-item {
    flex: 1 1 calc(50% - 32px);
    border: 2px solid transparent;
    border-radius: 16px;
    padding: 16px;
    transition: border-color 0.5s;
    box-sizing: border-box;
    background-color: #1e1e1e;
    color: white;
    min-width: 400px;
}

.vacancy-item:hover {
    border-color: #646cff;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 10px;
}

.pagination-btn {
    background-color: #2a2a2a;
    color: white;
    border: 2px solid transparent;
    border-radius: 4px;
    padding: 8px 16px;
    cursor: pointer;
    transition: border-color 0.3s;
    min-width: 150px;
}

.pagination-btn:hover {
    border-color: #646cff;
}

.pagination-btn:disabled {
    opacity: 0.5;
}

.pagination-btn:disabled:hover {
    border-color: transparent;
    cursor: default;
}

.filters {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 20px;
}

.filters select,
.filters input,
.filters button {
    padding: 8px;
    border-radius: 4px;
    background-color: #2a2a2a;
    color: white;
    border: 2px solid transparent;
    transition: border-color 0.3s;
    cursor: pointer;
}

.filters button:hover {
    border-color: #646cff;
}

.filters input {
    flex: 1 1 100%;
}

.filters select {
    flex: 1 1 200px;
}

</style>
