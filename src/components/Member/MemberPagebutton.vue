<template>
    <div class="member-page-button">
            <button class="page-button" @click="activeIndex = 0" :class="{ active: activeIndex === 0 }"><router-link to="/Policy/information">Member information</router-link></button>
            <button class="page-button" @click="activeIndex = 1" :class="{ active: activeIndex === 1 }"><router-link to="/Policy/changepassword">Change password</router-link></button>
            <button class="page-button" @click="activeIndex = 2" :class="{ active: activeIndex === 2 }"><router-link to="/Policy/Orderslist">Query Orders</router-link></button>
            <button class="page-button" @click="activeIndex = 3" :class="{ active: activeIndex === 3 }"><router-link to="/Policy/mycollections">My Collections</router-link></button>
            <button class="page-button" @click="activeIndex = 4" :class="{ active: activeIndex === 4 }"><router-link to="/Policy/coupons">Coupons</router-link></button>
    </div>
    <Pagelinebar :activeIndex="activeIndex" />
      <router-view></router-view>
</template>

<script setup>
import { ref, watch } from 'vue';
import Pagelinebar from './Pagelinebar.vue';
import { useRoute } from 'vue-router';

const activeIndex = ref(0);
const route = useRoute();

const routeToIndex ={
  '/Policy/information': 0,
  '/Policy/changepassword': 1,
  '/Policy/Orderslist': 2,
  '/Policy/mycollections': 3,
  '/Policy/coupons': 4,
}

activeIndex.value = routeToIndex[route.path] ?? 0;

watch(
  () => route.path,
  (newPath) => {
    activeIndex.value = routeToIndex[newPath] ?? 0;
  }
);

</script>

<style lang="scss" coped>
    .member-page-button{
        margin: 0 auto;
        width: 1200px;
        height: 48px;
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