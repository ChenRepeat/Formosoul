<script setup>
  import { computed, ref} from 'vue';
  import { usePageNavigation } from './goToPage';
  


  const emit = defineEmits(['flip'])
  const { goToPage } = usePageNavigation(emit)
</script>

<template>
  <section class="dp-flex-col newyear-left">
    <div class="newyear-top-topic dp-flex-col">
      <h3>New Year</h3>
      <div class="newyear-nian-intro dp-flex">
        <div class="nian-case">
          <img src="/Classes/Nian.png" alt="">
        </div>
        <div class="nian-intro-text dp-flex-col">        
          <h5>Nian</h5>
          <p>A magical beast attacking villages each New Year, scared away by glowing red spells, booming thunder charms, and enchanted fire to protect everyone.</p>
        </div>
      </div>
    </div>
    <div class="newyear-intro-pic dp-flex">
      <img src="/Classes/NewYear2.png" alt="" class="left">
      <img src="/Classes/NewYear1.png" alt="" class="right">
    </div>
    <div class="newyear-left-text">
      <p>Villagers used magical firecrackers, red charms, and lanterns to scare Nian. They sprinkled fairy dust and prepared enchanted foods to bring safety, health, and luck.</p>
    </div>
  </section>

</template>

<style lang="scss" scoped>
  .newyear-left{
    width: 100%;
    height: 100%;
    justify-content: space-between;
    gap: 24px;
  }
  .newyear-top-topic{
    h3{
      width: 100%;
      text-align: right;
    }
  }
  .newyear-nian-intro{
    gap: 24px;
    justify-content: space-between;
    .nian-case{
      width: 150px;
      height: 150px;
      border-radius: 50%;
      overflow: hidden;
      position: relative;
      img{
        position: absolute;
        top: 0;left: -50%;
        width: 200%;
      }
    }
    .nian-intro-text{
      width: 65%;
      h5{
        text-align: left;
      }
    }
  }
  .newyear-intro-pic{
    position: relative;
    width: auto;
    height: 100px;
    img{
      position: absolute;
    }
    .right{
      top: -100%;left: 0;
      width: 320px;
      height: 320px;
      transform: rotate(15deg);

    }
    .left{
      top: -150%;right: -20%;
      width: 360px;
      height: 360px;
      transform: rotate(-15deg);
      z-index: 2;
    }
  }
  .newyear-left-text{
    
      width: 150%;
      margin-left: 50%;
      padding: 20px;
      background-color: rgba(246, 246, 246,0.7);
      color: $color-fsContent;
      line-height: 2;
      border-radius: 8px;
    
  }
</style>
