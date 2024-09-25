<template>
    <div class="admin-panel">
        <h1>{{ $t('admin.title') }}</h1>
        <div class="tabs">
            <button
                v-for="tab in tabs"
                :key="tab.name"
                @click="activeTab = tab.name"
                :class="{ active: activeTab === tab.name }"
                class="tab-button"
            >
                {{ $t(`admin.${tab.name}`) }}
            </button>
        </div>

        <div class="tab-content">
            <div v-if="activeTab === 'vacancies'">
                <CreateVacancy></CreateVacancy>
            </div>
            <div v-if="activeTab === 'categories'">
                <h2>{{ $t('admin.categories') }}</h2>
                <p>{{ $t('admin.noData') }}</p>
            </div>
            <div v-if="activeTab === 'organizations'">
                <h2>{{ $t('admin.organizations') }}</h2>
                <p>{{ $t('admin.noData') }}</p>
            </div>
            <div v-if="activeTab === 'skills'">
                <h2>{{ $t('admin.skills') }}</h2>
                <p>{{ $t('admin.noData') }}</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import CreateVacancy from "./CreateVacancy.vue";

const tabs = [
    { name: 'vacancies' },
    { name: 'categories' },
    { name: 'organizations' },
    { name: 'skills' },
];

const activeTab = ref('vacancies');
const vacancy = ref({
    name: '',
    description: '',
    category: '',
    organization: '',
    skills: '',
});

</script>

<style scoped>
.admin-panel {
    background-color: #1e1e1e;
    color: white;
    padding: 16px;
    border-radius: 8px;
}

.admin-panel h1 {
    font-size: 2rem;
    margin-bottom: 16px;
}

.tabs {
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
</style>
