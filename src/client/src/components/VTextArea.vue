<script setup>

defineProps({
    'label': String,
    'placeholder': String,
    'modelValue': String,
    'btnClass': String,
    'max': Number,
})

defineEmits(['update:modelValue'])
</script>

<template>
    <div :class="['v-text-area', btnClass]">
        <label class="mb-1">{{ label }}</label>
        <textarea :placeholder="placeholder" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" :maxlength="max">
        </textarea>
    </div>
</template>

<style scoped lang="scss">
.v-text-area {
    width: 100%;
    display: flex;
    flex-direction: column;

    label {
        font-weight: 500;
    }

    textarea {
        width: 100%;
        height: 150px;
        padding: 10px;
        resize: none;
        font-weight: 400;
        border: $br-primary;
        border-radius: $bdra-primary;

        &:focus {
            outline: 0;
        }

        &::placeholder {
            font-style: italic;
            color: $grey-primary-placeholder;
        }
    }
}
</style>
