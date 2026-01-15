<template>
    <div class="wrapper dp-flex-col">
        <div class="chinesedelivery">
            <p>{{ $t("policy.paymentTitle") }}</p>
            <hr>
            
            <p>{{ $t("policy.paymentText1") }}</p>
            <p>{{ $t("policy.paymentText2") }}</p>
            <hr>

            <p>{{ $t("policy.paymentText3") }}</p>
            <p>{{ $t("policy.paymentText4") }}</p>
            <hr class="big">
            
            <p>{{ $t("policy.paymentText5") }}</p>
            <hr>

            <p>{{ $t("policy.paymentText6") }}</p>
            <p>{{ $t("policy.paymentText7") }}</p>
        </div>
    </div>
</template>

<style lang="scss" scoped>
    .wrapper{
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 40px 100px 40px;
        gap: 60px;
        &.en{
            flex-direction: column-reverse;
        }
    }
    .chinesedelivery > hr,
    .engdelivery > hr{
        border-color: transparent;

    }

    .big{
        margin: 24px;
    }
    @media screen and (max-width: 1300px) {
        .wrapper{
            max-width: 900px;
        }
    }
</style>