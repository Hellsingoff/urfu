<template>
    <div class="profile-container">
        <h1>{{ $t('profile.title') }}</h1>
        <div class="tab-buttons">
            <button
                @click="activeTab = 'resumes'"
                :class="{ active: activeTab === 'resumes' }"
                class="tab-button"
            >
                {{ $t('profile.resumes') }}
            </button>
            <button
                @click="activeTab = 'responses'"
                :class="{ active: activeTab === 'responses' }"
                class="tab-button"
            >
                {{ $t('profile.responses') }}
            </button>
            <button
                v-if="userStore.isModerator"
                @click="activeTab = 'vacancies'"
                :class="{ active: activeTab === 'vacancies' }"
                class="tab-button"
            >
                {{ $t('profile.vacancies') }}
            </button>
        </div>

        <div class="tab-content">
            <div v-if="activeTab === 'resumes'">
                <h2>{{ $t('profile.resumeList') }}</h2>
                <div class="resume-container">
                    <div
                        v-for="resume in resumes"
                        :key="resume.id"
                        class="resume-item"
                    >
                        <div class="resume-details">
                            <p class="resume-name">{{ resume.name }}</p>
                            <div class="resume-actions">
                                <button
                                    @click="downloadResume(resume.filename)"
                                    class="download-button"
                                >
                                    {{ $t('profile.download') }}
                                </button>
                                <button
                                    @click="deleteResume(resume.id)"
                                    class="delete-button"
                                >
                                    {{ $t('profile.delete') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <form
                    @submit.prevent="uploadResume"
                    v-if="resumes.length < 10"
                    class="upload-form"
                >
                    <input
                        v-model="newResumeName"
                        :placeholder="$t('profile.resumeName')"
                        class="resume-input"
                        required
                    />
                    <input
                        type="file"
                        @change="onFileChange"
                        class="file-input"
                        required
                    />
                    <button type="submit" class="upload-button">
                        {{ $t('profile.uploadResume') }}
                    </button>
                </form>
                <div v-else class="max-resumes">
                    {{ $t('profile.maxResumesReached') }}
                </div>
            </div>

            <div v-if="activeTab === 'responses'">
                <h2>{{ $t('profile.responsesList') }}</h2>
                <p>{{ $t('profile.noResponses') }}</p>
            </div>

            <div v-if="activeTab === 'vacancies'">
                <h2>{{ $t('profile.vacanciesList') }}</h2>
                <div
                    v-for="vacancy in vacancies"
                    :key="vacancy.id"
                    class="vacancy-item"
                >
                    <router-link :to="`/vacancies/${vacancy.id}`">
                        {{ vacancy.name }}
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { useUserStore } from '../../stores/user.js';
import { useI18n } from 'vue-i18n';
import axios from '../../axios/axios.js';

const userStore = useUserStore();
const { t } = useI18n();
const activeTab = ref('resumes');
const resumes = ref([]);
const newResumeName = ref('');
const selectedFile = ref(null);
const vacancies = ref([]);

const fetchResumes = async () => {
    const { data } = await axios.get('/user/resume');
    resumes.value = data.data;
};

const uploadResume = async () => {
    const formData = new FormData();
    formData.append('name', newResumeName.value);
    formData.append('file', selectedFile.value);

    await axios.post('/resumes', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });

    newResumeName.value = '';
    selectedFile.value = null;
    await fetchResumes();
};

const onFileChange = (event) => {
    selectedFile.value = event.target.files[0];
};

const deleteResume = async (id) => {
    await axios.delete(`/resumes/${id}`);
    await fetchResumes();
};

const downloadResume = (filename) => {
    const url = getResumeFile(filename);
    const link = document.createElement('a');
    link.href = url;
    link.target = '_blank';
    link.click();
};

const fetchVacancies = async () => {
    const { data } = await axios.get('/user/vacancies');
    vacancies.value = data.data;
};

onMounted(() => {
    fetchResumes();
    fetchVacancies();
    userStore.checkAuth();
});

const getResumeFile = (filename) => {
    return `http://localhost/${filename}`;
};
</script>
<style scoped>
.profile-container {
    background-color: #1e1e1e;
    color: white;
    padding: 16px;
    border-radius: 8px;
}

.tab-buttons {
    display: flex;
    margin-bottom: 16px;
}

.tab-button {
    background-color: transparent;
    border: none;
    color: white;
    padding: 10px 20px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.tab-button.active {
    background-color: #646cff;
    border-radius: 4px;
}

.tab-content {
    background-color: #2a2a2a;
    padding: 16px;
    border-radius: 4px;
}

.resume-container {
    margin-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.resume-item {
    background-color: #383838;
    padding: 12px;
    border-radius: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background-color 0.3s;
}

.resume-item:hover {
    background-color: #505050;
}

.resume-details {
    display: flex;
    justify-content: space-between;
    width: 100%;
}

.resume-name {
    font-weight: bold;
}

.resume-actions {
    display: flex;
    gap: 16px;
}

.download-button,
.delete-button {
    background-color: #646cff;
    border: none;
    color: white;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
}

.delete-button {
    background-color: #ff4d4f;
}

.download-button:hover,
.delete-button:hover {
    background-color: #5353c5;
}

.upload-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 16px;
    background-color: #3a3a3a;
    padding: 16px;
    border-radius: 8px;
}

.resume-input,
.file-input {
    background-color: #1e1e1e;
    color: white;
    border: 1px solid #646cff;
    padding: 10px;
    border-radius: 4px;
}

.upload-button {
    background-color: #646cff;
    border: none;
    color: white;
    padding: 12px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.upload-button:hover {
    background-color: #5353c5;
}

.max-resumes {
    color: red;
    margin-top: 8px;
}
</style>
