<script setup>

import { computed } from 'vue'

/**store**/
import { useAlertStore } from '@/stores/alert.js'

const alertStore = useAlertStore()

/** estilos **/
const style = computed(() => {
    switch (alertStore.data.status) {
        case "error":
            return "alert-error"
        case "not-found":
            return "alert-not-found"
        default:
            return "alert-success"
    }
})
</script>

<template>
    <div class="app-alert px-3 py-3" :class="style">
        <div class="container inherit-fill">
            <div class="row inherit-fill">
                <div class="col-11 d-flex flex-column justify-content-center align-items-start">
                    <span class="desc" v-for="item in alertStore.data.data">{{ item }}</span>
                </div>
                <div class="col-1 d-flex align-items-center justify-content-center">
                    <span class="icone icon-close-circle" @click="alertStore.setAlert()"></span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .app-alert {
        width: 500px;
        height: auto;
        background: $blue-dark-primary;
        box-shadow: $shadow-primary;
        outline: $br-primary;
        border-radius: $bdra-primary;
        position: fixed;
        top: 50px;
        left: calc(50% - 250px);
        box-sizing: border-box;
        z-index: 89;

        .desc{
            color: $grey-primary;
        }
        .icone {
            font-size: 25px;
            color: $grey-primary;
            cursor: pointer;
        }
    }
</style>
