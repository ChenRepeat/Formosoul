<script setup>
import { ref , computed, onMounted, onUnmounted, inject} from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/autoStore';
import { gsap } from 'gsap';
import { Draggable } from 'gsap/Draggable';
import { useLangStore } from '@/stores/lang';
const execLanguageChange = inject('execLanguageChange');

const props = defineProps({
  isBlackStyle: {
    type: Boolean,
    default: false
  }
});
const router = useRouter();
const authStore = useAuthStore();

const isMenuOpen = ref(false);
const isMemberMenuOpen = ref(false);

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
const isEnglish = computed({
  get: () => langStore.locale === 'en-US',
  set: (val) => langStore.setLanguage(val ? 'en-US' : 'zh-TW')
});
function toggleLang() {
  execLanguageChange(() => {
    isEnglish.value = !isEnglish.value;
  });}


function handleUserIconClick( e ){
 e.preventDefault();
 if(authStore.isLoggedIn){
  if (isMenuOpen.value) {
    isMenuOpen.value = false;
    setTimeout(() => {
      isMemberMenuOpen.value = true;
      
    }, 350);
  } else {
      isMemberMenuOpen.value = !isMemberMenuOpen.value;
  }
}else{
  isMenuOpen.value = false;
  authStore.openLoginModal();
  authStore.setmemberView('login');
  authStore.setloginView('loginpage');
 }
};

function toggleMenu() {
  if (!isMenuOpen.value && isMemberMenuOpen.value) {
    isMemberMenuOpen.value = false;
      setTimeout(() => {
        isMenuOpen.value = true;
      },350);
    } else {
      isMenuOpen.value = !isMenuOpen.value;
      isMemberMenuOpen.value = false;
  }
};
function closeMenu(){
  isMemberMenuOpen.value = false;
  isMenuOpen.value = false;

}
function handlelogout() {
  if(!confirm(`${authStore.user.name}確定要登出嗎?`)){
    return;
  }else{
    authStore.logout();
    isMemberMenuOpen.value = false;
    alert('登出惹');
  }
}
function onEnter(el, done) {

  gsap.set(el, { height: 0, opacity: 0, overflow: 'hidden' });
  
  gsap.to(el, {
    height: "auto",
    opacity: 1,
    duration: 0.5,
    ease: "power2.out",
    onComplete: done
  });

  gsap.from(el.querySelectorAll('li'), {
    x: -20,
    opacity: 0,
    stagger: 0.05,
    delay: 0.1
  });
}

function onLeave(el, done) {
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
    if (isMenuOpen.value || isMemberMenuOpen.value) {
      closeMenu();
    }
  }
};

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
  <div class="header-outer-case dp-flex">

    <div 
    ref="headerRef"
    class="header-link liquidGlass-wrapper dp-flex-col" 
    :class="{ open: isMenuOpen || isMemberMenuOpen ,'black': props.isBlackStyle }">

      <!-- 玻璃效果層 -->
      <div class="liquidGlass-effect"></div>
      <div class="liquidGlass-tint"></div>
      <div class="liquidGlass-shine"></div>

      <!-- 內容層 -->
      <div class="liquidGlass-content dp-flex" style="align-items: center; gap: 16px; width: auto;">
        
        <div class="header-lang-trigger dp-flex"  @click="toggleLang" :class="{ right: isEnglish }">
          <div class="header-lang-switcher" :class="{ right: isEnglish }"></div>
          <h6 class="trigger-lang">EN</h6>
          <h6 class="trigger-lang">ZH</h6>
        </div>

        <div class="header-icons-list dp-flex">
          <router-link to="/shoppingcart" class="no-i18n-anim"><font-awesome-icon icon="fa-solid fa-bag-shopping" class="header-icon"
             @click="closeMenu"/></router-link>
          <!-- <a href="/member"></a> -->
         
            <font-awesome-icon icon="fa-regular fa-circle-user" class="header-icon" @click="handleUserIconClick"/>

          <div class="hamburger-btn transition"
              @click="toggleMenu"
              :class="{ 'active': isMenuOpen }">
            <div class="dot dot1 transition"></div>
            <div class="dot dot2 transition"></div>
            <div class="dot dot3 transition"></div>
            <div class="dot dot4 transition"></div>
            <div class="dot dot5 transition"></div>
            <div class="dot dot6 transition"></div>
          </div>
          <!-- <font-awesome-icon icon="fa-solid fa-grip-vertical" class="header-icon draggable-icon" @mosueDown="drag"/> -->
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
        <li><router-link to="/member" @click="closeMenu"><h5>{{$t('nav.member')}}</h5></router-link></li>
        <li><router-link to="/member/information" @click="closeMenu"><h6>{{$t('nav.information')}}</h6></router-link></li>
        <li><router-link to="/member/changepassword" @click="closeMenu"><h6>{{$t('nav.changepassword')}}</h6></router-link></li>
        <li><router-link to="/member/orderslist" @click="closeMenu"><h6>{{$t('nav.orderslist')}}</h6></router-link></li>
        <li><router-link to="/member/mycollections" @click="closeMenu"><h6>{{$t('nav.mycollections')}}</h6></router-link></li>
        <li><router-link to="/member/coupons" @click="closeMenu"><h6>{{$t('nav.coupons')}}</h6></router-link></li>
        <hr class="memberhr">
        <!-- <li><basic-button class="btn-gray-fill" @click="handlelogout"><h5>logout</h5></basic-button></li> -->
        <li @click="handlelogout" class="logout"><h5>{{$t('nav.logout')}}</h5></li>
      </ul>
      </transition>
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
</template>

