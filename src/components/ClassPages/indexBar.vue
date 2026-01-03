<script setup>
import { ref, onMounted, onUnmounted, nextTick, markRaw, watch,computed } from 'vue';
import { useclassesStore } from '@/stores/classes';
import IconHelmet from '../icons/SVG/IconHelmet.vue';
import IconFoot from '../icons/SVG/IconFoot.vue';
import IconHistory from '../icons/SVG/IconHistory.vue';
import IconBue from '../icons/SVG/IconBue.vue';
import IconPotion from '../icons/SVG/IconPotion.vue';
import IconCharm from '../icons/SVG/IconCharm.vue';
const classesStore = useclassesStore()


const activeIndex = ref(0);
const menuRef = ref(null);
const itemsRef = ref([]);
const borderY = ref(0);
const isResizing = ref(false);
const BORDER_WIDTH = 150; 
const vWidth = ref(0)
const toMotorPage = ref(2);
const toAnimaPage = ref(4);
const toHistoryPage = ref(5);
const toCharmPage = ref(9);
const toPotionPage = ref(10);
const toDivitionPage = ref(11);
const isHover = ref(false);
const newVSize=()=>{
 vWidth.value = window.innerWidth;
   if(vWidth.value < 770){
    toMotorPage.value = 3;
    toAnimaPage.value = 7;
    toHistoryPage.value = 10;
    toCharmPage.value = 17;
    toPotionPage.value = 19;
    toDivitionPage.value = 21;
  }else{
    toMotorPage.value = 2;
    toAnimaPage.value = 4;
    toHistoryPage.value = 5;
    toCharmPage.value = 9;
    toPotionPage.value = 10;
    toDivitionPage.value = 11;
  }
}
const menuData = computed(() => [
  { bodyBg: "#ffb457", itemBg: "#ff8c00", icon: markRaw(IconHelmet), toPage: toMotorPage.value },
  { bodyBg: "#ff96bd", itemBg: "#f54888", icon: markRaw(IconFoot), toPage: toAnimaPage.value },
  { bodyBg: "#9999fb", itemBg: "#4343f5", icon: markRaw(IconHistory), toPage: toHistoryPage.value },
  { bodyBg: "#ffe797", itemBg: "#e0b115", icon: markRaw(IconCharm), toPage: toCharmPage.value },
  { bodyBg: "#cffff1", itemBg: "#65ddb7", icon: markRaw(IconPotion), toPage: toPotionPage.value },
  { bodyBg: "#cffff1", itemBg: "#65ddb7", icon: markRaw(IconBue), toPage: toDivitionPage.value }
])
const offsetMenuBorder = () => {
  const activeItem = itemsRef.value[activeIndex.value];
  const menu = menuRef.value;
  if (!activeItem || !menu) return;
  const rect = activeItem.getBoundingClientRect();
  const menuRect = menu.getBoundingClientRect();
  const top = (rect.top - menuRect.top)  + (BORDER_WIDTH / 2) -70;
  borderY.value = top;
};

const clickItem = (index) => {
  activeIndex.value = index;
  offsetMenuBorder();
};


const turnPage =(num)=>{
  if(num == 0 && router.path == "/classes"){
    classesStore.setPage(num);
  }else{
    if(sessionStorage.getItem("bookLoaded") == 'true' ){
      classesStore.setPage(num);
    }else{
      sessionStorage.setItem("bookLoaded", 'true')
      classesStore.setPage(num);
      setTimeout(()=>sessionStorage.setItem("bookLoaded", 'false'),1200)
    }
  }
}
const onHover = ()=>{
  isHover.value = !isHover.value
}
onMounted(async () => {
  await nextTick();
  offsetMenuBorder();
  newVSize();
  window.addEventListener('resize', newVSize);

});
watch(() => classesStore.currentPage, (newPage) => {
 if(newPage >= 3 && newPage < 7){
  clickItem(0)
 }else if(newPage >= 7 && newPage < 9){
  clickItem(1)
 }else if(newPage >= 9 && newPage < 17){
  clickItem(2)
 }else if(newPage >= 17 && newPage < 19){
  clickItem(3)
 }else if(newPage >= 19 && newPage < 21){
  clickItem(4)
 }else if(newPage >= 21){
  clickItem(5)
 }
});
onUnmounted(() => {
  window.removeEventListener('resize', newVSize);}
);
</script>

