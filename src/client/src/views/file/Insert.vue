<script setup>
import { reactive, ref } from 'vue'

/**component**/
import VButton from '@/components/VButton.vue'
import VInputFile from '@/components/VInputFile.vue'

/**stores**/
import { useFileStore } from '@/stores/file.js'

/**propertys**/
const fileStore = useFileStore()
const formUpload = ref(null)

function uploadFile(formData) {
    formUpload.value = formData
}

function submitForm() {
    if (formUpload.value)
        fileStore.uploadFile(formUpload.value)
}

</script>

<template>
    <div class="file-insert">
       <div class="container-fluid">
           <div class="row">
               <div class="col-12 mb-3">
                   <v-input-file @form-data="uploadFile" label="Click para fazer upload" fileRule="jpg, jpeg, png, mp3, wav." />
               </div>
           </div>
           <div class="row">
               <div class="col-12 d-flex align-items-center justify-content-end">
                   <v-button @action="submitForm">Enviar</v-button>
               </div>
           </div>
       </div>
    </div>
</template>

<style scoped lang="scss">
.file-insert {
    @include v-content-main;
}
</style>