<style scoped lang="scss">
.header-outer-case {
  padding: 0 40px 0 60px;
  justify-content: end;
  position: fixed;
  height: auto;
  width: 100%;
  transform: translateY(16px);
  right:  0;
  z-index: 1000;
  transition: all 0.5s ease;
  pointer-events: none;
}



img { object-fit: none; }

.trigger-lang { color: $color-fsWhite; margin: 0; }

.header-lang-trigger {
  width: 85px;
  height: 40px;
  border: 1px solid $color-fsWhite;
  padding: 0 10px;
  justify-content: space-around;
  align-items: center;
  border-radius: 20px;
  position: relative;
  cursor: pointer;
  background-color: unset;
  transition: all 1s 0.3s;
}
.header-lang-trigger.right{
  border: 1px solid $color-fsTitle;
  background-color: $color-fsTitle;
}
.header-lang-switcher{
  width: 30px;
  height: 30px;
  position: absolute;
  background-color: $color-fsTitle;
  z-index: 1001;
  border-radius: 50%;
  top: 4px;
  left: 10px;
  transition: all 1s ease;
}
.header-lang-switcher.right{
  left: 43px;
  background-color: $color-fsWhite;

}
.header-link {
  border-radius: 36px;
  padding: 12px 45px;
  height: auto;
  min-height: 50px;
  gap: 16px;
  position: absolute;
  flex-direction: column; 
  overflow: hidden;
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
  pointer-events: all;
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
}

.liquidGlass-effect { position: absolute; inset: 0; backdrop-filter: blur(4px); filter: url(#glass-distortion); z-index: 0; }
.liquidGlass-tint { position: absolute; inset: 0; background: rgba(255, 255, 255, 0.28); z-index: 1; }
.liquidGlass-shine { position: absolute; inset: 0; box-shadow: inset 2px 2px 1px rgba(255,255,255,0.4), inset -2px -2px 2px rgba(255,255,255,0.2); z-index: 2; }

.liquidGlass-content { position: relative; z-index: 10; align-items: center; gap: 16px; width: 100%; }

.header-icons-list { gap: 20px; align-items: center; 
  > button{ 
  background-color: transparent; 
  border: 0;
  }
}

.header-icon { color: $color-fsWhite; font-size: 32px; cursor: pointer; }
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
  h5{
    text-transform: capitalize;
  }
  h6{
    text-transform: capitalize;
    text-indent: 1em;
  }
}

.burger-list li a { color: $color-fsWhite; text-decoration: none; padding: 8px 12px; display: block; border-radius: 4px; transition: background-color 0.3s; }
.burger-list li a:hover { background-color: rgba(255, 255, 255, 0.1); }
.burger-list.active { opacity: 1; transform: translateY(0); z-index: 11;height: auto;}

/* 漢堡按鈕 */
.hamburger-btn { justify-content: center; gap: 8px; align-items: end; cursor: pointer; height: 24px;width: 24px;position: relative;}
.transition { transition: 0.5s ease-out; }
.dot{
  width: 5px;height: 5px;position: absolute;background-color: $color-fsWhite;
}
.dot1{left: 2px;top: 0px;border-radius: 50%;}
.dot2{right: 2px;top: 0px;}
.dot3{left: 2px;bottom: 0px;border-radius: 50%}
.dot4{right: 2px;bottom: 0px;}
.dot5{left: 2px;top: 9px;}
.dot6{right: 2px;top: 9px;border-radius: 50%}
.hamburger-btn.active{
  .dot{width: 30px;height: 2.5px;transform-origin:0 50%}
  .dot5,.dot6{width: 0;height: 0;opacity: 0;}
  .dot1,.dot3{transform-origin:0 50%}
  .dot2,.dot4{transform-origin:100% 50%}
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

.black{
  .trigger-lang { color: $color-fsTitle;}
  .header-lang-trigger {border: 1px solid $color-fsTitle;background-color: unset;}
  .header-lang-trigger.right{
    border: 1px solid $color-fsWhite;
    background-color: $color-fsTitle;
  }
  .right .trigger-lang { color: $color-fsWhite;}
  .header-lang-switcher{background-color: $color-fsTitle;}
  .header-lang-switcher.right{background-color: $color-fsWhite;}
  .header-icon { color: $color-fsTitle;}
  .burger-list li a { color: $color-fsTitle;}
  .logout > h5 { color: $color-fsTitle;}
  .dot { background-color: $color-fsTitle;}
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
@media screen and (max-width: 1200px) {
  .header-link{
    padding: 6px 20px;
    align-items: center;
    height: auto; 
    min-height: 50px; 
    max-height: 90vh; 
    overflow-y: auto;  
  }
  .header-icons-list{
    gap: 8px;
  }
  .header-icon{
    font-size: 22px;
  }
  .hamburger-btn{
    transform: scale(0.8);
  }
  
}

</style>
