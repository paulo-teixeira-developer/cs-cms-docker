<script setup>

defineProps({
    'title': String,
})

const emit = defineEmits(['close'])

function _close() {
    emit('close')
}

</script>

<template>
    <div class="v-pop-up">
       <div class="mask" @click="_close"></div>
       <div class="container-popup">
           <div class="header d-flex justify-content-between align-items-center p-3">
               <span class="title">{{ title }}</span>
               <span class="icons icon-close-circle" @click="_close"></span>
           </div>
           <div class="content p-3">
               <slot name="content"></slot>
           </div>
           <div class="footer p-3">
               <slot name="footer"></slot>
           </div>
       </div>
    </div>
</template>

<style scoped lang="scss">
.v-pop-up {
    width: 100vw;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 89;

    .mask {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        background: $blk-primary;
        opacity: .3;
    }

    .container-popup {
        width: 80vw;
        position: absolute;
        background: $wth-primary;
        border-radius: $bdra-primary;

        .header {
            border-bottom: $br-primary;

            .title {
                font-size: 1.2rem;
                font-weight: 500;
                color: $blue-primary;
            }

            .icons {
                font-size: 25px;
                color: $grey-dark-primary;
                cursor: pointer;
            }
        }

        .content {
            max-height: 50vh;
            overflow-y: scroll;
        }

        .footer {
            border-top: $br-primary;
        }
    }
}
</style>
