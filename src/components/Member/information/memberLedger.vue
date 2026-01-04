<script setup>
import IconButton from '@/components/icons/SVG/IconButton.vue';
import stampSlot from './stampSlot.vue'
import IconShrimp from '@/components/icons/SVG/IconShrimp.vue';
import IconBuecard from '@/components/icons/SVG/IconBuecard.vue';
import IconHelmet from '@/components/icons/SVG/IconHelmet.vue';
import IconWandCore from '@/components/icons/SVG/IconWandCore.vue';
import { ref, onMounted, computed } from 'vue';
import { useMemberStore } from '@/stores/member';
const memberStore = useMemberStore();

const props = defineProps({
  passedGames: Object,
  activeTriggers: Object
});

// 內部狀態（會員中心使用）
const internalPointsStatus = ref({
    dice: 0, 
    shrimp: 0, 
    ring: 0, 
    bue: 0, 
    mot: 0, 
    member_wandcore: 0
});

// 判斷是否從 props 傳入（遊戲內）還是自己載入（會員中心）
const isFromGame = computed(() => !!props.passedGames);

// 最終顯示的狀態
const displayStatus = computed(() => {
    if (isFromGame.value) {
        // 遊戲內：使用傳入的 props
        return {
            shrimp: props.passedGames.shrimp,
            dice: props.passedGames.dice,
            ringtoss: props.passedGames.ringtoss,
            bue: props.passedGames.bue,
            bike: props.passedGames.bike,
            wand: props.passedGames.wand
        };
    } else {
        // 會員中心：使用內部狀態
        return {
            shrimp: internalPointsStatus.value.shrimp >= 1,
            dice: internalPointsStatus.value.dice >= 1,
            ringtoss: internalPointsStatus.value.ring >= 1,
            bue: internalPointsStatus.value.bue >= 1,
            bike: internalPointsStatus.value.mot >= 1,
            wand: internalPointsStatus.value.member_wandcore >= 1
        };
    }
});

onMounted(async () => {
    // 如果不是從遊戲傳入，才需要自己載入
    if (!isFromGame.value) {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            await memberStore.fetchPointsStatus();
            internalPointsStatus.value = memberStore.pointsStatus;
            console.log('[MemberLedger] 從 store 載入:', internalPointsStatus.value);
        } else {
            // 訪客用原本的 fetch
            get_pointscard();
        }
    }
});
</script>

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

      <div class="stamp-position bue">
        <stampSlot :isUnlocked="passedGames.bue" :trigger="activeTriggers.bue">
          <IconBuecard class="ink-bue" size='140'/>
        </stampSlot>
      </div>

      <div class="stamp-position bike">
        <stampSlot :isUnlocked="passedGames.bike" :trigger="activeTriggers.bike">
          <IconHelmet class="ink-bike" size='140'/>
        </stampSlot>
      </div>

      <div class="stamp-position wand">
        <stampSlot :isUnlocked="passedGames.wand" :trigger="activeTriggers.wand">
          <IconWandCore class="ink-wand" />
        </stampSlot>
      </div>

    </div>
  </div>
</template>

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

  &.bue{
    width: 25%;
    height: 38%;
    bottom: 4.1%;
    left: 1.2%;
  }

  &.bike{
    width: 29%;
    height: 38%;
    top: 9.1%;
    left: 2.1%;
    transform: scaleX(-1) rotate(-10deg);
  }

  &.wand{
    width: 40%;
    height: 38%;
    top: 14.9%;
    left: 34.1%;
  }

}

.ink-shrimp,
.ink-dice,
.ink-ringtoss,
.ink-bue,
.ink-bike,
.ink-wand {
  width: 100%;
  height: 100%;
}
</style>