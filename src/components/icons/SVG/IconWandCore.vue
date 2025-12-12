<template>
  <svg width="180" height="180" viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
  <g clip-path="url(#clip0_2703_6310)">
  <path d="M46.6728 77.1624L62.5251 72.9147M50.4999 48.1063L64.7115 56.3114M73.7496 30.2639L77.9972 46.1163M102.806 34.0911L94.6005 48.3027M120.648 57.3407L104.796 61.5884M116.821 86.3968L102.609 78.1917M93.5712 104.239L89.3236 88.3868M64.5152 100.412L72.7203 86.2004M84.0748 68.7983L108.143 158.622" 
  stroke="currentColor" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
  </g>
  <defs>
  <clipPath id="clip0_2703_6310">
  <rect width="131.282" height="131.282" fill="white" transform="translate(0 113.693) rotate(-60)"/>
  </clipPath>
  </defs>
  </svg>
</template>

<script>
  export default {
    props: {
      size: {
        type: [String, Number],
        default: 48,
      },
    },
  };
</script>

<style scoped>
  svg {
    transition: color 0.2s ease-in-out; 
  }
</style>