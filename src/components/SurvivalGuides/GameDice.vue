<template>
    <Dice Xdeg="currentX" Ydeg="currentY"></Dice>
    <div class="playerbox">
        <h3>Player</h3>
        <div class="scroebox">
            <h3>Score：</h3>
            <h3>3</h3>
        </div>
    </div>
    <div class="bowlbox">
        <Bowl></Bowl>
    </div>
    <div class="bankerbox">
        <h3>banker</h3>
        <div class="scroebox">
            <h3>Score：</h3>
            <h3>3</h3>
        </div>
    </div>
</template>

<script setup>
import Bowl from './bowl.vue';
import Dice from './Dice.vue';


</script>

<style scoped>
    .bowlbox{
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%); 
    }

    .bankerbox,
    .playerbox{
        width: 200px;
        height: 200px;
    }

    .bankerbox{
        position: absolute;
        margin-left: auto;
        top: 50%;
        right: 0;
        transform: translate(0, -50%); 
    }
    .playerbox{
        position: absolute;
        margin-left: auto;
        top: 50%;
        left: 0;
        transform: translate(0, -50%); 

    }

    .scroebox > h3:last-child{
        text-align: center;
    }
    .scroebox{
        background-color: #EEEEEE;
    }
</style>