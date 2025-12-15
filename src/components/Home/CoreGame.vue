<script setup>

</script>

<template>

    <main class="coregame-dock dp-flex">
        <div class="coregame-left dp-flex">
            <img src="../../../public/Home/game/poking lottery.png" alt="">
        </div>

        <div class="coregame-right">
            <div class="coregame-guide">
                <h5>Ritual Guidance</h5>
                <p>To discover your destined core, you must sense this 'Herbal Core Cabinet.' <br> 
                    Each drawer seals a unique core ingredient.  
                    Simply trust your intuition and click a single drawer—your soul will make the choice.
                    Carefully explore the drawer that resonates with you.<br> <br>
                    Remember, you have only one chance to activate the sensing.
                </p>
            </div>

            
        </div>
    </main>
    

</template>

<style scoped lang="scss">

    .coregame-dock{  
        gap: 40px;
    }
    .coregame-left, .coregame-right{
        width: 50%;
    }

//左邊圖------------------------------------
    .coregame-left{
        justify-content: center;
        align-items: center;
    }

    .coregame-left img{
        width: 90%;
        height: 90%;
        object-fit: contain;
        object-position: center left;
        cursor: pointer;
        filter: drop-shadow(0 0 20px $color-fsWhite);   //因為要沿著櫃子加陰影，不能用box-shadow
        animation: blink 1.3s ease-in-out infinite;


        &:hover{                   //hover時取消閃爍
            animation: none;
            filter: none;
        }
    }

    @keyframes blink{
        0%, 100%{
            filter: drop-shadow(0 0 20px $color-fsWhite); 
        }
        50%{
            filter: drop-shadow(0 0 0px transparent);
        }
    }

//右邊文字 guide------------------------------------

    .coregame-guide h5{
        margin-bottom: 40px;
    }




</style>