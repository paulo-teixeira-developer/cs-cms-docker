<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    'label': String,
    'modelValue': [Array, Boolean],
    'inputValue': [String, Number],
})

const emit = defineEmits(['update:modelValue']);
const checkeds = ref('');

const compValue = computed({
    get() {
        return props.modelValue
    },
    set(value) {
        emit('update:modelValue', value)
    }
})

</script>

<template>
    <div class="v-checkbox">
        <label class="checkbox-switch my-1">
            <input type="checkbox" :id="inputValue" :value="inputValue" v-model="compValue" />
            <span class="slider"></span>
        </label>
        <label class="labelInput ms-1" :for="inputValue">{{ label }}</label>
    </div>
</template>

<style scoped lang="scss">
.v-checkbox {
    width: fit-content;
    display: flex;
    align-items: center;
    user-select: none;

    .checkbox-switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: $grey-dark-primary;
            border-radius: 20px;
            -webkit-transition: .3s ease-in-out;
            transition: .3s ease-in-out;

            &:before {
                position: absolute;
                content: "";
                height: 16px;
                width: 16px;
                top: 2px;
                right: 2px;
                bottom: 2px;
                left: 2px;
                background-color: $wth-primary;
                -webkit-transition: .3s ease-in-out;
                transition: .3s ease-in-out;
                border-radius: 50%;
            }
        }

        input {
            opacity: 0;
            width: 0;
            height: 0;

            &:checked {
                + {
                    .slider {
                        background-color: $blue-primary;

                        &:before {
                            -webkit-transform: translateX(20px);
                            -ms-transform: translateX(20px);
                            transform: translateX(20px);
                        }
                    }
                }
            }
        }
    }

    .labelInput {
        font-weight: 500;
        cursor: pointer;
        color: $blk-secondary;
    }
}
</style>
