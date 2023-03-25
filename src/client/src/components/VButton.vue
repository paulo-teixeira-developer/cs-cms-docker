<script setup>
const props = defineProps({
    'btnClass': {
        type: String,
        default: ""
    },
    'theme': {
        type: String,
        default: "b-default"
    },
    'disabled': {
        type: Boolean,
        default: false
    }
})
const emit = defineEmits(['action'])

function clickBtn() {
    if (!props.disabled)
        emit('action')
}
</script>

<template>
    <div class="v-button">
        <button :disabled="disabled" :class="[btnClass, theme]" @click="clickBtn">
            <slot></slot>
        </button>
    </div>
</template>

<style scoped lang="scss">
    .v-button {
        width: fit-content;
        user-select: none;

        button {
            width: fit-content;
            height: 35px;
            font-family: 'Rubik';
            padding: 0 .5rem;
            border-radius: $bdra-primary;
            font-weight: 500;
            cursor: pointer;
        }

        .b-default {
            background: $blue-primary;
            color: $wth-primary;
            border: none;
        }

        .b-outline-blue {
            background: $wth-primary;
            color: $blue-primary;
            border: $blue-primary solid 1px;
        }

        .b-grey {
            background: $grey-primary;
            color: $blk-secondary;
            border: none;
        }
    }
</style>
