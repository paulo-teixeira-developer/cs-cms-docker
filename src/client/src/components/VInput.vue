<script setup>

defineProps({
    'label': String,
    'placeholder': String,
    'modelValue': String,
    'btnClass': String,
    'max': Number,
    'type': {
        type: String,
        default: "text"
    }
})

defineEmits(['update:modelValue','enter'])
</script>

<template>
    <div :class="['v-input', btnClass]">
        <label class="mb-1">{{ label }}</label>
        <input :type="type" :placeholder="placeholder" :value="modelValue" :maxlength="max" @input="$emit('update:modelValue', $event.target.value)" @keyup.enter="$emit('enter')" />
    </div>
</template>

<style scoped lang="scss">
    .v-input {
        width: 100%;
        display: flex;
        flex-direction: column;

        label {
            font-weight: 500;
            color: $blk-secondary;
        }

        input {
            width: 100%;
            height: 35px;
            padding: 0 10px;
            font-weight: 400;
            border: $br-primary-input;
            border-radius: $bdra-primary;
            background: $wth-primary;
            color: $blk-secondary;

            &:focus {
                box-shadow: 0 0 0 0;
                border: $br-primary-input;
                outline: 0;
            }

            &::placeholder {
                font-style: italic;
                color: $grey-primary-placeholder;
            }
        }
    }
</style>
