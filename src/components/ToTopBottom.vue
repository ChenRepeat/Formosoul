<script setup>
  const scrollToTop=()=>{
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>

<template>
  <div class="toTop" @click="scrollToTop">
    <div v-for=" index in 4"
    :key="index"
    class="bar"
    :class="`bar${index}`"></div>
  </div>
</template>

<style lang="scss" scoped>
.toTop{
  position: fixed;
  z-index: 9999;
  bottom: 1%;
  right: 1%;
  cursor: pointer;
  width: 50px;
  height: 50px;
  filter: 
    // drop-shadow(2px 0 1px $color-fsWhite)
    // drop-shadow(-2px 0 1px $color-fsWhite)
    // drop-shadow(0 2px 1px $color-fsWhite) 
    drop-shadow(0 0 3px $color-fsWhite);
  transition: all 0.3s;
  .bar{
    width: 50%;
    height: 5px;
    background-color: $color-fsTitle;
    position: absolute;
    transform-origin: 50% 100%;
    &.bar1,&.bar2{
      transform: rotate(45deg);
    }
    &.bar3,&.bar4{
      transform: rotate(-45deg);
    }
    &.bar1{top: 30%;right: 14%;}
    &.bar2{top: 60%;right: 14%;}
    &.bar3{top: 30%;left: 14%;}
    &.bar4{top: 60%;left: 14%;}
  }
}
</style>