<script setup>
   //
</script>

<template>
    <div class="v-load d-flex justify-content-center align-items-center">
        <div class="mask-load"></div>
        <div class="loading">
            <span class="loader">Carregando</span>
        </div>
    </div>
</template>

<style scoped lang="scss">
.v-load {
    width: 100vw;
    height: 100vh;
    position: fixed;
    top: 0;
    z-index: 90;

    .mask-load {
        width: 100%;
        height: 100%;
        background: $blk-primary;
        opacity: .5;
        position: absolute;
        z-index: 91;
    }

    .loading {
        position: relative;
        z-index: 92;

        .loader {
            color: $wth-primary;
            display: inline-block;
            position: relative;
            font-size: 20px;
            box-sizing: border-box;
            font-weight: 600;
        }

        .loader::after {
            content: '';
            width: 5px;
            height: 5px;
            background: currentColor;
            position: absolute;
            bottom: 3px;
            right: -12px;
            box-sizing: border-box;
            animation: loader 1s linear infinite;
        }

        @keyframes loader {
            0% {
                box-shadow: 10px 0 rgba(255, 255, 255, 0), 20px 0 rgba(255, 255, 255, 0);
            }

            50% {
                box-shadow: 10px 0 white, 20px 0 rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 10px 0 white, 20px 0 white;
            }
        }
    }
}
</style>
