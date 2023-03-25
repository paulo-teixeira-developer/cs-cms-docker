<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()
const props = defineProps({
    'btn-class': {
        type: String,
        default: ""
    },
    'theme': {
        type: String,
        default: "btn-primary"
    },
    'push': {
        type: Object,
        default: null
    },
    'history': {
        type: String,
        default: null
    },
    'target': {
        type: String,
        default: null
    },
    'disabled': {
        type: Boolean,
        default: false
    }
})

function btnClick() {
    if (props.push) {
        router.push(props.push)
    }
    else if (props.history) {
        router.go(props.history)
    }
    else if (props.target) {
        window.open(props.target)
    }
}
</script>

<template>
    <div class="v-button-link">
        <button :disabled="disabled" :class="[btnClass, theme]" @Click="btnClick">
            <slot></slot>
        </button>
    </div>
</template>

<style scoped lang="scss">
.v-button-link {
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
