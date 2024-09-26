<template>
    <div class="vacancy-container">
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

        <div class="response-section">
            <div v-if="responses.length > 0" class="responses">
                <h3>{{ $t('vacancy.alreadyResponded') }}</h3>
                    <div v-for="response in responses" :key="response.id" class="response-item">
                        <div class="response-info">
                            <p><strong>{{ $t('vacancy.resume') }}:</strong> {{ response.resume.name }}</p>
                            <p><strong>{{ $t('vacancy.status') }}:</strong> {{ response.status }}</p>
                            <a :href="getResumeFile(response.resume.filename)" target="_blank" class="resume-link">
                                {{ $t('vacancy.downloadResume') }}
                            </a>
                        </div>

                        <div class="commentaries">
                            <h4>{{ $t('vacancy.commentaries') }}</h4>
                            <ul class="chat-history">
                                <li v-for="comment in response.commentaries" :key="comment.id" class="chat-message">
                                    <strong>{{ comment.owner.name }} ({{ comment.owner.roleLabel }}):</strong>
                                    <p>{{ comment.text }}</p>
                                    <small>{{ new Date(comment.created_at).toLocaleString() }}</small>
                                </li>
                            </ul>

                            <form @submit.prevent="submitComment(response.id)">
                                <textarea v-model="newComment" :placeholder="$t('vacancy.enterComment')"></textarea>
                                <button type="submit" class="submit-button">{{ $t('vacancy.sendComment') }}</button>
                            </form>
                        </div>
                    </div>
            </div>

            <div v-else class="response-form">
                <h3>{{ $t('vacancy.respond') }}</h3>
                <form @submit.prevent="submitResponse">
                    <label for="resume">{{ $t('vacancy.selectResume') }}:</label>
                    <Multiselect
                        v-model="selectedResume"
                        :options="resumes"
                        :track-by="'id'"
                        :label="'name'"
                        :placeholder="$t('vacancy.chooseResume')"
                    />

                    <label for="commentary">{{ $t('vacancy.commentary') }}:</label>
                    <textarea v-model="commentary" :placeholder="$t('vacancy.optionalComment')"></textarea>

                    <button type="submit" class="submit-button">{{ $t('vacancy.submitResponse') }}</button>
                </form>

                <div v-if="error" class="error-message">{{ error }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {onMounted, ref, watch} from 'vue';
import {useRoute} from 'vue-router';
import axios from '../../axios/axios.js';
import {useI18n} from 'vue-i18n';
import Multiselect from 'vue-multiselect';

const route = useRoute();
const vacancy = ref({});
const responses = ref([]);
const resumes = ref([]);
const selectedResume = ref(null);
const commentary = ref('');
const newComment = ref('');
const error = ref(null);
const { locale } = useI18n();

const fetchVacancyDetails = async () => {
    try {
        const response = await axios.get(`/vacancies/${route.params.id}`);
        vacancy.value = response.data.data;
    } catch (error) {
        console.error('Ошибка при загрузке вакансии:', error);
    }
};

const fetchResponses = async () => {
    try {
        const { data } = await axios.get(`/vacancies/${route.params.id}/my-responses`);
        responses.value = await Promise.all(
            data.data.map(async (response) => {
                const commentariesResponse = await axios.get(`/vacancy-responses/${response.id}/commentaries`);
                response.commentaries = commentariesResponse.data.data;
                return response;
            })
        );
    } catch (err) {
        console.error(err);
    }
};

const fetchResumes = async () => {
    try {
        const { data } = await axios.get('/user/resume');
        resumes.value = data.data;
    } catch (err) {
        console.error(err);
    }
};

const submitResponse = async () => {
    error.value = null;

    if (!selectedResume.value) {
        error.value = $t('vacancy.errorResumeRequired');
        return;
    }

    try {
        await axios.post('/vacancy-responses', {
            vacancy_id: route.params.id,
            resume_id: selectedResume.value.id,
            commentary: commentary.value,
        });
        await fetchResponses();
    } catch (err) {
        error.value = err.response?.data?.message || $t('vacancy.errorSubmitting');
    }
};

const submitComment = async (responseId) => {
    if (!newComment.value) return;

    try {
        await axios.post(`/vacancy-responses/${responseId}/commentaries`, {
            commentary: newComment.value,
        });
        newComment.value = '';
        await fetchResponses();
    } catch (err) {
        error.value = err.response?.data?.message || $t('vacancy.errorSubmittingComment');
    }
};

const getResumeFile = (filename) => {
    return `http://localhost/${filename}`;
};

onMounted(() => {
    fetchVacancyDetails();
    fetchResponses();
    fetchResumes();
});

watch(locale, () => {
    fetchVacancyDetails();
    fetchResponses();
    fetchResumes();
});
</script>

<style scoped>
.vacancy-container {
    background-color: #1e1e1e;
    color: white;
    padding: 16px;
    border-radius: 8px;
    max-width: 800px;
    margin: 0 auto;
}

.vacancy-details {
    background-color: #2e2e2e;
    padding: 16px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.response-section {
}

.responses {
    background-color: #2e2e2e;
    padding: 16px;
    border-radius: 8px;
}

.response-item {
    list-style-type: none;
}

.response-info {
    margin-bottom: 16px;
}

.resume-link {
    color: #646cff;
    display: block;
    margin-top: 8px;
}

.commentaries {
    margin-top: 16px;
}

.chat-history {
    list-style: none;
    padding: 0;
    margin: 0 0 16px;
    border-radius: 8px;
    background-color: #4a4a4a;
}

.chat-message {
    padding: 10px;
    border-bottom: 1px solid #5a5a5a;
}

.chat-message:last-child {
    border-bottom: none;
}

.chat-message p {
    margin: 4px 0;
}

textarea {
    width: calc(100% - 16px);
    padding: 8px;
    margin-bottom: 16px;
    border-radius: 4px;
    background-color: #1e1e1e;
    color: white;
    border: 1px solid #444;
}

.submit-button {
    background-color: #646cff;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #535ce8;
}

.error-message {
    color: red;
    margin-top: 16px;
}
</style>
