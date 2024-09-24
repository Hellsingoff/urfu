<script setup>
import { ref } from 'vue'

defineProps({
  msg: String,
})

const count = ref(0)
</script>

<template>
    <h1>{{ msg }}</h1>
    HelloWorldTest
    <div class="card">
        <button type="button" @click="count++">count is {{ count * 3 }}</button>
    </div>
</template>

<style scoped>
</style>
