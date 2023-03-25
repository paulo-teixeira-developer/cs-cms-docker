<script setup>
import { ref } from 'vue'

const logo = ref(`${import.meta.env.VITE_API_FILE}reserved/img/logo-codigo-storm-full-pb.png`);

</script>

<template>
    <div class="the-nav">
        <div class="header-nav d-flex justify-content-center align-items-center">
            <img :src="logo" alt="">
        </div>
        <div class="bl-nav mt-4">
            <div class="bl-title"><span>Menu Principal</span></div>
            <router-link :to="{ name: 'dashboard' }" class="bl-item"><span>Dashboard</span></router-link>
            <router-link :to="{ name: 'file', query: {page: 1, order: 'desc'} }" class="bl-item"><span>File</span></router-link>
            <router-link :to="{ name: 'post', query: {page: 1, order: 'desc'} }" class="bl-item"><span>Posts</span></router-link>
        </div>
        <div class="bl-nav mt-4">
            <div class="bl-title"><span>Configurações</span></div>
            <router-link :to="{ name: 'account' }" class="bl-item"><span>Conta</span></router-link>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .the-nav {
        width: 200px;
        min-height: 100vh;
        background: $wth-primary;
        box-shadow: $shadow-primary;
        display: flex;
        flex-direction: column;
        user-select: none;

        .header-nav {
            width: 100%;
            height: 70px;
            box-sizing: border-box;
            padding: 0 1rem;
            background: $blue-primary;

            img {
                max-width: 60%;
                max-height: 60%;
            }
        }

        .bl-nav {
            width: 100%;
            display: flex;
            flex-direction: column;

            .bl-title {
                margin-bottom: .5rem;
                padding: 0 1rem;

                span {
                    font-size: .8rem;
                    font-weight: 600;
                    text-transform: uppercase;
                    color: $blue-primary;
                }
            }

            .bl-item {
                width: 100%;
                padding: .7rem 1rem;
                box-sizing: border-box;
                transition: .1s;
                text-transform: uppercase;

                span {
                    font-size: .7rem;
                    color: $blue-primary;
                    font-weight: 500;
                }

                &:hover {
                    background: $blue-primary;

                    span {
                        color: $wth-primary;
                        font-weight: 500;
                    }
                }
            }

            .router-link-active {
                background: $blue-primary;

                span {
                    font-size: .7rem;
                    color: $wth-primary;
                    font-weight: 500;
                }
            }
        }
    }
</style>
