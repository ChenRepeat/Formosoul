<script setup>
  import { computed, ref} from 'vue';
  import { usePageNavigation } from './goToPage';
  


  const emit = defineEmits(['flip'])
  const { goToPage } = usePageNavigation(emit)
</script>

<template>
    <div class="animal-right-contain dp-flex-col">
      <div class="animal-top-topic dp-flex">
        <div class="animal-photo-case">
          <img src="/Classes/Salamander.png" alt="" class="salamander">
        </div>
        <div class="animal-topic-text dp-flex-col top">
          <h5 class="animal-topic-title">Formosa Salamander</h5>
          <p>A rare high-altitude amphibian found in cold mountain streams, sensitive to habitat changes.</p>
        </div>
      </div>
      <div class="animal-bottom-topic dp-flex">
        <div class="animal-topic-text dp-flex-col buttom">
          <h5 class="animal-topic-title">Taiwan <br>Blue Magpie</h5>
          <p>A striking blue bird with a long tail and cooperative family groups, often called “long-tailed mountain lady.”</p>
        </div>
        <div class="animal-photo-case">
          <img src="/Classes/BlueBird.png" alt="" class="blue-bird">
        </div>
      </div>
    </div>

</template>

<style lang="scss" scoped>

  .animal-photo-case{
    width: 320px;
    height: 320px;
    position: relative;
    img{
      width: 100%;
      height: 100%;
      border-radius: 8px;
      position: absolute;
      box-shadow: 6px 6px 12px $color-fsTitle;
    }
    .salamander{
      top: -25%;
      left: -10%;
      transform: rotate(10deg);
    }
    .blue-bird{
      top: 15%;
      left: 15%;
      transform: rotate(-15deg);
    }
  }
  .animal-topic-text{
    text-align: left;
    width: 38%;
    justify-content: end;
    &.buttom{
      width: 35%;
    }
    &animal-topic-text.top{
      padding-right: 6%;
    }
  }
  p{
    line-height: 2;
    color: $color-fsContent;
    hyphens:auto;
  }
  .animal-topic-title{
    color: $color-fsTitle;
  }
  
 
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
      right: 497px;
    }
  }
</style>
