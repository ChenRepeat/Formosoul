<template>
  <div class="ledger-container">
    <img src="/member/ledgercard_back.png" class="ledger-bg" alt="Ledger Background">
    
    <div class="stamps-layer">
      <div class="stamp-position shrimp">
        <stampSlot :isUnlocked="passedGames.shrimp" :trigger="activeTriggers.shrimp">
          <IconShrimp class="ink-shrimp" />
        </stampSlot>
      </div>

      <div class="stamp-position dice">
        <stampSlot :isUnlocked="passedGames.dice" :trigger="activeTriggers.dice">
          <IconDice class="ink-dice" />
        </stampSlot>
      </div>

      <div class="stamp-position ringtoss">
        <stampSlot :isUnlocked="passedGames.ringtoss" :trigger="activeTriggers.ringtoss">
          <IconButton class="ink-ringtoss" />
        </stampSlot>
      </div>
            
      <div class="stamp-position helmet"></div>
    </div>
  </div>
</template>

<script setup>
import IconButton from '@/components/icons/SVG/IconButton.vue';
import stampSlot from './stampSlot.vue'
import IconShrimp from '@/components/icons/SVG/IconShrimp.vue';

const props = defineProps({
  passedGames: Object,
  activeTriggers: Object
});
</script>

<style scoped lang="scss">
.ledger-container {
  position: relative;
  width: 480px; 
  height: auto;
  overflow: hidden;
  border-radius: 10px;
}

.ledger-bg {
  width: 100%;
  display: block;
}

.stamps-layer {
  position: absolute;
  top: 0; left: 0;
  width: 100%; height: 100%;
}

.stamp-position {
  position: absolute;
  display: flex;
  justify-content: center;
  align-items: center;

  &.shrimp {
    width: 40%;
    height: 40%;
    bottom: 9%; 
    right: -5%;
  }

  &.dice {
    width: 38%;
    height: 38%;
    top: 8.5%;
    right: -2.9%;
  }

  &.ringtoss {
    width: 39%;
    height: 38%;
    bottom: 13.1%;
    left: 16%;
  }
}

.ink-shrimp,
.ink-dice,
.ink-ringtoss {
  width: 100%;
  height: 100%;
}
</style>