<template>
  <h1>📗 Page Two</h1>
  <button @click="count++">點我：{{ count }}</button>
</template>

<script setup>
import { ref } from 'vue'
const count = ref(0)
</script>