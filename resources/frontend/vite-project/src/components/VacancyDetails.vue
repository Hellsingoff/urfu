<template>
    <div class="vacancy-details">
        <h1>{{ vacancy.name }}</h1>
        <p><strong>{{ $t('vacancies.status') }}:</strong> {{ vacancy.status || 'N/A' }}</p>
        <p><strong>{{ $t('vacancies.category') }}:</strong> {{ vacancy.category ? vacancy.category.name : 'N/A' }}</p>
        <p><strong>{{ $t('vacancies.organization') }}:</strong> {{ vacancy.organization ? vacancy.organization.name : 'N/A' }}</p>
        <p><strong>{{ $t('vacancies.skills') }}:</strong> {{ vacancy.skills ? vacancy.skills.map(skill => skill.name).join(', ') : 'N/A' }}</p>
        <p v-if="vacancy.rating !== undefined && vacancy.rating !== null">
            <strong>{{ $t('vacancies.rating') }}:</strong> {{ vacancy.rating.toFixed(1) }}
        </p>
        <p>
            <strong>{{ $t('vacancies.owner') }}:</strong>
            {{ vacancy.owner ? vacancy.owner.name : 'N/A' }} ({{ vacancy.owner ? vacancy.owner.roleLabel : 'N/A' }})
        </p>
        <p><strong>{{ $t('vacancies.description') }}:</strong> {{ vacancy.description || 'N/A' }}</p>
    </div>
</template>

<script setup>
import {ref, onMounted, watch} from 'vue';
import {useRoute} from 'vue-router';
import axios from '../../axios/axios.js';
import {useI18n} from 'vue-i18n';

const route = useRoute();
const vacancy = ref({});
const {locale} = useI18n();

const fetchVacancyDetails = async () => {
    try {
        const response = await axios.get(`/vacancies/${route.params.id}`);
        vacancy.value = response.data.data;
    } catch (error) {
        console.error('Ошибка при загрузке вакансии:', error);
    }
};

onMounted(() => {
    fetchVacancyDetails();
});

watch(locale, () => {
    fetchVacancyDetails();
});
</script>

<style scoped>
.vacancy-details {
    background-color: #1e1e1e;
    color: white;
    padding: 16px;
    border-radius: 8px;
}

.vacancy-details h1 {
    font-size: 2rem;
    margin-bottom: 16px;
}

.vacancy-details p {
    margin: 8px 0;
    font-size: 1rem;
}

.vacancy-details strong {
    color: #646cff;
}
</style>
