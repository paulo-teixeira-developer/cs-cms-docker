<script setup>
    import { ref } from 'vue'

    /** component **/
    import VNavigationBar from '@/components/VNavigationBar.vue'
    import VInputFile from '@/components/VInputFile.vue'
    import VButton from '@/components/VButton.vue'

    /** store **/
    import { useAccountStore } from '@/stores/account.js'

    /** propiedades do componente **/
    const accountStore = useAccountStore();
    const formUpload = ref(null)

    function uploadFile(formData) {
        formUpload.value = formData
    }

    function submitForm() {
        if (formUpload.value)
            accountStore.uploadFile(formUpload.value)
    }

</script>


<template>
    <div class="account-profile-picture">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-12">
                    <v-navigation-bar :tabs="accountStore.accountNav"/>
                </div>
            </div>
            <div class="row">
                <div class="col-6 offset-3 mb-3">
                    <v-input-file @form-data="uploadFile" label="Click para fazer upload da foto" fileRule="jpg, jpeg, png." />
                </div>
                <div class="col-12 d-flex align-items-center justify-content-end">
                    <v-button @action="submitForm">Enviar</v-button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .account-profile-picture {
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
        }
    }
</style>