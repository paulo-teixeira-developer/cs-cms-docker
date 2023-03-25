<script setup>
import { ref, computed } from 'vue'

/** composable **/
import { bytesToSize } from '@/composables/validator.js'

const props = defineProps({
    'inputClass': {
        type: String,
        default: ""
    },
    'label': {
        type: String,
        default: ""
    },
    'fileRule': {
        type: String,
        default: ""
    },
})

const fileDetails = ref(null)
const emit = defineEmits(['form-data'])

async function fileUpload(event) {
    fileDetails.value = await event.target.files[0]
    let formData = await new FormData();
    await formData.append('file', fileDetails.value)
    await emit('form-data', formData)
}

const fileInfo = computed(() => {
    return (fileDetails.value?.name) ? `${fileDetails.value.name} (${bytesToSize(fileDetails.value.size)})` : props.label
})

</script>

<template>
    <div class="v-input-file">
        <label class="file-container d-flex justify-content-center align-items-center">
            <div class="file-content d-flex flex-column justify-content-center align-items-center">
                <span class="icone icon-cloud-upload mb-1"></span>
                <span class="title mb-1">{{ fileInfo }}</span>
                <span class="desc">{{ fileRule }}</span>
            </div>
            <input type="file" @change="fileUpload($event)" />
        </label>
    </div>
</template>

<style scoped lang="scss">
    .v-input-file{
        width: 100%;
        height: 200px;
        input {
            display: none;
        }
        .file-container {
            width: 100%;
            height: 100%;
            background: $wth-primary;
            border: $grey-primary dashed 4px;
            cursor: pointer;

            .file-content {
                .icone {
                    font-size: 40px;
                    color: $blue-primary
                }

                .title {
                    font-weight: 500;
                    text-align: center;
                    color: $blue-primary;
                }

                .desc{
                    font-size: .9rem;
                    font-weight: 400;
                    text-align: center;
                    color: $blue-primary;
                }
            }
        }
    }
</style>
