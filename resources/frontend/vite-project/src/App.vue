<script setup>
import { useI18n } from 'vue-i18n';
import { useUserStore } from '../stores/user';
import { onMounted } from "vue";

const { locale } = useI18n();
const userStore = useUserStore();

onMounted(() => {
    userStore.checkAuth();
});

const changeLanguage = (event) => {
    const lang = event.target.value;
    locale.value = lang;
    localStorage.setItem('lang', lang);
};

const logout = () => {
    userStore.logout();
};
</script>

<template>
    <div class="navbar">
        <div class="nav-links">
            <div class="nav-item">
                <router-link to="/" class="link">{{ $t('navbar.home') }}</router-link>
            </div>
            <div class="nav-item">
                <router-link to="/vacancies" class="link">{{ $t('navbar.vacancies') }}</router-link>
            </div>
            <template v-if="userStore.isModerator">
                <div class="nav-item">
                    <router-link to="/admin" class="link">{{ $t('navbar.admin') }}</router-link>
                </div>
            </template>
        </div>
        <div class="settings">
            <template v-if="userStore.isLoggedIn">
                <router-link to="/profile" class="profile-button">{{ $t('navbar.profile') }}</router-link>
                <button class="profile-button" @click="logout">{{ $t('navbar.logout') }}</button>
            </template>
            <template v-else>
                <router-link to="/login" class="profile-button">{{ $t('navbar.login') }}</router-link>
                <router-link to="/register" class="profile-button">{{ $t('navbar.register') }}</router-link>
            </template>
            <select @change="changeLanguage" class="language-select" :value="locale">
                <option value="en">English</option>
                <option value="ru">Русский</option>
                <option value="de">Deutsch</option>
            </select>
        </div>
    </div>
    <div class="content">
        <router-view />
    </div>
</template>

<style scoped>
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #171717;
    border-radius: 8px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 10px;
}

.nav-links {
    display: flex;
    gap: 4px;
}

.nav-item {
    border-radius: 8px;
    border: 2px solid transparent;
    transition: border-color 0.3s;
    display: flex;
}

.nav-item:hover {
    border-color: #646cff;
}

.link {
    text-decoration: none;
    color: white;
    display: block;
    width: 100%;
    height: 100%;
    box-sizing: border-box;
    padding: 16px;
}

.language-select {
    background-color: #2a2a2a;
    color: white;
    border: 2px solid transparent;
    border-radius: 4px;
    padding: 8px;
    cursor: pointer;
    transition: border-color 0.3s;
    margin-right: 8px;
    font-size: 16px;
    font-weight: 500;
}

.language-select:hover {
    border-color: #646cff;
}

.settings {
    display: flex;
    gap: 8px;
}

.profile-button {
    border: 2px solid transparent;
    color: white;
    background-color: #2a2a2a;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s, border-color 0.3s;
}

.profile-button:hover {
    border-color: #646cff;
    background-color: #3a3a3a;
}

.content {
    max-width: 1200px;
    margin: 20px auto;
}
</style>
