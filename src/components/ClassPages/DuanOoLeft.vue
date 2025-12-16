<script setup>


</script>

<template>
  <section class="duan-left dp-flex-col">
    <div class="duan-title dp-flex-col">
      <h3>Dragon-boat  Festival</h3>
      <p>The Legend: Protecting the Dragon Soul When the magical scholar Qu Yuan cast himself into the river, his spirit transformed into a powerful Dragon Soul.</p>
    </div>
    <div class="duan-mid-text">
      <p>To safeguard him, villagers established three ancient rites: <br>

Dragon Boat Racing: Mystical battles on water to rescue his soul from malevolent spirits.<br>

Enchanted Dumplings: Ritual offerings infused with fortune spells to nourish his essence.<br>

Magical Defense: Hanging calamus and mugwort as talismans to forge a protective barrier.</p>
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