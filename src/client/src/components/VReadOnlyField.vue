<script setup>
    defineProps({
        'label': {
            type: String,
            default: ""
        },
        'content': {
            type: [String, Number],
            default: ""
        },
        'size': {
            type: String,
            default: "default"
        },
        'type': {
            type: String,
            default: "horizontal"
        },
    })

    defineEmits(['action'])
</script>

<template>
    <div :class="['v-read-only-field', size, type]">
        <div v-if="type == 'horizontal'" class="label me-1"><span>{{label}}</span>:</div>
        <div v-else class="label mb-2"><span>{{label}}</span></div>
        <div class="content"><span>{{content}}</span></div>
    </div>
</template>

<style scoped lang="scss">
    .v-read-only-field {
        .label{
            font-weight: 500;

        }
        .content{
            //
        }
    }
    /** type **/
    .horizontal{
        display: flex;
        flex-direction: row;
    }
    .vertical{
        display: flex;
        flex-direction: column;
    }

    /** size **/
    .default {
         .label, .content {
             span {
                 font-size: 1rem;
             }
         }
     }

    .small {
        .label, .content {
            span {
                font-size: .8rem;
            }
        }
    }


</style>
