<script setup>
import { ref, onMounted } from 'vue';

// 規則內容數據
const rules = {
  1:{
    title:"1. Controls",
    content:" Use the Left and Right Arrow Keys, or drag the motorcycle with your mouse or finger to move across the road.",
  },
  2:{
    title:"2. Objective",
    content:" Reach the finish line within the time limit while avoiding all obstacles on the road.",
  },
  3:{
    title:"3. Obstacles & Damage",
    content:" Each collision with an obstacle reduces one health point. You have three health points. Losing all three results in game over.",
  },
  4:{
    title:"4. Winning Condition",
    content:" Successfully reach the end of the track before time runs out and with at least one remaining health point.",
  },
  5:{
    title:"5. Tips",
    content:" Stay alert, react quickly, and keep your motorcycle clear of incoming hazards to survive the run.",
  },
}



onMounted(() => {

});
</script>

<template>
  <div class="rule-title dp-flex-col">
    <h5>Rider Edition</h5>
    <div v-for="(rule, key) in rules" 
      :key="key">
    <p>{{ rule.title }}</p>
    <p>{{ rule.content }}</p>
    </div>
    

    </div>
</template>

<style lang="scss" scoped>

p {
  line-height: 2;
  text-align: left;
  color: $color-fsContent;
}

.rule-title {
  color: $color-fsTitle;
  gap: 20px;
}

</style>