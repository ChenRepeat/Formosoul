<script setup>
import { defineEmits, defineProps } from 'vue';

const props = defineProps({
    text: {type: String, requird: true},
    width: {type: String, default: '20%vw'},
    height: {type: String, default: '20%vh'},
    align: {type: String, default: 'center'},
    tag: {type: String, default: 'p'},
})

// const emit = defineEmits(['click']);
</script>


<template>
<div class="survival-cs-text-frame" :style="{ width: props.width, height: props.height }">
    <component :is="props.tag"
    class="cs-text-content"
    :style="{ textAlign:props.align}"
    >

    {{ props.text }}
    </component>
</div>
</template>



<style scoped lang="scss">


</style>