<script setup>
import { reactive, ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'

/**component**/
import VButton from '@/components/VButton.vue'

/** store **/
import { useAuthStore } from '@/stores/auth.js'

/** propiedades do componente **/
const authStore = useAuthStore();
const nameSys = import.meta.env.VITE_APP_TITLE;
const activePopUp = ref(false)
const router = useRouter();

const notification = reactive({
    alert: false,
})

function toggleAccountPopUp() {
    activePopUp.value = !activePopUp.value;
}

async function logout() {
    await authStore.setLogout();
    await router.push({ name: 'LoginIndex', replace: true })
}

function redirectAccount() {
    router.push({ name: 'accountBasicInfo' })
    activePopUp.value = false
}

</script>

<template>
    <div class="the-header">
        <div class="container inherit-fill">
            <div class="row inherit-fill">
                <div class="v-name-sys col-10 d-flex align-items-center">
                    <span class="name-primary">{{ nameSys }}</span>
                </div>
                <div class="col-2 d-flex justify-content-end align-items-center">
                    <div class="v-notification me-3">
                        <div class="alert-notify" :class="{ 'active-notify': notification.alert }"></div>
                    </div>
                    <div class="account d-flex justify-content-center align-items-center" @click="toggleAccountPopUp" :style="{backgroundImage: `url(${authStore.accountImg}`}">
                        <span v-if="!authStore.auth.user.imgAccount" class="account-abbr">{{ authStore.auth.user.abbrName }}</span>
                    </div>
                    <div class="pop-up-account" v-if="activePopUp">
                        <div class="header d-flex flex-column align-items-center">
                            <div class="account-img d-flex justify-content-center align-items-center" :style="{backgroundImage: `url(${authStore.accountImg}`}">
                                <span v-if="!authStore.auth.user.imgAccount" class="abbr-name">{{ authStore.auth.user.abbrName }}</span>
                            </div>
                            <span class="name mt-3 mb-2">{{`${authStore.auth.user.name} ${authStore.auth.user.lastName}`}}</span>
                            <span class="email">{{authStore.auth.user.email}}</span>
                        </div>
                        <div class="content">
                            <div class="item" @click="redirectAccount">
                                <span>Gerenciar sua conta</span>
                            </div>
                        </div>
                        <div class="footer d-flex justify-content-center align-items-center">
                            <v-button theme="b-outline-blue" @action="logout">Sair de todos os dispositivos</v-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .the-header {
        width: calc(100vw - 200px);
        height: 70px;
        background: $wth-primary;
        box-shadow: $shadow-primary;
        border: $br-primary;

        .v-name-sys{
            span{
                color: $blue-primary;
            }
            .name-primary{
                font-size: 1.2rem;
                font-weight: 700;
            }
        }

        .v-notification {
            width: 20px;
            height: 20px;
            border-radius: $bdra-primary;
            background: $grey-primary;
            position: relative;

            .alert-notify {
                width: 15px;
                height: 15px;
                background: $grey-primary;
                border-radius: 10px;
                position: absolute;
                border: $wth-primary solid 2px;
                top: -5px;
                right: -5px;
            }

            .active-notify {
                background: $red-primary;
                animation: active-notify 2s ease infinite;
            }
        }

        .account {
            width: 35px;
            height: 35px;
            border-radius: 100px;
            background: $blue-primary;
            box-shadow: $shadow-primary;
            background-size: contain;
            background-position: center;
            cursor: pointer;
            z-index: 50;

            .account-abbr {
                color: $wth-primary;
                font-weight: 400;
                font-size: .8rem;
            }
        }

        .pop-up-account {
            width: 300px;
            height: auto;
            position: absolute;
            top: 70px;
            background: $wth-primary;
            border: $br-primary;
            box-shadow: $shadow-primary;
            border-radius: $bdra-primary;
            z-index: 50;
            .header{
                padding: 1rem;
                .account-img{
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    background: $blue-primary;
                    box-shadow: $shadow-primary;
                    background-size: contain;
                    background-position: center;

                    .abbr-name{
                        color: $wth-primary;
                        font-weight: 400;
                        font-size: 1.7rem;
                    }
                }
                .name{
                    font-weight: 500;
                }
                .email{
                    font-weight: 400;
                }
            }
            .content{
                padding: 1rem 0;
                border-top: $br-dark-primary;
                .item{
                    width: 100%;
                    padding: .8rem 1rem;
                    cursor: pointer;
                    &:hover{
                        background: $grey-primary;
                    }
                }
            }
            .footer{
                padding: 1rem;
                border-top: $br-dark-primary;
            }
        }
    }
</style>
