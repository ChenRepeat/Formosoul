<template>
  <div 
    class="checkmark-container" 
    :style="{ width: size + 'px', height: size + 'px' }"
  >
    <svg 
      xmlns="http://www.w3.org/2000/svg" 
      viewBox="0 0 100 100" 
      class="checkmark-svg"
    >
      <circle 
        ref="circleRef"
        class="checkmark-circle" 
        cx="50" cy="50" r="40" 
        fill="none" 
        :stroke="color"
        :stroke-width="roundWidth"
        stroke-linecap="round"
      />
      
      <path 
        ref="checkRef"
        class="checkmark-check" 
        fill="none" 
        d="M30 52 L45 67 L70 35" 
        :stroke="color"
        :stroke-width="strokeWidth"
        stroke-linecap="round"
        stroke-linejoin="round"
      />
    </svg>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import gsap from 'gsap';

const props = defineProps({
  size: {
    type: [Number, String],
    default: 100
  },
  color: {
    type: String,
    default: '#4caf50'
  },
  strokeWidth: {
    type: Number,
    default: 6
  },
  roundWidth: {
    type: Number,
    default: 6
  },
  duration: {
    type: Number,
    default: 0.8
  },
  delay: {
    type: Number,
    default: 0.5
  },
});

const circleRef = ref(null);
const checkRef = ref(null);

onMounted(() => {
  const circle = circleRef.value;
  const check = checkRef.value;
  const circleLength = circle.getTotalLength();
  const checkLength = check.getTotalLength();

  gsap.set([circle, check], { 
    strokeDasharray: circleLength, 
    strokeDashoffset: circleLength,
    autoAlpha: 0 
  });

  const tl = gsap.timeline({ defaults: { ease: 'power2.out' } , delay : props.delay});

  tl
    .to(circle, { 
      strokeDashoffset: 0, 
      autoAlpha: 1, 
      duration: props.duration * 0.6 
    })
    .to(check, {  
      autoAlpha: 1, 
      duration: props.duration * 0.001 
    },)
    .to(check, { 
      strokeDashoffset: 0, 
      duration: props.duration * 3
    },);
});
</script>

<style scoped>
.checkmark-container {
  display: inline-block;
  line-height: 0; 
}

.checkmark-svg {
  width: 100%;
  height: 100%;
  display: block;
  overflow: visible;
}

.checkmark-circle {
  transform-origin: center;
  transform: rotate(-90deg);
}

/* 非必要，但可以防止在 JS 載入前閃爍：
   預設讓 path 和 circle 先透明，
   JS 的 gsap.set 會接手控制它們。
*/
.checkmark-circle, .checkmark-check {
  opacity: 0;
}
</style>