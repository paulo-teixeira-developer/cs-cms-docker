<script setup>
/**component**/
import TheNav from '@/components/layout/nav/TheNav.vue'
import TheHeader from '@/components/layout/header/TheHeader.vue'
import VButtonLink from '@/components/VButtonLink.vue'
import { useRoute } from 'vue-router'
/** store **/

/** propiedades do componente **/
const route = useRoute()

</script>
<template>
    <div class="v-main-layout d-flex">
        <the-nav />
        <div class="d-flex flex-column">
            <the-header />
            <div class="container my-5">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="v-main-title">
                            <div class="container">
                                <div class="row">
                                    <div class="col-6 d-flex justify-content-start align-items-baseline">
                                        <span class="title me-2">{{ route.meta.title }}</span>
                                        <span class="subtitle">{{ route.meta.subtitle }}</span>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end align-items-center">
                                        <v-button-link v-if="route.meta.goBack" theme="b-outline-blue"
                                            history="-1">Voltar</v-button-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="v-content-main">
                            <slot name="content"></slot>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.v-main-layout {
    width: 100%;
    min-height: 100vh;
    background: $grey-secondary;

    .v-main-title {
        width: 100%;
        box-sizing: border-box;
        user-select: none;

        .title {
            font-size: 2rem;
            font-weight: 500;
            color: $blue-primary;
        }

        .subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            color: $blue-primary;
        }
    }

    .v-content-main {
        width: 100%;
        min-height: auto;
        box-sizing: border-box;
    }
}
</style>