<template>
  <div class="menu-case dp-flex">
    <menu class="menu vertical" ref="menuRef" :style="{ '--timeOut': isResizing ? 'none' : '' }"
    :class="{'menu-open':isHover}"
    @mouseenter="onHover"
    @mouseleave="onHover">
    >
      <button
        v-for="(item, index) in menuData"
        :key="index"
        :ref="el => itemsRef[index] = el"
        class="menu__item"
        :class="{ 'active': activeIndex === index }"
        :style="{ '--bgColorItem': item.itemBg }"
        @click="clickItem(index),turnPage(item.toPage)"
      >
        <component :is="item.icon" class="icon"/>
      </button>

      <div 
        class="menu__border" 
        :style="{ transform: `translate3d(0, ${borderY}px, 0) rotate(90deg)` }"
      ></div>
    </menu>
  </div>
  <div class="svg-container">
    <svg viewBox="0 0 202.9 45.5">
      <clipPath id="menu" clipPathUnits="objectBoundingBox" transform="scale(0.0049285362247413 0.021978021978022)">
        <path d="M6.7,45.5c5.7,0.1,14.1-0.4,23.3-4c5.7-2.3,9.9-5,18.1-10.5c10.7-7.1,11.8-9.2,20.6-14.3c5-2.9,9.2-5.2,15.2-7
          c7.1-2.1,13.3-2.3,17.6-2.1c4.2-0.2,10.5,0.1,17.6,2.1c6.1,1.8,10.2,4.1,15.2,7c8.8,5,9.9,7.1,20.6,14.3c8.3,5.5,12.4,8.2,18.1,10.5
          c9.2,3.6,17.6,4.2,23.3,4H6.7z"/>
      </clipPath>
    </svg>
  </div>
</template>

<style lang="scss" scoped>
.menu-case{
  height: 100%;
  position: absolute;
  align-items: center;
  left: 0;
    z-index: 100;

}
.menu.vertical {
  width: 8em;
  height: fit-content;
  flex-direction: column;
  display: flex;
  padding: 6em 0;
  position: relative;
  background-color: #1d1d27;
  justify-content: center;
  gap: 16px;
  left: -7em;
  transition: left 1s ease;
  @media screen and (max-width: 1200px) {
    left: -7.5em;
  }
  &.menu-open{
    left: 0em;
  }
  border-radius: 0 20px 20px 0;
  
  .menu__item {
    all: unset;
    height: 48px;
    z-index: 100;
    display: flex;
    cursor: pointer;
    position: relative;
    align-items: center;
    justify-content: center;
    transition: transform var(--timeOut, 0.7s);

    &.active {
      transform: translate3d(1.2em, 0, 0);
    }

    &::before {
      content: "";
      z-index: -1;
      width: 4.5em;
      height: 4.5em;
      border-radius: 50%;
      position: absolute;
      transform: scale(0);
      transition: background-color 0.7s, transform 0.7s;
    }

    &.active::before {
      transform: scale(1.3);
      background-color: var(--bgColorItem);
    }
  }

  .menu__border {
    position: absolute;
    transform-origin: center center; 
    left: 20px; 
    top: 0;
    width: 150px;
    height: 30px;
    background-color: #1d1d27;
    clip-path: url(#menu);
    will-change: transform;
    transition: transform var(--timeOut, 0.7s);
  }
}

.icon {
  stroke: transparent;
  color: $color-fsWhite;
  stroke-width: 1.2pt;
  stroke-linecap: round;
  stroke-linejoin: round;
  stroke-dasharray: 400; 
  stroke-dashoffset: 400;
}
.menu__item.active .icon {
  animation: strokeL 3s 0.5s ease forwards;

}
@keyframes strokeL {
    0%{
      stroke-dashoffset: 400;
      stroke: $color-fsGold;
      color: $color-fsWhite;
    }
    100% {
      stroke-dashoffset: 0;
      stroke: $color-fsGold;
      color: $color-fsGold;

    }

}
.svg-container { width: 0; height: 0; position: absolute; }
</style>