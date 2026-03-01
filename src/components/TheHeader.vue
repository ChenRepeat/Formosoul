<script setup>
import { ref , computed, onMounted, onUnmounted, inject, nextTick, watch} from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/autoStore';
import { gsap } from 'gsap';
import { Draggable } from 'gsap/Draggable';
import { useLangStore } from '@/stores/lang';
import { useMemberStore } from '@/stores/member';
import { useCartStore } from '@/stores/cart';

const props = defineProps({
  isBlackStyle: {
    type: Boolean,
    default: false
  }
});
const router = useRouter();
const authStore = useAuthStore();
const memberStore = useMemberStore();
const cartstore = useCartStore();          // 購物車 data
const isMenuOpen = ref(false);
const isMemberMenuOpen = ref(false);
const execLanguageChange = inject('execLanguageChange');
const dragHandleRef = ref(null);
// 拖曳
gsap.registerPlugin(Draggable);

const langStore = useLangStore();
// 原本的寫法
// const isEnglish = ref(true)
// function toggleLang(){
//   isEnglish.value = !isEnglish.value;
//   if(isEnglish.value){
//     langStore.setLanguage('en-US');
//   }else{
//     langStore.setLanguage('zh-TW');
//   }
// }
// 第二版雙語系切換
// const isEnglish = computed({
//   get: () => langStore.locale === 'en-US',
//   set: (val) => langStore.setLanguage(val ? 'en-US' : 'zh-TW')
// });
// function toggleLang() {
//   execLanguageChange(() => {
//     isEnglish.value = !isEnglish.value;
//   });
// }
// 第三版 多語系切換
// 語系選項清單 如有需要可在 option裡面增加
const isLangMenuOpen = ref(false); // 控制語系選單
const langRollerRef = ref(null);   // GSAP 動畫目標
const langOptions = [
  { label: '繁體中文', short: 'ZH', value: 'zh-TW', index: 0 ,bgimg:'Lang-Flag/flag-taiwan.svg'},
  { label: 'ENGLISH', short: 'EN', value: 'en-US', index: 1 ,bgimg:'Lang-Flag/flag-uk.svg' },
  { label: '日本語', short: 'JA', value: 'ja-JP', index: 2  ,bgimg:'Lang-Flag/flag-jp.svg'}
];
const displayedBgImg = ref('');
const isBgVisible = ref(true);
const currentLang = computed(() => langStore.locale);
let currentVirtualIndex = 0; 
let currentIndex = 0;

watch(currentLang, (newVal) => {
  const targetIndex = langOptions.find(opt => opt.value === newVal)?.index || 0;
  const logicalTotal = langOptions.length; 
  let diff = targetIndex - currentIndex;
  if (diff <= 0) {
    diff += logicalTotal;
  }
  const extraLaps = 3; 
  const totalSteps = diff + (extraLaps * logicalTotal);
  currentVirtualIndex += totalSteps;
  currentIndex = targetIndex;
  const visualTotalItems = logicalTotal * 2;
  const itemStepPercent = 100 / visualTotalItems;
  const targetYPercent = -(currentVirtualIndex * itemStepPercent);
  const targetOption = langOptions.find(opt => opt.value === newVal);
  isBgVisible.value = false;
  if (langRollerRef.value) {
    gsap.to(langRollerRef.value, {
      yPercent: targetYPercent, 
      duration: 1.2,
      ease: "power3.out", 
      
      modifiers: {
        yPercent: gsap.utils.unitize((y) => {
          return parseFloat(y) % 50; 
        })
      },
      onComplete: () => {
        if (targetOption && targetOption.bgimg) {
          displayedBgImg.value = `url(${targetOption.bgimg})`;
        }
        isBgVisible.value = true;
      }
    });
  }
});

function selectLang(langValue) {
  if (langValue === currentLang.value) {
    isLangMenuOpen.value = false;
    return;
  }
  isLangMenuOpen.value = false;
  execLanguageChange(() => {
    langStore.setLanguage(langValue);
  });
}

function handlelogout() {
  if(!confirm(`${authStore.user.name || 'USER' }確定要登出嗎?`)){
    return;
  }else{
    authStore.logout();
    isMemberMenuOpen.value = false;
    window.localStorage.removeItem('game_progress')
    router.push('/');
  }
}
function onEnter(el, done) {
  moveHeaderToTop();

  gsap.set(el, { height: 0, opacity: 0, overflow: 'hidden' });
  
  gsap.to(el, {
    height: "auto",
    opacity: 1,
    duration: 0.5,
    ease: "power2.out",
    onComplete: done,
  });

  gsap.from(el.querySelectorAll('li'), {
    x: -20,
    opacity: 0,
    stagger: 0.05,
    delay: 0.1
  });
}

