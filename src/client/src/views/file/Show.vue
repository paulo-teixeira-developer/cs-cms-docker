<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

/** composable **/
import { isImage, isAudio, bytesToSize } from '@/composables/validator.js'

/**component**/
import VButton from '@/components/VButton.vue'
import VReadOnlyField from '@/components/VReadOnlyField.vue'
import VImage from '@/components/VImage.vue'
import VAudio from '@/components/VAudio.vue'
import VConfirm from '@/components/VConfirm.vue'

/**stores**/
import { useFileStore } from '@/stores/file.js'

/**propertys**/
const route = useRoute()
const router = useRouter()
const fileStore = useFileStore()
const confirmDelete = ref(false)

/** load data **/
onMounted(() => {
    fileStore.getFileById(route.params.id)
})

function toggleConfirmDelete() {
    confirmDelete.value = !confirmDelete.value
}

async function deleteFile(id) {
    await toggleConfirmDelete()
    await fileStore.deleteFileById(id)
    await deleteFileStatus()
}

function deleteFileStatus() {
    if (fileStore.deleteStatus == "success")
        router.push({ name: 'fileIndex' })
}

const fileImg = computed(()=>{
    return (fileStore.file?.data?.id) ? `${import.meta.env.VITE_API_FILE}${fileStore.file.data?.file_path?.name}/${fileStore.file?.data.hash}.${fileStore.file.data?.file_format?.name}` : ""
})

const fileAudio = computed(()=>{
    return (fileStore.file?.data?.id) ? `${import.meta.env.VITE_API_CMS}files/streaming/${fileStore.file.data.file_path.name}/${fileStore.file.data.hash}.${fileStore.file.data.file_format.name}` : ""
})

const isFileImage = computed(() => isImage(fileStore.file.data?.file_format?.name));
const isFileAudio = computed(() => isAudio(fileStore.file.data?.file_format?.name));

</script>

<template>
    <div class="file-show">
       <div class="container-fluid">
           <div class="row mb-3">
               <div class="col-12 d-flex justify-content-center" v-if="fileStore.file.data.id">
                   <v-read-only-field size="small" class="me-3" label="Nome" :content="fileStore.file.data?.name"/>
                   <v-read-only-field size="small" class="me-3" label="Formato" :content="fileStore.file.data?.file_format?.name"/>
                   <v-read-only-field size="small" class="me-3" label="Tamanho" :content="bytesToSize(fileStore.file.data?.size)"/>
                   <v-read-only-field size="small" class="me-3" label="Criado" :content="fileStore.file.data?.created_at"/>
               </div>
           </div>
           <div class="row mb-3">
               <div class="col-12">
                   <div v-if="isFileImage" class="file-image">
                       <v-image :url="fileImg" />
                   </div>
                   <div v-if="isFileAudio" class="file-audio">
                       <v-audio :url="fileAudio" :title="fileStore.file.data.name" />
                   </div>
                   <v-confirm v-if="confirmDelete" @accepted="deleteFile(fileStore.file.data.id)" @rejected="toggleConfirmDelete"
                              title="Ao confirmar está ação, o arquivo será excluído."
                              message="Tem certeza que deseja excluir? Lembre-se que está ação não poderá ser desfeita."/>
               </div>
           </div>
           <div class="row">
               <div class="col-12 d-flex align-items-center justify-content-end">
                   <v-button :disabled="!fileStore.file.data.id" @action="toggleConfirmDelete">Excluir</v-button>
               </div>
           </div>
       </div>
    </div>
</template>

<style scoped lang="scss">
.file-show {
    @include v-content-main;

    .file-image{
        width: 100%;
        height: 400px;
    }

    .file-audio{
        width: 100%;
    }
}
</style>


