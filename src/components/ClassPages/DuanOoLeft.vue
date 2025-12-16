<script setup>


</script>

<template>
  <section class="duan-left dp-flex-col">
    <div class="duan-title dp-flex-col">
      <h3>Dragon-boat  Festival</h3>
      <p>A Tale of Magic, Souls, and Alignment,The Origin of the Legend: A Hero's Sacrifice and the Dragon Soul's Protection</p>
    </div>
    <div class="duan-mid-text">
      <p>Villagers believed that Qu Yuan’s integrity and powerful magic, upon his descent, transformed into a Dragon Soul that still needed to be protected and honored. Therefore, they held ancient rites to rescue and safeguard his spirit:

They raced Dragon Boats across the waters, simulating battles against malevolent aquatic spirits, hoping to retrieve Qu Yuan's Dragon Soul.

They tossed specially-prepared 'Enchanted Rice Dumplings' into the river—not as sustenance, but as a ritualistic offering of blessings and fortune spells for the Dragon Soul.

They hung calamus and mugwort within their homes, combining them with various talismans and protective charms, hoping to forge a magical defense for Qu Yuan's spirit.</p>
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