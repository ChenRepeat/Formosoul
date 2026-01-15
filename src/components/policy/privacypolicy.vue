<template>
    <div class="wrapper dp-flex-col">
        <div class="chinesedelivery">
            <p>{{ $t("policy.privacyTitle") }}</p>
            <p>{{ $t("policy.privacySubTitle") }}</p>
            <hr>
            <p v-for="(item, index) in privacy1" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy2" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy3" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy4" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy5" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy6" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy7" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy8" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in privacy9" :key="index">{{ item }}</p>
        </div>
    </div>
</template>

<script setup>
    import { computed} from 'vue';
    import { useI18n } from 'vue-i18n'
    const { tm } = useI18n()
    const privacy1 = computed(() => {
        return tm('policy.privacy1')
    })
    const privacy2 = computed(() => {
        return tm('policy.privacy2')
    })
    const privacy3 = computed(() => {
        return tm('policy.privacy3')
    })
    const privacy4 = computed(() => {
        return tm('policy.privacy4')
    })
    const privacy5 = computed(() => {
        return tm('policy.privacy5')
    })
    const privacy6 = computed(() => {
        return tm('policy.privacy6')
    })
    const privacy7 = computed(() => {
        return tm('policy.privacy7')
    })
    const privacy8 = computed(() => {
        return tm('policy.privacy8')
    })
    const privacy9 = computed(() => {
        return tm('policy.privacy9')
    })
</script>

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
        display: flex;
        margin: 24px;
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