function onLeave(el, done) {
  restoreHeaderPosition();
  gsap.to(el, {
    height: 0,
    opacity: 0,
    duration: 0.4,
    ease: "power2.in",
    onComplete: done
  });
}
const headerRef = ref(null);

const handleClickOutside = (e) => {
  if (headerRef.value && !headerRef.value.contains(e.target)) {
    if (isMenuOpen.value || isMemberMenuOpen.value || isLangMenuOpen.value) {
      closeMenu();
    }
  }
};

const isNameNull = computed(() => {
  const username = localStorage.getItem('user');
  if(!username) return true;
  const nameobj = JSON.parse(username);
  return !nameobj.name;
})
const isDrag = ref(false)
let draggableInstance = null;
const lastYPosition = ref(0);
const initDraggable = () => {
  if (!headerRef.value || !dragHandleRef.value) return;
  draggableInstance = Draggable.create(headerRef.value, {
    type: "x,y",
    trigger: dragHandleRef.value,
    bounds: ".drag-sandbox",
    inertia: true,
    edgeResistance: 0.65,
    zIndexBoost: false,
    onPress: function() {
      dragHandleRef.value.classList.add('dragging');
    },
    onRelease: function() {
      dragHandleRef.value.classList.remove('dragging');
    },
    onDragEnd: function() {
      lastYPosition.value = draggableInstance.y;
      isDrag.value = false;
    }
  })[0];
};
const moveHeaderToTop = () => {
  if (!draggableInstance) return;
  if (!isMenuOpen.value && !isMemberMenuOpen.value) {
    lastYPosition.value = draggableInstance.y;
  }
  gsap.to(headerRef.value, {
    y: -16,
    duration: 0.4,
    ease: "power2.out",
    overwrite: true,
    onUpdate: () => {
        if(draggableInstance) draggableInstance.update();
    }
  });
};

const restoreHeaderPosition = () => {
  if (!draggableInstance) return;

  gsap.to(headerRef.value, {
    y: lastYPosition.value,
    duration: 0.4,
    ease: "power2.out",
    overwrite: true,
    delay:0.4,
    onUpdate: () => {
        if(draggableInstance) draggableInstance.update();
    }
  });
};

// 選單開關按鈕統邏輯區
const MENU_DELAY = 350;
function closeAllMenus() {
  if (isMenuOpen.value) {
    restoreHeaderPosition(); 
  }
  
  isMenuOpen.value = false;
  isMemberMenuOpen.value = false;
  isLangMenuOpen.value = false;
}
function handleMenuSwitch(targetRef) {
  if (targetRef.value) {
    closeAllMenus();
    return;
  }

  const isAnyOpen = isMenuOpen.value || isMemberMenuOpen.value || isLangMenuOpen.value;
  if (isAnyOpen) {
    closeAllMenus();
    setTimeout(() => {
      targetRef.value = true;
    }, MENU_DELAY);
  } else {
    targetRef.value = true;
  }
}

const toggleMenu=()=> handleMenuSwitch(isMenuOpen);

const toggleLangMenu = () => handleMenuSwitch(isLangMenuOpen);

function handleUserIconClick(e) {
  if (e) e.preventDefault();
  if (authStore.isLoggedIn) {
    handleMenuSwitch(isMemberMenuOpen);
  } else {
    closeAllMenus();
    authStore.openLoginModal();
    authStore.setmemberView('login');
    authStore.setloginView('loginpage');
  }
}
const closeMenu = ()=> closeAllMenus();
// 選單開關按鈕統邏輯區

