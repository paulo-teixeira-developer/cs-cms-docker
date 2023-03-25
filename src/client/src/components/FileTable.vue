<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

/** composable **/
import { bytesToSize, isImage, isAudio } from '@/composables/validator.js'

const VITE_API_CMS = ref(import.meta.env.VITE_API_FILE)
const router = useRouter()
const emit = defineEmits(['action'])
const fileFormat = ref(null)

defineProps({
    'content': Object,
})

function itemClick(id) {
    emit('action', id)
}


</script>

<template>
    <div class="v-table">
        <div class="grid-header p-3">
            <div class="grid-item"><span>#</span></div>
            <div class="grid-item"><span>Nome</span></div>
            <div class="grid-item"><span>Formato</span></div>
            <div class="grid-item"><span>Tamanho</span></div>
            <div class="grid-item"><span>Criado</span></div>
        </div>
        <div class="grid-content p-3" v-for="obj in content" @click="itemClick(obj)">
            <div class="grid-item">
                <div v-if="isImage(obj.file_format.name)" class="file" :style="{ backgroundImage: `url(${VITE_API_CMS}${obj.file_path.name}/${obj.hash}.${obj.file_format.name})` }"></div>
                <div v-else-if="isAudio(obj.file_format.name)" class="file d-flex justify-content-center align-items-center">
                    <span class="icone icon-headphone"></span>
                </div>
            </div>
            <div class="grid-item"><span class="text-item">{{ obj.name }}</span></div>
            <div class="grid-item"><span class="text-item">{{ obj.file_format.name }}</span></div>
            <div class="grid-item"><span class="text-item">{{ bytesToSize(obj.size) }}</span></div>
            <div class="grid-item"><span class="text-item">{{ obj.created_at }}</span></div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.v-table {
    .grid-header,
    .grid-content {
        width: 100%;
        height: auto;
        display: grid;
        grid-template-columns: 10% 55% 10% 10% 15%;
        box-sizing: border-box;
        .grid-item {
            display: flex;
            align-items: center;
        }
    }
    .grid-header {
        background: $wth-primary;
        box-shadow: $shadow-primary;
        border: $br-primary;
        span {
            font-weight: 500;
            color: $blue-primary;
        }
    }
    .grid-content {
        cursor: pointer;
        border-bottom: $br-primary;
        .file {
            width: 40px;
            height: 40px;
            background: $grey-primary;
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
        }
        .grid-item{
            .text-item{
                font-size: .9rem;
                color: $blk-tertiary;
            }
            .icone{
                font-size: 1.5rem;
                color: $blue-primary;
            }
        }
    }
}
</style>
