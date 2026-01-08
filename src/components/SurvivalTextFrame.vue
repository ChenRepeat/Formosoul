<script setup>
// import BasicButton from './BasicButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';


// ================ 試試看用 props 寫法 ================
const props = defineProps({
  i18nText: {type: Boolean, default: false},
  text: { type: String, default:'' },
  description: { type: String, default: '' },
  width: { type: String, default: '280px'},
  height: { type: String, default: '250px'},
  align: {type: String, default: 'left'},
  tag: {type: String, default: 'p'},
  showButton: { type: Boolean, default: true }
})

const emit = defineEmits(['click'])
</script>


<template>
<div class="survival-text-frame" :style="{ width: props.width, maxWidth: '100%', minHeight: props.height, textAlign: props.align }">

  <template v-if="props.description">
    <h4 v-if="props.i18nText" class="text-content title-style" :style="{textAlign: props.align}">
       {{ $t(props.text) }} 
    </h4>
    <h4 v-else class="text-content title-style" :style="{textAlign: props.align}">
       {{ props.text }}
    </h4>
    <p class="desc-content" :style="{textAlign: props.align}">
      {{ props.description }}
      <slot name="descriptionExtra"></slot>
    </p>
  </template>

  <template v-else-if ="props.i18nText">
    <h4 class="text-content title-style i18nText" :style="{textAlign: props.align}">
       {{ $t(props.text) }} 
    </h4>
  </template>

  <template v-else>
    <component 
    :is="props.tag" 
    class="text-content"
    :style="{ textAlign: props.align }" 
    >
      <slot>{{ props.text }}</slot>
    </component>
  </template>

  <!-- 用slot的話 預設是 Enter，但可以被其他想要使用的父層覆蓋 -->
  <button v-if="props.showButton" class="survival-button" @click="emit('click')"> 
    <slot name="textButton">{{$t("survivalguide.enterbutton")}}</slot>
  </button>
  <!-- 或是可以用下面的方式引用的來寫 -->
   <!-- <basic-button class='btn-yellow-fill' @click="emit('click')">Enter</basic-button> -->
</div>
</template>

  <!-- 等同於這樣： -->
<!-- <button @click="() => emit('click')">
  <slot name="textButton">Enter</slot>
</button> -->

<!-- 也等同於這樣： -->
<!-- <button @click="handleClick">
  <slot name="textButton">Enter</slot>
</button> -->

<!-- <script setup>
function handleClick() {
  emit('click')
}
</script> -->

<style scoped lang="scss">
.survival-text-frame {
  background-color: $color-fsBlue50 ;
  border-radius: 7px;
  height: auto;
  // min-height: 100px;
  display: flex;           
  flex-direction: column;
  justify-content: center;  
  align-items: center;
  padding: 20px 15px;
  outline: 10px solid $color-fsBlue50;
  border: 1px solid $color-fsTitle;
  // outline-offset: -10px;
}

.survival-text-frame button {
  font-family: 'Roboto', 'Noto Sans TC', sans-serif;
  color: #000;
  padding: 14px 45px;
  background-color: $color-fsGold300;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-bottom: 16px;
  box-shadow: 3px 3px 0 #000;
  text-transform: uppercase;
  letter-spacing: 2px;
  position: relative;
  border-radius: 8px;
  border: 2px solid #1A1A1A;

  &::before {
    top: -3px;
    left: -3px;
  }

  &::after {
    bottom: -3px;
    right: -3px;
  }

  &:hover {
    transform: translate(3px, 3px);
    box-shadow: 0px 0px 0 #000;
  }

  &:active {
    transform: translate(2px, 2px);
    box-shadow: 2px 2px 0 #000;
  }
}

.title-style {
  font-weight: bold;
}

.desc-content {
  margin-bottom: 20px;
  white-space: pre-line;
}

.survival-text-frame .text-content {
  color: #000;
  margin-bottom: 20px;
  width: 100%;
  white-space: pre-line;
}

// .survival-text-frame button {
//   font-size: 16px;
//   font-family: 'Roboto', 'Noto Sans TC', sans-serif;
//   color: $color-fsTitle;
//   padding: 8px 40px;
//   border-radius: 10px;
//   border: 1px solid transparent;
//   background-color: $color-fsGold300;
//   cursor: pointer;
//   transition: transform 0.2s;
//   margin-bottom: 16px;

//   &:hover {
//     transform: scale(1.05); 
//   }
// }

.survival-text-frame .text-content.i18nText {
  margin-bottom: 0;
}

</style>

