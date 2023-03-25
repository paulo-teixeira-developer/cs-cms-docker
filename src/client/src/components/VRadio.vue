<script setup>
import { computed, ref } from 'vue'

const props = defineProps({
    'appClass': String,
    'name': String,
    'label': String,
    'modelValue': [String, Number],
    'content': Object,
})

const emit = defineEmits(['update:modelValue']);

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
    <div :class="['v-radio', appClass]">
        <label class="radio-switch my-1">
            <input type="radio" :id="content.id" :name="name" :value="content.value" v-model="compValue">
            <span class="slider"></span>
        </label>
        <label class="labelInput ms-1" :for="content.id">{{ label }}</label><br>
    </div>
</template>

<style scoped lang="scss">
.v-radio {
    width: fit-content;
    display: flex;
    align-items: center;
    user-select: none;

    .radio-switch {
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
            background-color: $grey-primary;
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
    }
}
</style>
