<script setup>


/**component**/
import VButton from '@/components/VButton.vue'

const props = defineProps({
    'title': String,
    'profileImg': {
        type: String,
        default: null
    },
    'initials': {
        type: String,
        default: 'PT'
    }
})



const emit = defineEmits(['close'])

function _close() {
    emit('close')
}

const fileDetails = ref(null)
async function fileUpload(event) {
    fileDetails.value = await event.target.files[0]
    let formData = await new FormData();
    await formData.append('file', fileDetails.value)
    await emit('form-data', formData)
}


</script>

<template>
    <div class="v-pop-up">
        <div class="mask" @click="_close"></div>
        <div class="popup-container container-fluid">
            <div class="row">
                <div class="header col-12 d-flex justify-content-between align-items-center py-3">
                    <span class="title">{{ title }}</span>
                    <span class="icons icon-close" @click="_close"></span>
                </div>
            </div>
            <div class="row">
                <div class="content col-12 py-3 d-flex justify-content-center align-items-center">
                    <div class="profile-img d-flex justify-content-center align-items-center" :style="{backgroundImage: `url(${profileImg})`}">
                        <span class="initials">{{ initials }}</span>
                        <input type="file" @change="fileUpload($event)" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="footer col-12 py-3 d-flex justify-content-between">
                    <v-button theme="outline-primary" @action="">Alterar</v-button>
                    <v-button @action="_close">Cancelar</v-button>
                </div>
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
    z-index: 99;

    .mask {
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        position: fixed;
        background: $blk-primary;
        opacity: .3;
    }

    .popup-container {
        width: 300px;
        position: absolute;
        background: $wth-primary;
        border-radius: $bdra-primary;

        .header {
            border: $br-primary;

            .title {
                font-size: 1.2rem;
                font-weight: 600;
                color: $blue-primary;
            }

            .icons {
                font-size: 2rem;
                color: $grey-primary;
                cursor: pointer;
            }
        }

        .content {
            .profile-img{
                width: 200px;
                height: 200px;
                border-radius: 50%;
                background: $blue-primary;
                background-repeat: no-repeat;
                background-size: contain;
                background-position: center;
                cursor: pointer;
                input {
                    display: none;
                }
                .initials{
                    font-size: 5rem;
                    font-weight: 600;
                    color: $wth-primary;
                }
            }
        }

        .footer {
            border: $br-primary;
        }
    }
}
</style>
