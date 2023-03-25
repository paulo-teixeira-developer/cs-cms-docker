<script setup>
import { onMounted, watch, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

/**component**/
import VButtonLink from '@/components/VButtonLink.vue'
import VPaginate from '@/components/VPaginate.vue'
import FileTable from '@/components/FileTable.vue'

/**stores**/
import { useFileStore } from '@/stores/file.js'

/**propertys**/
const fileStore = useFileStore()
const route = useRoute()
const router = useRouter()

onMounted(() => {
    fileStore.getFiles(route.query)
})

watch(() => route.query, (newQuery) => {
    if (route.name == "fileList")
        fileStore.getFiles(newQuery)
})

function pagination(param) {
    router.push({ name: 'fileList', query: {...param, ...{order: 'desc'}} })
}

function goShow(obj) {
    router.push({ name: 'fileShow', params: { id: obj.id } })
}

</script>
<template>
    <div class="file-index">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <v-button-link theme="b-outline-blue" :push="{ name: 'fileInsert' }">Novo File</v-button-link>
                </div>
            </div>
            <div class="row">
                <div class="col-12 py-3">
                    <file-table :content="fileStore.files.data" @action="goShow" />
                </div>
                <div class="col-12 d-flex justify-content-center pt-3">
                    <v-paginate :data="fileStore.files.paginate" @get-param="pagination" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
.file-index {
    @include v-content-main;
}
</style>


