<script setup>
const all = ref([
  'https://drive.google.com/file/d/1ZR5q6iKhSpKWkrkcs9NC86UlV2s6CrWj/view?usp=drive_link',
  'https://drive.google.com/file/d/1UQtZnlnnNRxVi5W-fNJIuW8JXVw-GpL5/view?usp=drive_link'
])


</script>
<template>
  <h2 class="title">Professor Introduction</h2>
  <ul class="">
    <li><img v-for="photo in all" class="photo"></img></li>
  </ul>

  

</template>
<style scoped>
@media (min-width: 1024px) {
  .about {
    min-height: 100vh;
    display: flex;
    align-items: center;
  }
  .title{
    }
}
</style>
