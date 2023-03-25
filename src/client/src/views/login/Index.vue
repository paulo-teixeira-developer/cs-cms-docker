<script setup>

import { reactive, watch, computed } from 'vue'
import { useRouter } from 'vue-router'

/** component **/
import VInput from '@/components/VInput.vue'
import VButton from '@/components/VButton.vue'

/** composable **/
import { validate } from '@/composables/validator.js'

/** store **/
import { useAlertStore } from '@/stores/alert.js'
import { useAuthStore } from '@/stores/auth.js'

/** propiedades do componente **/
const router = useRouter();
const alertStore = useAlertStore();
const authStore = useAuthStore();
const dataExport = reactive({
    email: 'pauloteixeira@gmail.com',
    password: "123456789"
});
const rulesDataExport = {
    email: { rules: { required: true, email: true, min: 5 } },
    password: { rules: { required: true, min: 5 }, label: 'senha' }
};

/** validações **/
function submitForm() {
    const validation = validate(dataExport, rulesDataExport);
    if (validation.fails.length) {
        alertStore.setAlert({ status: 'error', data: validation.fails });
    } else {
        authStore.setLogin(dataExport);
    }
}

watch(authStore.auth, (newAuth) => {
    if (newAuth.isLogged)
        router.push({ name: 'DashboardIndex', replace: true })
})

const logo = computed(() => {
    return `${import.meta.env.VITE_API_FILE}reserved/img/logo-codigo-storm-full-pb.png`
})

</script>

<template>
    <div class="login">
        <div class="container-main">
            <div class="container inherit-fill">
                <div class="row inherit-fill">
                    <div class="col-6 dv-1">
                        <img :src="logo">
                    </div>
                    <div class="col-6 dv-2">
                        <div class="form-login">
                            <v-input btn-class="mb-3" v-model.trim="dataExport.email" label="Email" placeholder="Informe seu email" @enter="submitForm" />
                            <v-input btn-class="mb-3" v-model.trim="dataExport.password" label="Senha" placeholder="Informe sua senha" type="password" @enter="submitForm" />
                            <v-button class="mt-3" @action="submitForm">Entrar</v-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .login {
        width: 100vw;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        user-select: none;

        .container-main {
            width: 800px;
            height: 500px;
            box-shadow: $shadow-primary;
            outline: $br-primary;
            border-radius: $bdra-primary;
            background: $blue-primary;

            .dv-1 {
                display: flex;
                justify-content: center;
                align-items: center;

                img {
                    width: 60%;
                    height: auto;
                }
            }

            .dv-2 {
                display: flex;
                justify-content: center;
                align-items: center;
                background: $wth-primary;

                .form-login {
                    width: 70%;
                    display: flex;
                    flex-direction: column;
                }
            }
        }
    }
</style>