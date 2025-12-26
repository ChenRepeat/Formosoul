<script setup>
import { ref, watch, defineProps } from 'vue';
import gsap from 'gsap';

const props = defineProps({
  isUnlocked: Boolean, // 是否已經蓋過章
  trigger: Boolean, // 是否現在播放砰的落下動畫
})

const stampRef = ref(null);

const playStampAnimation = () => {
  if (!stampRef.value) return;

  // GSAP
  gsap.fromTo(stampRef.value,
    { scale: 5, 
      opacity: 0, 
      rotation: 0 
    }, 
        { 
            scale: 1, 
            opacity: 1, 
            rotation: 0, 
            duration: 0.4, 
            ease: "power2.in", 
            onComplete: () => {
                gsap.to(stampRef.value, { x: "+=2", y: "+=2", duration: 0.05, repeat: 3, yoyo: true });
            }
        }
    );
};

// 監聽trigger訊號
watch(()=> props.trigger, (newVal) => {
  if (newVal) playStampAnimation();
});
</script>

<template>
<div class="stamp-slot">
  <div v-if="!isUnlocked && !trigger" class="stamp-ghost">
    <slot></slot>
  </div>

  <div 
    v-show="isUnlocked || trigger" 
    class="stamp-ink" 
    :class="{ 'is-permanent': isUnlocked && !trigger }"
    ref="stampRef"
  >
    <slot></slot>
  </div>
</div>
</template>

<style scoped lang="scss">
.stamp-slot {
  width: 100%; 
  height: 100%;
  display: flex; 
  justify-content: center; 
  align-items: center;
  position: relative;
}

.stamp-ghost { color: $color-fsCaption; opacity: 0.5; }
.stamp-ink { 
  opacity: 0;
  color: $color-fsRed !important;
  &.is-permanent {
    opacity: 1 !important;
  }
}

</style>