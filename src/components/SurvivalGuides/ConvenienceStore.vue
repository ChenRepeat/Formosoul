<script setup>

</script>

<template>
        <main class="survival-convenience-store-case">
            <div class="survival-convenience-store-case-wrapper">
                <img class='survival-convenience-store-case-bg' src="../../../public/SurvivalGuide/convenience_Store-min.png" alt="">
            </div>
        </main>

</template>


<style scoped lang="scss">


.survival-convenience-store-case {
    background-color: #000;
    width: 100%; 
    height: 100vh;
    position: relative;
    overflow: hidden;
}

.survival-convenience-store-case-wrapper {
    width: 100%;
    height: 100%;
    margin: 0;    
    padding: 0;
}

.survival-convenience-store-case-bg {
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