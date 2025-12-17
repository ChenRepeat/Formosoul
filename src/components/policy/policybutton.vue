<template>
    <div class="member-page-button">
            <button class="policypage-button" @click="activeIndex = 0" :class="{ active: activeIndex === 0 }"><router-link to="/policy/payment">Payment</router-link></button>
            <button class="policypage-button" @click="activeIndex = 1" :class="{ active: activeIndex === 1 }"><router-link to="/policy/delivery">Delivery</router-link></button>
            <button class="policypage-button" @click="activeIndex = 2" :class="{ active: activeIndex === 2 }"><router-link to="/policy/returns">Returns</router-link></button>
            <button class="policypage-button" @click="activeIndex = 3" :class="{ active: activeIndex === 3 }"><router-link to="/policy/privacypolicy">Privacy Policy</router-link></button>
    </div>
    <Pagelinebar :linebarposition="300" :activeIndex="activeIndex" />
      <router-view></router-view>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import Pagelinebar from '../Member/Pagelinebar.vue';

const props = defineProps({
  page:{
    type: Number,
    validator: (value) => value >= 0 && value <= 4,
  },
});

const activeIndex = ref(props.page);
const route = useRoute();


const getcurrentpage = (path) => {
  if (path.includes('payment')) return 0;
  if (path.includes('delivery')) return 1;
  if (path.includes('returns')) return 2;
  if (path.includes('privacypolicy')) return 3;
  return 0;
}

activeIndex.value = getcurrentpage(route.path);

watch(
  () => route.path,
  (newPath) => {
    activeIndex.value = getcurrentpage(newPath);
  }
);

</script>

<style lang="scss" coped>
    .member-page-button{
        margin: 0 auto;
        width: 1200px;
        height: 48px;
        margin-top: 180px;
    }

    .policypage-button{
        width: 300px;
        height: 48px;
        border: 0;
        background-color: transparent;
        cursor: pointer;
        font-size: 16px;
    }
    .policypage-button > a{
        align-items: center;
        justify-content: center;
        display: flex;
        width: 100%;
        height: 100%;
        color: $color-fsCaption;
        text-decoration: none;
    }

    .policypage-button.active > a{
        color: $color-fsTitle;
    }
</style>