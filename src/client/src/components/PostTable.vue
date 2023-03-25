<script setup>

/** composable **/
import { abbrName } from '@/composables/helper.js'

defineProps({
    'content': Object,
})

const emit = defineEmits(['action'])

function itemClick(id) {
    emit('action', id)
}
</script>

<template>
    <div class="v-table">
        <div class="grid-header p-3">
            <div class="grid-item"><span>#ID</span></div>
            <div class="grid-item"><span>Título</span></div>
            <div class="grid-item"><span>Publicado</span></div>
            <div class="grid-item"><span>Criado</span></div>
            <div class="grid-item"><span>Atualizado</span></div>
            <div class="grid-item"><span>Autor</span></div>
        </div>
        <div class="grid-content p-3" v-for="obj in content" @click="itemClick(obj.id)">
            <div class="grid-item"><span class="text-item">{{ obj.id }}</span></div>
            <div class="grid-item"><span class="text-item">{{ obj.title }}</span></div>
            <div class="grid-item"><span class="text-item">{{ (obj.published) ? 'Sim' : 'Não' }}</span></div>
            <div class="grid-item"><span class="text-item">{{ obj.created_at }}</span></div>
            <div class="grid-item"><span class="text-item">{{ obj.updated_at }}</span></div>

            <div class="grid-item"><span class="text-item">{{ abbrName(`${obj.user.person.name} ${obj.user.person.last_name}`.toUpperCase()) }}</span></div>
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
            grid-template-columns: 5% 60% 10% 10% 10% 5%;
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
                background: $grey-secondary;
                background-repeat: no-repeat;
                background-size: cover;
                background-position: center;
            }
            .grid-item{
                .text-item{
                    font-size: .9rem;
                    color: $blk-tertiary;
                }
            }
        }
    }
</style>
