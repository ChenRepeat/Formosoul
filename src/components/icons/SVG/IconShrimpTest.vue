<template>
  <div class="stamp-wrapper">
    <svg width="129" height="129" viewBox="0 0 129 129" fill="none" xmlns="http://www.w3.org/2000/svg" :style="{ width: size + 'px', height: size + 'px' }">
      <g clip-path="url(#clip0_2703_6304)">
        <path d="M13.1413 46.3117C..." fill="currentColor"/>
      </g>
      <defs>
        <clipPath id="clip0_2703_6304">
          <rect width="94.1997" height="94.1997" fill="white" transform="translate(0 47.1016) rotate(-30)"/>
        </clipPath>
      </defs>
    </svg>
  </div>
</template>

<script setup>
defineProps({
  size: { type: [String, Number], default: 100 }
});
</script>

<style scoped>
.stamp-wrapper {
  display: inline-block;
  color: #e63946; /* 設定成印泥的紅色 */
  filter: drop-shadow(0 0 1px rgba(0,0,0,0.2)); /* 增加一點點立體感 */
}
</style>