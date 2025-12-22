<script setup>
import { ref, onMounted } from 'vue';

// 規則內容數據
const rules = {
  1:{
    title:"classes.motorRule1",
    content:"classes.motorRule2",
  },
  2:{
    title:"classes.motorRule3",
    content:"classes.motorRule4",
  },
  3:{
    title:"classes.motorRule5",
    content:"classes.motorRule6",
  },
  4:{
    title:"classes.motorRule7",
    content:"classes.motorRule8",
  },
  5:{
    title:"classes.motorRule9",
    content:"classes.motorRule10",
  },
}



onMounted(() => {

});
</script>

<template>
  <div class="rule-title dp-flex-col">
    <h5>{{$t('classes.motorGame')}}</h5>
    <div v-for="(rule, key) in rules" 
      :key="key">
    <p>{{ $t(rule.title) }}</p>
    <p>{{ $t(rule.content) }}</p>
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