<script setup>
    import BasicButton from '../BasicButton.vue';

</script>

<template>

    <main class="coregame-dock">
        <div class="coregame-top dp-flex">
            <img src="../../../public/Home/game/poking_real.png" alt="">
        </div>
        <div class="coregame-down">

            <h5>Poking Lottery</h5>
            <p>This is a classic Taiwanese childhood game where players pay a small fee to punch a hole through one of many covered squares on a board, winning the surprise prize hidden inside. It's all about luck and a little fun poke!</p>

        </div>
        <BasicButton class="btn-yellow-fill">Begin the Core Sensing</BasicButton>
    </main>

</template>

<style scoped lang="scss">

    .coregame-dock{
        width: 100%;
    }

    .coregame-top{
        height: 40%;
        justify-content: center;
        align-items: center;
        margin-bottom: 5%;
    }

    .coregame-top img{
        width: 30%;
        height: 30%;
        object-fit: contain;
    }

    .coregame-down{
        margin: 0 auto 40px;
        width: 80%;
    }

    .coregame-down h5{
        text-align: center;
        margin-bottom: 20px;
    }

    .btn-yellow-fill{
        display: block;
        margin: 0 auto;
    }

</style>