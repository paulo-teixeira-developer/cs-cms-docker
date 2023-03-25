<script setup>
    //
</script>

<template>
    <Transition name="v-transition">
        <slot></slot>
    </Transition>
</template>

<style scoped lang="scss">
.v-transition-enter-active,
.v-transition-leave-active {
    opacity: 1;
    transition: 0.2s ease;
}

.v-transition-enter-from,
.v-transition-leave-to {
    opacity: 0;
    transition: 0.2s ease;
}
</style>
