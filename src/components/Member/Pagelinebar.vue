<template>
    <div class="page-line-bar">
        <div
        :style="{
            left: positions,
            width: linerbarlength,
        }"
        class="line-bar">
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    // 在你的頁面引用時 應該會是這樣
    // <Pagelinebar :linebarposition="視當前頁面的標籤數量去決定每次需要移動多少 px 以及 linebar 的長度" :activeIndex="activeIndex" />
    activeIndex: {
        type: Number,
        default: 0
    },
    linebarposition: {
        type: Number,
        default: 0,
    }
});


// 控制每次 linebar 要移動幾個位置
const positions = computed(() => {
    return `${props.activeIndex * props.linebarposition}px`;
});
// 控制 linebar 長度
const linerbarlength = computed(() => {
    return `${props.linebarposition}px`;
});
</script>

<style scoped>
    .page-line-bar{
        margin: 0 auto;
        width: 1200px;
        height: 8px;
        border: 0;
        /* border-radius: 999px; */
        background-color: #f0f7ff;
        position: relative;
    }
    .line-bar{

        height: 4px;
        border: 0;
        /* border-radius: 999px; */
        background-color: #0D3B66;
        position: absolute;
        transition: left 0.3s ease;
    }
</style>