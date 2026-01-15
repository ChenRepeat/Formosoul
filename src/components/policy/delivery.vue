<template>
    <div class="wrapper dp-flex-col">
        <div class="chinesedelivery">
            <p>{{ $t('policy.deliveryTitle') }}</p>
            <hr>
            <p v-for="(item, index) in deliveryList1" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in deliveryList2" :key="index">{{ item }}</p>
        </div>
    </div>
</template>

<script setup>
    import { computed} from 'vue';
    import { useI18n } from 'vue-i18n'
    const { tm, locale } = useI18n()
    const deliveryList1 = computed(() => {
        return tm('policy.delivery1')
    })
    const deliveryList2 = computed(() => {
        return tm('policy.delivery2')
    })
</script>

<style lang="scss" scoped>
    .wrapper{
        max-width: 1200px;
        margin: 0 auto;
        padding: 60px 40px 100px 40px;
    }

    hr{ border-color: transparent;}

    .big{
        margin: 24px;
    }
    @media screen and (max-width: 1300px) {
        .wrapper{
            max-width: 900px;
        }
    }
</style>