<script setup>
    import {onMounted} from 'vue'


    /** component **/
    import VLayout from '@/components/layout/MainLayout.vue'
    import AccountGeneral from '@/views/account/General.vue'

    /** store **/
    import { useAccountStore } from '@/stores/account.js'

    /** propiedades do componente **/
    const accountStore = useAccountStore();

    onMounted(() => {
        accountStore.getAccount()
    })

</script>
<template>
    <v-layout>
        <template #content>
            <div class="account-container">
                <account-general />
                <div class="">
                    <RouterView/>
                </div>
            </div>
        </template>
    </v-layout>
</template>

<style scoped lang="scss">
    .account-container{
        display: grid;
        grid-template-columns: $column-20 $column-80;
        column-gap: $gap;
    }
</style>