<script setup>

    /**component**/
    import VButton from '@/components/VButton.vue'

    const props = defineProps({
        'title': {
            type: String,
            default: ""
        },
        'message': {
            type: String,
            default: ""
        }
    })


    const emit = defineEmits(['accepted', 'rejected'])

    function setAccepted() {
        emit('accepted')
    }

    function setRejected() {
        emit('rejected')
    }

</script>

<template>
    <div class="v-confirm">
        <div class="mask-confirm" @click="setRejected"></div>
        <div class="container-confirm">
            <div class="content d-flex flex-column p-3">
                <span class="title">{{title}}</span>
                <span v-if="message" class="message mt-3">{{message}}</span>
            </div>
            <div class="footer d-flex justify-content-between p-3">
                <v-button @action="setRejected" btnClass="me-2" theme="b-grey">Cancelar</v-button>
                <v-button @action="setAccepted" btnClass="b-outline-blue">Continuar</v-button>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
    .v-confirm {
        width: 100vw;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99;

        .mask-confirm {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background: $blk-primary;
            opacity: .5;
            position: fixed;
        }

        .container-confirm {
            width: 300px;
            height: auto;
            position: absolute;
            background: $wth-primary;
            border-radius: $bdra-primary;
            .content{
                .title {
                    font-weight: 500;
                    font-size: 1rem;
                }
                .message {
                    font-size: .9rem;
                }
            }
            .footer{
                width: 100%;
                border-top: $br-primary;
            }
        }
    }
</style>
