<template>
    <div class="vacancies-page">
        <h1>{{ $t('vacancies.title') }}</h1>
        <div class="filters">
            <div class="selects-row">
                <Multiselect
                    v-model="filters.category_id"
                    :options="categories"
                    label="name"
                    track-by="id"
                    :placeholder="$t('vacancies.category')"
                    :show-labels="false"
                    :clear-on-select="true"
                    :preserve-search="true"
                    class="custom-multiselect"
                />
                <Multiselect
                    v-model="filters.organization_id"
                    :options="organizations"
                    label="name"
                    track-by="id"
                    :placeholder="$t('vacancies.organization')"
                    :show-labels="false"
                    :clear-on-select="true"
                    :preserve-search="true"
                    class="custom-multiselect"
                />
                <Multiselect
                    v-model="filters.skills"
                    :options="skills"
                    label="name"
                    track-by="id"
                    :placeholder="$t('vacancies.skills')"
                    :multiple="true"
                    :show-labels="false"
                    :clear-on-select="true"
                    :preserve-search="true"
                    class="custom-multiselect"
                />
            </div>
            <div class="input-button-row">
                <input v-model="filters.text" type="text" :placeholder="$t('vacancies.placeholderText')" class="search-input" />
                <button @click="applyFilters" class="search-button">{{ $t('vacancies.filterButton') }}</button>
            </div>
        </div>

        <div v-if="loading" class="loading-placeholder"></div>
        <div v-else>
            <div v-if="vacancies.length === 0" class="no-vacancies">{{ $t('vacancies.noVacancies') }}</div>
            <ul class="vacancy-list">
                <li v-for="vacancy in vacancies" :key="vacancy.id" class="vacancy-item" @click="goToVacancy(vacancy.id)">
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
import { ref, onMounted, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import axios from '../../axios/axios.js';
import Multiselect from "vue-multiselect";
import router from "../../router/index.js";

const { t, locale } = useI18n();

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

const selectedLanguage = ref(localStorage.getItem('lang') || 'en');

watch(selectedLanguage, (newLang) => {
    locale.value = newLang;
    localStorage.setItem('lang', newLang);
});

const goToVacancy = (id) => {
    router.push(`/vacancies/${id}`);
};

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
        category_id: filters.value.category_id ? filters.value.category_id.id : undefined,
        organization_id: filters.value.organization_id ? filters.value.organization_id.id : undefined,
        text: filters.value.text || undefined,
    };

    filters.value.skills?.forEach((skill, index) => {
        params[`skills[${index}]`] = skill.id;
    });

    try {
        const response = await axios.get('/vacancies', { params });
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

watch(locale, async () => {
    await fetchFiltersData();
    filters.value.category_id = categories.value.find(category => category.id === filters.value.category_id?.id) || null;
    filters.value.organization_id = organizations.value.find(org => org.id === filters.value.organization_id?.id) || null;
    filters.value.skills = skills.value.filter(skill => filters.value.skills.some(selectedSkill => selectedSkill.id === skill.id));
    await fetchVacancies(currentPage.value);
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
<style scoped>
.loading-placeholder {
    background-color: #1e1e1e;
    border-radius: 16px;
    height: 150px;
    margin-bottom: 16px;
    animation: pulse 0.5s infinite;
}

@keyframes pulse {
    0% {
        opacity: 0.7;
    }
    25% {
        opacity: 0.85;
    }
    50% {
        opacity: 1;
    }
    75% {
        opacity: 0.85;
    }
    100% {
        opacity: 0.7;
    }
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

.filters {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.selects-row {
    display: flex;
    gap: 16px;
    margin-bottom: 8px;
}

.input-button-row {
    display: flex;
    align-items: center;
}

.search-input {
    padding: 8px;
    border-radius: 4px;
    background-color: #2a2a2a;
    color: white;
    border: 2px solid transparent;
    flex: 1;
    margin-right: 8px;
}

.search-button {
    padding: 8px 12px;
    border-radius: 4px;
    background-color: #2a2a2a;
    color: white;
    border: 2px solid transparent;
    transition: border-color 0.3s;
    cursor: pointer;
}
</style>
