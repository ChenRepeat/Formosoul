<script setup>
import { ref, onMounted } from 'vue';

// 規則內容數據
const rules = [
    "1. Controls",
    " Use the Left and Right Arrow Keys, or drag the motorcycle with your mouse or finger to move across the road.",
    "2. Objective",
    " Reach the finish line within the time limit while avoiding all obstacles on the road.",
    "3. Obstacles & Damage",
    " Each collision with an obstacle reduces one health point. You have three health points. Losing all three results in game over.",
    "4. Winning Condition",
    " Successfully reach the end of the track before time runs out and with at least one remaining health point.",
    "5. Tips",
    " Stay alert, react quickly, and keep your motorcycle clear of incoming hazards to survive the run.",
];

// 響應式狀態：用於儲存當前應顯示的規則段落
const visibleRules = ref([]);
const ANIMATION_DELAY = 200; // 毫秒：每行出現的間隔時間

// 在組件掛載後開始逐行顯示動畫
onMounted(() => {
    rules.forEach((rule, index) => {
        // 使用 setTimeout 設置延時
        setTimeout(() => {
            // 將規則內容推送到 visibleRules 數組中，觸發 Vue 渲染
            visibleRules.value.push(rule);
        }, index * ANIMATION_DELAY); // 延時是 (索引 * 間隔時間)
    });
});
</script>

<template>
  <div class="rule-title">
    <h5>Rider Edition</h5>
    
    <p 
      v-for="(rule, index) in visibleRules" 
      :key="index"
      :class="{'fade-in': true}" 
    >
      {{ rule }}
    </p>

    </div>
</template>

<style lang="scss" scoped>

p {
  line-height: 2;
  text-align: left;
  margin-top: 20px;
  color: $color-fsContent;
  opacity: 0;
  transform: translateY(10px);
  transition: opacity 0.5s ease-out, transform 0.5s ease-in; 
}

/* 進入狀態：設置為不透明且位置正常 */
.fade-in {
  opacity: 1;
  transform: translateY(0);
}

.rule-title {
  padding: 0 30px;
  color: $color-fsTitle;
}

</style>