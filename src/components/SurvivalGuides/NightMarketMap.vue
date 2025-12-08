<script setup>

</script>

<template>
        <main class="survival-night-market-case">
            <div class="survival-night-market-case-wrapper">
                <img class='survival-night-market-case-map' src="/SurvivalGuide/night_market_map_bg-min.png" alt="">
            </div>
        </main>

</template>


<style scoped lang="scss">


.survival-night-market-case {
    background-color: #000;
    width: 100%; 
    height: 100vh;
    position: relative;
    overflow: hidden;
}

.survival-night-market-case-wrapper {
    width: 100%;
    height: 100%;
    margin: 0;    
    padding: 0;
}

.survival-night-market-case-map {
    // 用絕對定位脫離header的fixed排版
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
    z-index: 0;
}


</style>