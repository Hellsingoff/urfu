<template>
    <div class="create-vacancy">
        <h1>{{ $t('admin.createVacancy') }}</h1>

        <div class="language-checkboxes">
            <label v-for="lang in languages" :key="lang.code" class="checkbox-inline">
                <input
                    type="checkbox"
                    v-model="selectedLanguages"
                    :value="lang.code" />
                {{ lang.name }}
            </label>
        </div>
        <div v-if="errors.languages" class="error-message">{{ errors.languages }}</div>

        <div v-for="lang in selectedLanguages" :key="lang" class="language-fields">
            <h2>{{ langNames[lang] }}</h2>
            <label>
                {{ $t('admin.vacancyName') }}:
                <input
                    v-model="vacancy.name[lang]"
                    required
                    :class="{ 'error': errors.name[lang] }" />
            </label>
            <div v-if="errors.name[lang]" class="error-message">{{ errors.name[lang] }}</div>

            <label>
                {{ $t('admin.description') }}:
                <textarea
                    v-model="vacancy.description[lang]"
                    required
                    :class="{ 'error': errors.description[lang] }"></textarea>
            </label>
            <div v-if="errors.description[lang]" class="error-message">{{ errors.description[lang] }}</div>
        </div>

        <div class="select-container">
            <label>
                <Multiselect
                    v-model="vacancy.category_id"
                    :options="categories"
                    label="name"
                    track-by="id"
                    :placeholder="$t('vacancies.category')"
                    :show-labels="false"
                    :clear-on-select="true"
                    :preserve-search="true"
                    :close-on-select="true"
                    class="custom-multiselect" />
            </label>
            <div v-if="errors.category_id" class="error-message">{{ errors.category_id }}</div>

            <label>
                <Multiselect
                    v-model="vacancy.organization_id"
                    :options="organizations"
                    :multiple="false"
                    track-by="id"
                    label="name"
                    :placeholder="$t('vacancies.organization')"
                    :class="{ 'error': errors.organization_id }" />
            </label>
            <div v-if="errors.organization_id" class="error-message">{{ errors.organization_id }}</div>

            <label>
                <Multiselect
                    v-model="vacancy.skills"
                    :options="skills"
                    :multiple="true"
                    track-by="id"
                    label="name"
                    :placeholder="$t('vacancies.skills')"
                    :class="{ 'error': errors.skills }" />
            </label>
            <div v-if="errors.skills" class="error-message">{{ errors.skills }}</div>
        </div>

        <button @click.prevent="createVacancy" class="submit-button">{{ $t('admin.send') }}</button>

        <div v-if="error" class="error-message general">{{ error }}</div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useI18n } from 'vue-i18n';
import axios from '../../axios/axios.js';
import Multiselect from 'vue-multiselect';

const languages = [
    { code: 'ru', name: 'Русский' },
    { code: 'en', name: 'English' },
    { code: 'de', name: 'Deutsch' },
];

const langNames = {
    ru: 'Русский',
    en: 'English',
    de: 'Deutsch',
};

const selectedLanguages = ref([]);
const vacancy = ref({
    name: {},
    description: {},
    category_id: null,
    organization_id: null,
    skills: [],
});

const categories = ref([]);
const organizations = ref([]);
const skills = ref([]);
const error = ref(null);
const errors = ref({
    name: {},
    description: {},
    category_id: null,
    organization_id: null,
    skills: null,
    languages: null,
});

const router = useRouter();
const { locale } = useI18n();

const fetchCategories = async () => {
    try {
        const response = await axios.get('/categories?without_pagination=1');
        categories.value = response.data.data;
    } catch (err) {
        console.error(err);
    }
};

const fetchOrganizations = async () => {
    try {
        const response = await axios.get('/organizations?without_pagination=1');
        organizations.value = response.data.data;
    } catch (err) {
        console.error(err);
    }
};

const fetchSkills = async () => {
    try {
        const response = await axios.get('/skills?without_pagination=1');
        skills.value = response.data.data;
    } catch (err) {
        console.error(err);
    }
};

const fetchData = async () => {
    await Promise.all([fetchCategories(), fetchOrganizations(), fetchSkills()]);
};

watch(() => locale.value, () => {
    fetchData();
});

fetchData();

const createVacancy = async () => {
    error.value = null;
    errors.value = {
        name: {},
        description: {},
        category_id: null,
        organization_id: null,
        skills: null,
        languages: null,
    };

    try {
        const response = await axios.post('/vacancies', {
            languages: selectedLanguages.value,
            name: vacancy.value.name,
            description: vacancy.value.description,
            category_id: vacancy.value.category_id?.id,
            organization_id: vacancy.value.organization_id?.id,
            skills: vacancy.value.skills.map(skill => skill.id),
        });

        await router.push(`/vacancies/${response.data.data.id}`);
    } catch (err) {
        if (err.response && err.response.data && err.response.data.errors) {
            const errorData = err.response.data.errors;
            Object.keys(errorData).forEach(field => {
                const fieldParts = field.split('.');

                if (fieldParts.length === 2) {
                    const [mainField, lang] = fieldParts;
                    if (!errors.value[mainField]) {
                        errors.value[mainField] = {};
                    }
                    errors.value[mainField][lang] = errorData[field][0];
                } else {
                    errors.value[field] = errorData[field][0];
                }
            });
        } else {
            error.value = err.response?.data?.message || 'Неизвестная ошибка';
        }
    }
};
</script>

<style scoped>
.language-checkboxes {
    display: flex;
    gap: 16px;
}

.checkbox-inline {
    display: flex;
    align-items: center;
}

label {
    display: block;
}

input, textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 4px;
    border: 1px solid #646cff;
    border-radius: 4px;
    background-color: #2a2a2a;
    color: white;
    box-sizing: border-box;
}

input.error, textarea.error {
    border-color: red;
}

.select-container {
    display: flex;
    flex-direction: column;
    gap: 16px;
    margin-top: 16px;
}

.submit-button {
    margin-top: 32px;
    padding: 10px 16px;
    background-color: #646cff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.submit-button:hover {
    background-color: #545bc6;
}

.error-message {
    color: red;
}

.general {
    margin-top: 12px;
}
</style>
