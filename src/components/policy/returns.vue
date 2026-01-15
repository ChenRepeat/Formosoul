<template>
    <div class="wrapper dp-flex-col" :class="{'en': isEnglish}">
        <div class="chinesedelivery">
            <p>{{ $t("policy.returnTitle") }}</p>
            <p>{{ $t("policy.returnSubTitle") }}</p>
            <hr class="big">
            
            <p>{{ $t("policy.return1") }}</p>
            <hr>

            <p>{{ $t("policy.return2") }}</p>
            <p>{{ $t("policy.return3") }}</p>
            <hr>
            
            <p>{{ $t("policy.return4") }}</p>
            <p>{{ $t("policy.return5") }}</p>
            <hr>

            <p>{{ $t("policy.return6") }}</p>
            <p>{{ $t("policy.return7") }}</p>
            <hr class="big">
            <p v-for="(item, index) in return8" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in return9" :key="index">{{ item }}</p>
            <hr class="big">
            <p>{{ $t("policy.return10") }}</p>
            <hr>
            <p v-for="(item, index) in return11" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in return12" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in return13" :key="index">{{ item }}</p>
            <hr class="big">
            <p v-for="(item, index) in return14" :key="index">{{ item }}</p>
            <hr>
            <p v-for="(item, index) in return15" :key="index">{{ item }}</p>
        </div>
    </div>
</template>

<script setup>
    import { computed} from 'vue';
    import { useI18n } from 'vue-i18n'
    const { tm } = useI18n()
    const return8 = computed(() => {
        return tm('policy.return8')
    })
    const return9 = computed(() => {
        return tm('policy.return9')
    })
    const return11 = computed(() => {
        return tm('policy.return11')
    })
    const return12 = computed(() => {
        return tm('policy.return12')
    })
    const return13 = computed(() => {
        return tm('policy.return13')
    })
        const return14 = computed(() => {
        return tm('policy.return14')
    })
    const return15 = computed(() => {
        return tm('policy.return15')
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

    }
    .big{
        margin: 24px;
    }
    p{
        white-space: pre-wrap;
    }
    @media screen and (max-width: 1300px) {
        .wrapper{
            max-width: 900px;
        }
    }
</style>