<template>
  <h1>📙 Page Three</h1>
  <p>也可以放動畫、表單、影片。</p>
</template>

<style lang="scss" scoped>
  .motor-right-contain{
    justify-content: space-between;
    gap: 16px;
    height: 100%;
    padding-top: 60px;
    padding-bottom: 12px;
  }
  .motor-right-title{
    gap: 12px;
    width: 406px;
    padding-left: 40px;
  }
  p{
    line-height: 2;
    text-align: left;
    color: $color-fsContent;
  }
  .motor-right-dialog{
    width: 245px;
    padding-top: 24px;
  }
  .motor-right-content{
    align-items: center;
    width: 500px;
    transform: translateX(60px);
    justify-content: space-between;
  }
  .motor-right-iamge-case{
    width: 180px;
    gap: 20px;
    transform: rotate(7deg);
  }
  .motor-right-iamge{
    width: 180px;
    height: 180px;
    transform: scale(0.88);
    position: relative; 
    overflow: hidden;

  }
  img{
    width: 100%;
  }
  .motor-right-iamge.toggle{
    transform: scale(1);
    z-index: 10; 
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
  }
  .over-lay{
    position: absolute; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4); 
    z-index: 9; 
    opacity: 1; 
    pointer-events: none; 
    transition: opacity 0.3s ease;
  }

  .toggle .over-lay {
    opacity: 0; 
  }
  .fade-enter-active,.fade-leave-active {
  transition: opacity 0.2s ease;
  }

  .fade-enter-from,.fade-leave-to {
    opacity: 0;
  }
  .motor-right-icons{
    width: 80%;
    justify-content: space-between;
    margin: 0 auto; 
  }
  .bottom-icons{
    width: 190px;
    position: relative;
  }
  .icon{
    position: absolute;
  }
  .icon-bike{top: 15px;right: 0px;
    animation: 5s drive 0.5s linear infinite;
  }
  .icon-helmet{top: 0px;left: 100px;}
  .icon-stop{top: 15px;left: 180px;}
  @keyframes drive{
    0% {
      right: -169px;
      opacity: 0;
    }
    1%{
      right: -100px;
      opacity: 1;
    }
    100% {
      right: 1100px;
    }
  }
</style>
