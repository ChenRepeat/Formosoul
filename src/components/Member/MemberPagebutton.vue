<template>
    <div class="members-page-button">
            <button class="memberpage-button" @click="activeIndex = 0" :class="{ active: activeIndex === 0 }"><router-link to="/member/information">{{$t('member.memberInfo')}}</router-link></button>
            <button class="memberpage-button" @click="activeIndex = 1" :class="{ active: activeIndex === 1 }"><router-link to="/member/changepassword">{{$t('member.PWChange')}}</router-link></button>
            <button class="memberpage-button" @click="activeIndex = 2" :class="{ active: activeIndex === 2 }"><router-link to="/member/orderslist">{{$t('member.myOrders')}}</router-link></button>
            <button class="memberpage-button" @click="activeIndex = 3" :class="{ active: activeIndex === 3 }"><router-link to="/member/mycollections">{{$t('member.myCollect')}}</router-link></button>
            <button class="memberpage-button" @click="activeIndex = 4" :class="{ active: activeIndex === 4 }"><router-link to="/member/coupons">{{$t('member.coupons')}}</router-link></button>
    </div>
    <Pagelinebar :linebarposition="linebar" :activeIndex="activeIndex" />
      <router-view></router-view>
</template>

<script setup>
import { computed, onUnmounted, ref, watch } from 'vue';
import Pagelinebar from './Pagelinebar.vue';
import { useRoute } from 'vue-router';
import { onMounted } from 'vue';

const props = defineProps({
  page:{
    type: Number,
    validator: (value) => value >= 0 && value <= 4,
  },
});


const activeIndex = ref(props.page);
const route = useRoute();
const windowWidth = ref(window.innerWidth);

const linebar = computed(() => {
  return windowWidth.value <= 1300 ? 180 : 240;
});

const updateWidth = () => {
  windowWidth.value = window.innerWidth;
};

onMounted(() => {
  window.addEventListener('resize', updateWidth);
});

onUnmounted(() => {
  window.removeEventListener('resize', updateWidth);
});

const getcurrentpage = (path) => {
  if (path.includes('information')) return 0;
  if (path.includes('changepassword')) return 1;
  if (path.includes('orderslist')) return 2;
  if (path.includes('mycollections')) return 3;
  if (path.includes('coupons')) return 4;
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
    .members-page-button{
        margin: 0 auto;
        width: 1200px;
        height: 48px;
        margin-top: 180px;
    }

    .memberpage-button{
        width: 240px;
        height: 48px;
        border: 0;
        background-color: transparent;
        cursor: pointer;
        font-size: 16px;
    }
    .memberpage-button > a{
        align-items: center;
        justify-content: center;
        display: flex;
        width: 100%;
        height: 100%;
        color: $color-fsCaption;
        text-decoration: none;
    }

    .memberpage-button.active > a{
        color: $color-fsTitle;
    }
    @media screen and (max-width: 1300px) {
        .members-page-button{
            width: 900px;
        }
        .memberpage-button{
            width: 180px;
        }
    }
</style>