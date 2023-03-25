<script setup>
import { computed } from 'vue'

const props = defineProps({
    'imageClass': {
        type: String,
        default: ""
    },
    'url': {
        type: String,
        default: ""
    },
    'size': {
        type: String,
        default: "contain"
    },
    'descriptive': {
        type: String,
        default: ''
    }
})

</script>

<template>
    <div :class="['v-image-upload', imageClass]" :style="{ backgroundImage: `url(${url})`, backgroundSize: size }" @click="$emit('action')">
        <div class="backdrop d-flex flex-column justify-content-center align-items-center" v-if="descriptive">
            <span class="icone icon-picture-square mb-3"></span>
            <span class="title">{{ descriptive }}</span>
        </div>
    </div>
</template>

<style scoped lang="scss">
.v-image-upload {
    width: 100%;
    height: 100%;
    background-repeat: no-repeat;
    background-position: center;

    .backdrop {
        width: 100%;
        height: 100%;
        transition: .1s;

        .icone {
            font-size: 30px;
            color: $blue-primary;
        }

        .title {
            font-weight: 600;
            color: $blue-primary;
        }
    }
}
</style>
