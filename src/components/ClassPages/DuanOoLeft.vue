<script setup>


</script>

<template>
  <section class="duan-left dp-flex-col">
    <div class="duan-title dp-flex-col">
      <h3>Dragon-boat  Festival</h3>
      <p>A Tale of Magic, Souls, and Alignment,📜 The Origin of the Legend: A Hero's Sacrifice and the Dragon Soul's Protection</p>
    </div>
    <div class="duan-mid-text">
      <p>Villagers used magical dragon boats, colorful charms, and protective spells to save Qu Yuan’s spirit. They tossed enchanted rice dumplings into the river for blessings and luck.</p>
    </div>
    <div class="duan-qu-yuan dp-flex">
      <div class="intro dp-flex-col">
        <h5>Qu Yuan</h5>
        <p>A heroic scholar with magical powers, who jumped into the river. Villagers cast spells and charms to honor and protect his spirit.</p>
      </div>
      <img src="/Classes/QiYuan.png" alt="">
    </div>
  </section>
</template>

<style lang="scss" scoped>
  .duan-left{
    width: 100%;
    height: 90%;
    justify-content: space-between;
  }
  .duan-title{
    gap: 12px;
  }
  p{
    color: $color-fsContent;
  }
  .intro{
    h5{
      text-align-last: left;
    }
  }
  .duan-qu-yuan{
    gap: 30px;
    align-items: center;
    img{
      width: 180px;
      height: 180px;
      border-radius: 50%;
    }
  }
</style>