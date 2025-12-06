<template>
    <div class="member-page-button">
            <button class="page-button" @click="activeIndex = 0" :class="{ active: activeIndex === 0 }"><router-link to="/policy/information">Member information</router-link></button>
            <button class="page-button" @click="activeIndex = 1" :class="{ active: activeIndex === 1 }"><router-link to="/policy/changepassword">Change password</router-link></button>
            <button class="page-button" @click="activeIndex = 2" :class="{ active: activeIndex === 2 }"><router-link to="/policy/orderslist">Query Orders</router-link></button>
            <button class="page-button" @click="activeIndex = 3" :class="{ active: activeIndex === 3 }"><router-link to="/policy/mycollections">My Collections</router-link></button>
            <button class="page-button" @click="activeIndex = 4" :class="{ active: activeIndex === 4 }"><router-link to="/policy/coupons">Coupons</router-link></button>
    </div>
    <Pagelinebar :linebarposition="240" :activeIndex="activeIndex" />
      <router-view></router-view>
</template>

<script setup>
import { ref, watch } from 'vue';
import Pagelinebar from './Pagelinebar.vue';
import { useRoute } from 'vue-router';
import Information from './information/information.vue';
const props = defineProps({
  page:{
    type: Number,
    validator: (value) => value >= 0 && value <= 4,
  },
});

const activeIndex = ref(props.page);
const route = useRoute();


const getcurrentpage = (path) => {
  if (path.includes('information')) return 0;
  if (path.includes('changepassword')) return 1;
  if (path.includes('orderslist')) return 2;
  if (path.includes('mycollections')) return 3;
  if (path.includes('coupons')) return 4;
  return 0;
}

console.log(route.path);
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
    }

    .member-page-button{
      margin-top: 100px;
    }

    .page-button{
        width: 240px;
        height: 48px;
        border: 0;
        background-color: transparent;
        cursor: pointer;
        font-size: 16px;
    }
    .page-button > a{
        align-items: center;
        justify-content: center;
        display: flex;
        width: 100%;
        height: 100%;
        color: $color-fsCaption;
        text-decoration: none;
    }

    .page-button.active > a{
        color: $color-fsTitle;
    }
</style>