onMounted(() => {
  initDraggable();
  document.addEventListener('click', handleClickOutside);

  nextTick(() => {
    const savedLocale = langStore.locale; 
    const initIndex = langOptions.findIndex(opt => opt.value === savedLocale);
    const targetIndex = initIndex !== -1 ? initIndex : 0;
    currentIndex = targetIndex;
    currentVirtualIndex = targetIndex;
    const logicalTotal = langOptions.length;
    const visualTotalItems = logicalTotal * 2; 
    const initPercent = -(targetIndex * (100 / visualTotalItems));
    if (langRollerRef.value) {
      gsap.set(langRollerRef.value, { 
        yPercent: initPercent 
      });
    }
    const initOption = langOptions.find(opt => opt.value === savedLocale);
    if (initOption && initOption.bgimg) {
      displayedBgImg.value = `url('${initOption.bgimg}')`;
    }
  })
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div class="drag-sandbox">
  <div class="header-outer-case dp-flex">

    <div 
    class="header-link liquidGlass-wrapper dp-flex-col" 
    ref="headerRef"
    :class="{ open: isMenuOpen || isMemberMenuOpen ,'black': props.isBlackStyle }"
    @mousedown.stop
    @touchstart.stop>

      <!-- 玻璃效果層 -->
      <div class="liquidGlass-effect"></div>
      <div class="liquidGlass-tint"></div>
      <div class="liquidGlass-shine"></div>

      <!-- 內容層 -->
      <div class="liquidGlass-content dp-flex" style="align-items: center; width: auto;">
        
        <div class="header-lang-trigger dp-flex" 
          @click.stop="toggleLangMenu" 
          :class="{ 'active': isLangMenuOpen }">
          <div class="lang-bg-layer" 
            :class="{ 'show': isBgVisible }" 
            :style="{ backgroundImage: displayedBgImg }">
          </div>
          <div class="lang-roller-window">
            <div class="lang-roller-list" ref="langRollerRef">
              <p v-for="(opt, i) in [...langOptions, ...langOptions]" 
                :key="i"
                class="trigger-lang dp-flex">
                {{ opt.short }}
            </p>
            </div>
          </div>
        </div>
        <div class="header-icons-list dp-flex">
          <router-link to="/shoppingcart" class="no-i18n-anim ">
            <font-awesome-icon icon="fa-solid fa-bag-shopping" class="header-icon cart-icon"
            @click="closeMenu"/>
            <span v-if=" cartstore.totalQty > 0 " class="cart-qty">{{ cartstore.totalQty }}</span>
          </router-link>
          <font-awesome-icon icon="fa-regular fa-circle-user" class="header-icon" @click="handleUserIconClick" v-if="!authStore.isLoggedIn"/>
          <font-awesome-icon icon="fa-solid fa-hat-wizard" class="header-icon" @click="handleUserIconClick" v-else/>
          <div class="hamburger-btn transition"
            @click="toggleMenu"
            @mousedown.stop
            @touchstart.stop
            :class="{ 'active': isMenuOpen}">
            <div v-for="(index) in 6 " :class="`dot${index}`" class="dot transition"></div>
          </div>
          <div class="dragIcon dp-flex" 
          ref="dragHandleRef" 
          >
            <div class="draggerdot"></div>
            <div class="arrow" v-for="(index) in 4" :class="`arrow${index}`">
              <div class="arrow-bar"></div>
            </div>
          </div>
        </div>
      </div>
      <transition
        @enter="onEnter"
        @leave="onLeave" 
        :css="false"
      >
      <ul 
      v-if="isMenuOpen"
      class="burger-list" :class="{ 'active': isMenuOpen }">
        <li><router-link to="/"  @click="closeMenu"><h5>{{$t('nav.home')}}</h5></router-link></li>
        <li><router-link to="/news"  @click="closeMenu"><h5>{{$t('nav.news')}}</h5></router-link></li>
        <li><router-link to="/annualevent"  @click="closeMenu"><h5>{{$t('nav.annualEvent')}}</h5></router-link></li>
        <li><router-link to="/classes"  @click="closeMenu"><h5>{{$t('nav.classes')}}</h5></router-link></li>
        <li><router-link to="/professorsintroduction"  @click="closeMenu"><h5>{{$t('nav.professor')}}</h5></router-link></li>
        <li><router-link to="/survivalguide"  @click="closeMenu"><h5>{{$t('nav.survivalGuide')}}</h5></router-link></li>
        <li><router-link to="/shop"  @click="closeMenu"><h5>{{$t('nav.diagonAlley')}}</h5></router-link></li>
        <li><router-link to="/about"  @click="closeMenu"><h5>{{$t('nav.about')}}</h5></router-link></li>
        <li><router-link to="/policy"  @click="closeMenu"><h5>{{$t('nav.policy')}}</h5></router-link></li>
      </ul>
      </transition>

      <transition
        @enter="onEnter"
        @leave="onLeave"
        :css="false"
      >
      <ul v-if="isMemberMenuOpen && !isMenuOpen"
      class="burger-list member-list"
      :class="{ 'active': isMemberMenuOpen }">
        <li class="dp-flex member-data-case">
          <div class="head-shot-case" v-if="memberStore.imgURL !=''"><img :src="memberStore.imgURL" alt=""></div>
          <div class="head-shot-case" v-else><font-awesome-icon icon="fa-regular fa-circle-user" class="memberListIcon"/></div>
          <div>
            <p>{{$t('nav.welcome')}}</p>
            <h6>{{ memberStore.memberData.tempName }}</h6>
          </div>
        </li>
        <hr class="memberhr">
        <li><router-link to="/member/information" @click="closeMenu"><h6>{{$t('nav.information')}}</h6></router-link></li>
        <li><router-link to="/member/changepassword" @click="closeMenu"><h6>{{$t('nav.changepassword')}}</h6></router-link></li>
        <li><router-link to="/member/orderslist" @click="closeMenu"><h6>{{$t('nav.orderslist')}}</h6></router-link></li>
        <li><router-link to="/member/mycollections" @click="closeMenu"><h6>{{$t('nav.mycollections')}}</h6></router-link></li>
        <li><router-link to="/member/coupons" @click="closeMenu"><h6>{{$t('nav.coupons')}}</h6></router-link></li>
        <li v-if="parseInt(memberStore.memberData.role) === 1"><router-link to="/admin" @click="closeMenu" target="_blank"><h6>{{$t('nav.admin')}}</h6></router-link></li>
        <hr class="memberhr">
        <li @click="handlelogout" class="logout"><h5>{{$t('nav.logout')}}</h5></li>
      </ul>
      </transition>
      <Transition 
        @enter="onEnter"
        @leave="onLeave"
        :css="false">
        <ul v-if="isLangMenuOpen" class="burger-list active">
          <li v-for="opt in langOptions" :key="opt.value">
            <a href="#" @click.prevent.stop="selectLang(opt.value)" 
              :class="{ 'current-lang': currentLang === opt.value }">
              <h6 class="langli">{{ opt.label }}</h6>
            </a>
          </li>
        </ul>
      </Transition>
    </div>
  </div>

  <!-- SVG FILTER 玻璃遮罩 -->
  <svg style="display:none">
    <filter id="glass-distortion" x="0" y="0" width="100%" height="100%">
      <feTurbulence type="fractalNoise" baseFrequency="0.01" numOctaves="1" seed="5" result="turbulence" />
      <feGaussianBlur in="turbulence" stdDeviation="2" result="softMap" />
      <feDisplacementMap in="SourceGraphic" in2="softMap" scale="200" xChannelSelector="R" yChannelSelector="G"/>
    </filter>
  </svg>
  </div>
</template>

<style scoped lang="scss">
.drag-sandbox {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 3000;
  pointer-events: none;
  transform: none !important; 
  overflow: hidden;
}
.header-outer-case {
  padding: 0 40px 0 60px;
  justify-content: end;
  position: fixed;
  height: auto;
  width: 100%;
  top: 16px;
  right:  0;
  z-index: 3000;
  transition: all 0.5s ease;
  pointer-events: none;
  position: absolute;
}

.head-shot-case{
  width: 50px;
  height: 50px;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
  img{
    width: 100%;
    height: auto;
    inset: 0;
    position: absolute;
    object-fit: contain;
  }
}
.member-data-case{
  gap: 28px;
  align-items: center;
  justify-content: left;
  padding: 8px 12px;
  @media screen and (max-width: 1366px){
    flex-direction: column;
  }
}
img { object-fit: none; }

.header-lang-trigger {
  height: 30px;
  width: 30px;
  justify-content: center;
  align-items: center;
  border-radius: 50%;
  position: relative;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s ease;
  &.active {
    box-shadow: 0 0 4px $color-fsWhite;
  }

  @media screen and (max-width: 1366px){
    height: 30px;
    width: 30px;
  }
  .lang-bg-layer {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    opacity: 0;
    transition: opacity 0.4s ease;
    &.show {
      opacity: 0.7;
    }
  }
}
.lang-roller-window {
  height: 30px;
  width: 30px;
  overflow: hidden;
  position: relative;
  pointer-events: none;
  
  @media screen and (max-width: 1366px){
    height: 30px;
  }
}

.lang-roller-list {
  display: flex;
  flex-direction: column;
  width: 100%;
  
}

.trigger-lang {
  color: $color-fsWhite;
  height: 30px;      
  line-height: 30px; 
  display: flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
  filter: drop-shadow(0 0 2px black);
  @media screen and (max-width: 1366px){
    height: 28px;
    line-height: 28px;
  }
}
.header-link {
  border-radius: 36px;
  padding: 12px 45px;
  height: auto;
  gap: 16px;
  position: absolute;
  flex-direction: column; 
  overflow: hidden;
  pointer-events: all;
  @media screen and (max-width: 1366px){
    border-radius: 18px;
    padding: 6px 20px;
    align-items: center;
    height: auto; 
    max-height: 90vh; 
    overflow-y: auto; 
    &.open{
      padding-top: 6px;
    } 
  }
}

/* --- XXXXX 玻璃效果 勿動 XXXXX --- */
.liquidGlass-wrapper {
  position: relative;
  border-radius: 36px;
  overflow: hidden;
  flex-direction: column;
  background: rgba(255, 255, 255, 0.12);
  box-shadow: 
    inset 0 2px 6px rgba(255,255,255,0.2),
    0 4px 10px rgba(0,0,0,0.2);
  backdrop-filter: blur(8px); 
    @media screen and (max-width: 1366px){
    justify-content: center;
    border-radius: 18px;
    padding: 6px 20px;
    align-items: center;
    height: auto; 
    max-height: 90vh; 
    overflow-y: auto;  
  }
}
.memberListIcon{
  color: $color-fsTitle;
  width: 100%;
  height: 100%;
}
.liquidGlass-effect { position: absolute; inset: 0; backdrop-filter: blur(4px); filter: url(#glass-distortion); z-index: 0; }
.liquidGlass-tint { position: absolute; inset: 0; background: rgba(255, 255, 255, 0.28); z-index: 1; }
.liquidGlass-shine { position: absolute; inset: 0; box-shadow: inset 2px 2px 1px rgba(255,255,255,0.4), inset -2px -2px 2px rgba(255,255,255,0.2); z-index: 2; }

.liquidGlass-content { position: relative; z-index: 10; align-items: center; gap: 20px; width: 100%; 
  @media screen and (max-width: 1366px){
    padding: 4px 16px;
    gap: 8px;
  }}

.header-icons-list { gap: 20px; align-items: center; 
  position: relative;
  > button{ 
  background-color: transparent; 
  border: 0;
  }

  & .cart-qty{
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: $color-fsRed;
    color: $color-fsWhite;
    text-align: center;
    line-height: 14px;
    font-size: 10px;
    position: absolute;
    top: 50%;
    left: 24px;
  }
}

.header-icon { color: $color-fsWhite; font-size: 32px; cursor: pointer;
&.cart-icon{

  transform:scale(0.9) translateY(1px);
} }
.draggable-icon { cursor: move ; position: relative; z-index: 1001;}

.burger-list {
  // font-weight: 200;
  list-style: none;
  padding: 10px;
  margin: 0;
  background: transparent;
  border-radius: 12px;
  width: 100%;
  z-index: -2;
  transition: color 0s;
  color: $color-fsWhite;

  h5{
    text-transform: capitalize;
  }
  h6{
    text-transform: capitalize;
    text-indent: 1em;
  }

  .langli{
    text-indent: unset;

  }
  
}
.member-data-case h6{
    text-indent: unset;
}
.burger-list li a { color: $color-fsWhite; text-decoration: none; padding: 8px 12px; display: block; border-radius: 4px; transition: background-color 0.3s; }
.burger-list li a:hover { background-color: rgba(255, 255, 255, 0.1); }
.burger-list.active { opacity: 1; transform: translateY(0); z-index: 11;height: auto;}

/* 漢堡按鈕 */
.hamburger-btn { 
  justify-content: center; 
  gap: 8px;
  align-items: end;
  cursor: pointer;
  height: 24px;
  width: 24px;
  position: relative;
  overflow: hidden;
  @media screen and (max-width: 1366px) {
    transform: scale(0.75);
  }
}
.transition { 
  transition: 0.5s ease-out ,color 0s;
}
.dot{
  width: 5px;height: 5px;position: absolute;background-color: $color-fsWhite;
}
.dot1{left: 4px;top: 0px;border-radius: 50%;}
.dot2{right: 4px;top: 0px;}
.dot3{left: 4px;bottom: 0px;border-radius: 50%}
.dot4{right: 4px;bottom: 0px;}
.dot5{left: 4px;top: 9px;}
.dot6{right: 4px;top: 9px;border-radius: 50%}
.hamburger-btn.active{
  .dot{width: 38px;height: 3px;transform-origin:0 50%}
  .dot5,.dot6{width: 0;height: 0;opacity: 0;}
  .dot1,.dot3{left: 0px;transform-origin:5% 50%}
  .dot2,.dot4{right: 0px;transform-origin:95% 50%}
  .dot1{border-radius: unset;transform: rotate(45deg);}
  .dot3{border-radius: unset;transform: rotate(-45deg);}
  .dot2{border-radius: unset;transform: rotate(-45deg);}
  .dot4{border-radius: unset;transform: rotate(45deg);}
}

.menu-slide-enter-active,
.menu-slide-leave-active {
  transition: opacity 0.2s ease, transform 0.2s ease;
  height: auto;

}

.menu-slide-enter-from,
.menu-slide-leave-to {
  opacity: 0;
  transform: translateY(100%);
  height: 0;

}




.logout{
    padding: 8px 12px;
    display: block;
    border-radius: 4px;
    transition: background-color 0.3s;
    cursor: pointer;
}

.logout > h5{
  color: $color-fsWhite;
}

.logout:hover{
  background-color: rgba(255, 255, 255, 0.1);
}

.logout.active { opacity: 1; transform: translateY(0); z-index: 11;}

.memberhr{
  border: none; 
  border-top: 1px solid $color-fsCaption;
}
@media screen and (max-width: 1366px) {
  .header-icons-list{
    gap: 8px;
  }
  .header-icon{
    font-size: 24px;
  }
  h5{
    font-size: 2.0rem;
  }
  h6{
    font-size: 1.8rem;

  }

  .header-icons-list {  
    & .cart-qty{
      left: 16px;
    }
  }
}
.dragIcon{
  width: 24px;
  height: 24px;
  position: relative;
  justify-content: center;
  align-items: center;
  transition: 0.5s all ease;
  .draggerdot{
    border-radius: 50%;
    width: 4px;
    height: 4px;
    background-color: $color-fsWhite;
    transition: 1s all ease;

  }
  .arrow{
    position: absolute;
    width: 30%;
    height: 30%;
    border-top: 2px solid $color-fsWhite;
    border-left: 2px solid $color-fsWhite;
    overflow: hidden;
    transition: 0.5s all ease;

    .arrow-bar{
      width: 150%;
      height: 2px;
      transform: rotate(45deg);
      background-color: $color-fsWhite;
      transform-origin: 18% 20%;
    }
  }
  .arrow1{top: 1%;left: 35%;transform: rotate(45deg);}
  .arrow2{bottom: 1%;left: 35%;transform: rotate(225deg);}
  .arrow3{top: 35%;left: 1%;transform: rotate(-45deg);}
  .arrow4{top: 35%;right: 1%;transform: rotate(135deg);}
  &.dragging{
    .draggerdot{background-color: $color-fsTitle;}
    .arrow {
      border-top: 2px solid $color-fsTitle;
      border-left: 2px solid $color-fsTitle;
      .arrow-bar{background-color: $color-fsTitle;}
    }
    .arrow1{top: -10%;}
    .arrow2{bottom: -10%;}
    .arrow3{left: -10%;}
    .arrow4{right: -10%;}

  }
  @media screen and (max-width: 1366px){
   transform: scale(0.75);
  }
}

.black{
  .trigger-lang { color: $color-fsTitle;filter: unset;}
  .header-lang-trigger {background-color: unset;}
  .header-icon { color: $color-fsTitle;}
  .burger-list{ color: $color-fsTitle;}
  .burger-list li a { color: $color-fsTitle;}
  .logout > h5 { color: $color-fsTitle;}
  .dot { background-color: $color-fsTitle;}
  .dragIcon{
    .draggerdot{background-color: $color-fsTitle;}
    .arrow{
      border-top: 2px solid $color-fsTitle;
      border-left: 2px solid $color-fsTitle;
      .arrow-bar{background-color: $color-fsTitle;}
    }
    &.dragging{
      .draggerdot{background-color: $color-fsWhite;}
      .arrow {
        border-top: 2px solid $color-fsWhite;
        border-left: 2px solid $color-fsWhite;
        .arrow-bar{background-color: $color-fsWhite;}
      }
    }
  }
}
</style>
