<script setup>

defineProps({
    'label': {
        type: String,
        default: ''
    },
    'placeholder': String,
    'modelValue': String,
})

defineEmits(['update:modelValue'])

</script>

<template>
    <div class="v-select">
        <label v-if="title.length">{{ label }}</label>
        <select @input="$emit('update:modelValue', $event.target.value)">
            <option v-for="item in 10" :value="item">item</option>
        </select>
    </div>
</template>

<style scoped lang="scss">
.v-select {
    width: 100%;
    display: flex;
    flex-direction: column;

    label {
        font-weight: 500;
    }

    select {
        width: 100%;
        height: 35px;
        background: $wth-primary;
        border: $br-primary;
        border-radius: $bdra-primary;
        outline: none;
        padding: 0 10px;
        user-select: none;
    }
}
</style>
