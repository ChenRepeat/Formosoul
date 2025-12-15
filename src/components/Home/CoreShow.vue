<script setup>
import BasicButton from '../BasicButton.vue';

</script>
<template>

    <main class="coregame-dock dp-flex-col">
        <h5>Power Forged!</h5>
        <div class="coregame-top dp-flex">
            <div>
                <div class="core-pic dp-flex">
                    <img src="../../../public/Home/game/Cornu Cervi Pantotrichum.png" alt="">
                </div>
            </div>
            <div class="core-text">
                
                <h6>Cornu Cervi Pantotrichum</h6>
                <p>Source ：Young antler of Sika or Red Deer.</p>
                <p>Property ：Extreme Vitality, Rapid Regeneration.</p>
                <p>Effect：Anchors the Mind, Resists Mental Attack, Suited for Deep Concentration.</p>
                
            </div>
        </div>

        <div class="coregame-down">
            <div class="coregame-record">

                <p>Your Formosoul bond is sealed. <br>
                    The core is already etched onto your Student Card.
                    Finalize enrollment now, before the energy dissipates.
                </p>
                <BasicButton class="btn-yellow-fill">Enroll ＆ Claim Student Card!</BasicButton>
            </div>
        </div>
    </main>
    

</template>

<style scoped lang="scss">

    .coregame-dock{  
        gap: 40px;
        & h5{
            text-align: center;
        }
    }



//上方區塊------------------------------------
    .coregame-top{
        justify-content: center;
        align-items: center;
        gap: 60px;
        padding: 50px 60px;
        box-shadow: inset 0 0 10px rgba(0,0,0, 0.3);
        border-radius: 10px;
    }

    .coregame-top img{
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center left;
        filter: drop-shadow(0 0 15px $color-fsWhite);   //因為要沿著圖加陰影，不能用box-shadow
    }

    .core-pic{
        height: 150px;
        width: 150px;
        & img{
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
    }
    
    .core-text{
        & h6{
            margin: 0 auto 20px;
        }
        & p{
            font-weight: 200;
        }
    }


    // 下方區塊------------------------------------

    .coregame-record{
        & P{
            font-size: 1.2rem;
            animation: 
            float-swing 10s linear infinite;
        }
    }

    @keyframes float-swing {
        0%, 100% {
            transform: translateY(0) rotate(0deg);
        }
        20%, 60% {
            transform: translateY(-3px) rotate(-0.5deg);
        }
        40%, 80% {
            transform: translateY(-3px) rotate(0.5deg);
        }
    }



    .btn-yellow-fill{
        display: block;
        margin: 20px auto 0;
    }



</style>