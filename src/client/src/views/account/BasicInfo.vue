<script setup>

    /** component **/
    import VNavigationBar from '@/components/VNavigationBar.vue'
    import VButton from '@/components/VButton.vue'
    import VInput from '@/components/VInput.vue'
    import VTextArea from '@/components/VTextArea.vue'


    /** store **/
    import { useAccountStore } from '@/stores/account.js'

    /** propiedades do componente **/
    const accountStore = useAccountStore();

    function updateAccount() {
        accountStore.updateAccount()
    }

</script>

<template>
    <div class="account-basic-info">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <v-navigation-bar :tabs="accountStore.accountNav"/>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3  d-flex flex-column justify-content-center align-items-center">
                    <v-input  v-model.trim="accountStore.accountState.person.name" label="Nome" placeholder="Informe seu email" />
                </div>
                <div class="col-3  d-flex flex-column justify-content-center align-items-center">
                    <v-input  v-model.trim="accountStore.accountState.person.last_name" label="Sobrenome" placeholder="Informe seu email" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-3  d-flex flex-column justify-content-center align-items-center">
                    <v-input v-model.trim="accountStore.accountState.person.birth" label="Data de nascimento" placeholder="Informe seu email" />
                </div>
                <div class="col-4  d-flex flex-column justify-content-center align-items-center">
                    <v-input  v-model.trim="accountStore.accountState.person.profession" label="Profissão" placeholder="Informe seu email" />
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12  d-flex flex-column justify-content-center align-items-center">
                    <v-text-area  v-model.trim="accountStore.accountState.person.biography" :max="200" label="Biografia" placeholder="digite o resumo do post." />
                </div>
            </div>
            <div class="row">
                <div class="col-12 d-flex justify-content-end align-items-center">
                    <v-button btnClass="me-2" @action="updateAccount">Salvar</v-button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .account-basic-info {
        width: 100%;
        height: auto;
        @include v-content-main;
        .content{
            .account-img {
                width: 200px;
                height: 200px;
                background: $grey-primary;
                border-radius: 50%;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center;
                box-shadow: $shadow-primary;
            }
            .name-full{
                font-size: 1.3rem;
                font-weight: 500;
            }
            .email{
                font-size: 1rem;
                font-weight: 400
            }
            .info{
                width: 100%;
                height: auto;
                background: $wth-primary;
                border: $br-primary;
                border-radius: $bdra-primary;
            }
        }
    }
</style>