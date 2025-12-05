<template>
    <div class="page-line-bar">
        <div
        :style="{
            left: positions
        }"
        class="line-bar">
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    // 在你的頁面引用時 應該會是這樣
    // <Pagelinebar :linebarposition="視當前頁面的標籤" :activeIndex="activeIndex" />
    activeIndex: {
        type: Number,
        default: 0
    },
    linebarposition: {
        type: Number,
        default: 0,
    }
});



const positions = computed(() => {
    return `${props.activeIndex * props.linebarposition}px`
});
</script>

<style scoped>
    .page-line-bar{
        margin: 0 auto;
        width: 1200px;
        height: 8px;
        border: 0;
        border-radius: 999px;
        background-color: #f0f7ff;
        position: relative;
    }
    .line-bar{
        width: 240px;
        height: 8px;
        border: 0;
        border-radius: 999px;
        background-color: #0D3B66;
        position: absolute;
        transition: left 0.3s ease;
    }
</style>