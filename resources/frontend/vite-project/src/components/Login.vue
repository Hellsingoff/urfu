<template>
    <div class="login-form">
        <h2>{{ $t('login.title') }}</h2>
        <form @submit.prevent="submit">
            <div>
                <label for="email">{{ $t('login.email') }}</label>
                <input
                    type="text"
                    id="email"
                    v-model="email"
                    @input="clearErrors('email')"
                :class="{ 'error-border': errors.email }"
                required
                />
                <span v-if="errors.email" class="error">{{ errors.email[0] }}</span>
            </div>
            <div>
                <label for="password">{{ $t('login.password') }}</label>
                <input
                    type="password"
                    id="password"
                    v-model="password"
                    @input="clearErrors('password')"
                :class="{ 'error-border': errors.password }"
                required
                />
                <span v-if="errors.password" class="error">{{ errors.password[0] }}</span>
            </div>
            <button type="submit">{{ $t('login.submit') }}</button>
            <span v-if="generalError" class="error">{{ generalError }}</span>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useUserStore } from '../../stores/user.js';
import router from "../../router/index.js";
import axios from "../../axios/axios.js";

const { t } = useI18n();
const userStore = useUserStore();
const email = ref('');
const password = ref('');
const errors = ref({});
const generalError = ref('');

const clearErrors = (field) => {
    if (errors.value[field]) {
        delete errors.value[field];
    }
};

const submit = async () => {
    generalError.value = '';
    errors.value = {};

    try {
        const response = await axios.post('/login', {
            email: email.value,
            password: password.value,
        });

        const token = response.data.data.token;
        userStore.login(token);
        await router.push('/');
    } catch (error) {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            generalError.value = t('login.generalError');
        }
    }
};
</script>

<style scoped>
.login-form {
    max-width: 400px;
    margin: 100px auto;
    padding: 20px;
    background-color: #2a2a2a;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
}

h2 {
    color: white;
    text-align: center;
    margin-bottom: 20px;
}

div {
    margin-bottom: 20px;
}

label {
    display: block;
    color: white;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 12px;
    border-radius: 4px;
    border: 2px solid #ccc;
    transition: border-color 0.3s;
    font-size: 16px;
    box-sizing: border-box;
}

input.error-border {
    border-color: red;
}

button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 4px;
    background-color: #646cff;
    color: white;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

button:hover {
    background-color: #5058b2;
}

.error {
    color: red;
    font-size: 0.875rem;
    margin-top: 5px;
    text-align: center;
}
</style>
