<script setup>
  const scrollToTop=()=>{
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
</script>

<template>
  <div class="toTop" @click="scrollToTop">
    <div class="toTopBar1"></div>
    <div class="toTopBar2"></div>
    <div class="toTopBar3"></div>
    <div class="toTopBar4"></div>
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
    drop-shadow(2px 0 1px $color-fsWhite)
    drop-shadow(-2px 0 1px $color-fsWhite)
    drop-shadow(0 2px 1px $color-fsWhite) 
    drop-shadow(0 -2px 1px $color-fsWhite);
  transition: all 0.3s;
}
.toTop:hover{
  opacity: 0.8;
}
.toTopBar1,.toTopBar2,.toTopBar3,.toTopBar4{
  width: 20%;
  border: 1px solid $color-fsTitle;
  position: absolute;
}
.toTopBar1{
  transform: rotate(-45deg) translate(-8px,16px);
}
.toTopBar2{
  transform: rotate(-45deg) translate(-16px,24px);
}
.toTopBar3{
  transform: rotate(45deg) translate(32px,8px);
}
.toTopBar4{
  transform: rotate(45deg) translate(24px,0px);
}
</style>