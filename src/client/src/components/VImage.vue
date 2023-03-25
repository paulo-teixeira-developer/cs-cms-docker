<script setup>

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
    }
})

</script>

<template>
    <div :class="['v-image', imageClass]" :style="{ backgroundImage: `url(${url})`, backgroundSize: size }" @click="$emit('action')"></div>
</template>

<style scoped lang="scss">
.v-image {
    width: 100%;
    height: 100%;
    background-color: $blk-primary;
    background-repeat: no-repeat;
    background-position: center;
}
</style>
