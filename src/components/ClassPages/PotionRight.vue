<script setup>
import { ref, onMounted } from 'vue';
import BasicButton from '../BasicButton.vue';


//Boolean
const isShow = ref(true)
const beforeStart = ref(true)
const beforeCook = ref(true)
//variables
//functions
const start= ()=>{
  isShow.value = false
  beforeStart.value = false
}

const cook=()=>{
  isShow.value = true
}

</script>
<template>
  <section>
    <h2 class="potion-right-title" v-show="isShow">Your Own Potion</h2>
    <div>
      <img src="../../../public/Classes/potions/glass.png" alt="">
    </div>
    <BasicButton class="potion-game-start btn-black" v-if="beforeStart"
    @click="start" >
      START
    </BasicButton>


    <BasicButton class="potion-game-cook btn-black" v-if="beforeCook"
    @click="cook">
      COOK
    </BasicButton>
  </section>
</template>