<script setup>
/** imports **/
import { RouterView, useRouter } from 'vue-router'
import VTransition from '@/components/VTransition.vue'
import VAlert from '@/components/VAlert.vue'
import VLoad from '@/components/VLoad.vue'
/** stores **/
import { useLoadStore } from '@/stores/load.js'
import { useAlertStore } from '@/stores/alert.js'
import { useAuthStore } from '@/stores/auth.js'
/** instancia stores **/
const loadStore = useLoadStore()
const alertStore = useAlertStore()
const authStore = useAuthStore()
authStore.setAuth();
</script>

<template>
    <v-transition>
        <v-load v-if="loadStore.active" />
        <v-alert v-if="alertStore.active" />
    </v-transition>
    <RouterView />
</template>

<style scoped lang="scss">
//
</style>
