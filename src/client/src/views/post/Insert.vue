<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

/**component**/
import VButton from '@/components/VButton.vue'
import VRadio from '@/components/VRadio.vue'
import VButtonLink from '@/components/VButtonLink.vue'
import VImageUpload from '@/components/VImageUpload.vue'
import VInput from '@/components/VInput.vue'
import VTextArea from '@/components/VTextArea.vue'
import VTextEditor from '@/components/VTextEditor.vue'
import VCheckbox from '@/components/VCheckbox.vue'
import VPopUp from '@/components/VPopUp.vue'
import VPaginate from '@/components/VPaginate.vue'
import FileTable from '@/components/FileTable.vue'

/**stores**/
import { usePostStore } from '@/stores/post.js'
import { useFileStore } from '@/stores/file.js'
import { useCategoryStore } from '@/stores/category.js'

/**propertys**/
const postStore = usePostStore()
const fileStore = useFileStore()
const categoryStore = useCategoryStore()
const router = useRouter()
const _file = ref(false)
const _cat = ref(false)

/** load data **/
onMounted(() => {
    fileStore.getFilesByPath({file_path_id: [3]})
    categoryStore.getCategories()
})

onUnmounted(()=>{
    postStore.postCleaning()
})

function toggleThumbnail() {
    _file.value = !_file.value
}
function toggleCat() {
    _cat.value = !_cat.value
}

function updateThumbnail(obj) {
    toggleThumbnail()
    postStore.setThumbnail(obj)
}

function paginationFile(param) {
    fileStore.getFiles(param)
}

function paginationCat(param) {
    fileStore.getCategories(param)
}

async function storePost() {
    const response = await postStore.storePost()
}

const urlImage = computed(() => {
    return (postStore.thumbnail) ? `${import.meta.env.VITE_API_FILE}${postStore.thumbnail}` : ''
})


</script>

<template>
    <div class="post-insert">
       <div class="container-fluid">
           <div class="row">
               <div class="col-12">
                   <div class="thumbnail-container d-flex justify-content-center align-items-center mb-3">
                       <v-image-upload :url="urlImage" size="cover" :descriptive="(!postStore.thumbnail) ? 'Inserir Thumbnail': ''" @action="toggleThumbnail" />
                       <v-pop-up title="Thumbnail" v-if="_file" @close="toggleThumbnail">
                           <template v-slot:content>
                               <file-table :content="fileStore.files.data" @action="updateThumbnail" />
                           </template>
                           <template v-slot:footer>
                               <v-paginate :data="fileStore.files.paginate" @get-param="paginationFile" />
                           </template>
                       </v-pop-up>
                   </div>
               </div>
               <div class="col-12">
                   <v-input btn-class="mb-3" v-model.trim="postStore.dataStore.title" :max="200" label="Titulo"
                            placeholder="digite o título do post." />
                   <v-text-area btn-class="mb-3" v-model.trim="postStore.dataStore.summary" :max="200" label="Resumo"
                                placeholder="digite o resumo do post." />
                   <v-text-editor btn-class="mb-3" v-model.trim="postStore.dataStore.content"
                                  label="Conteúdo Principal" />
               </div>
           </div>
           <div class="row">
               <div class="col-6 d-flex align-items-center justify-content-start">
                   <v-checkbox class="me-2" label="Publicar" inputValue="item" v-model="postStore.dataStore.published" />
               </div>
               <div class="col-6 d-flex align-items-center justify-content-end">
                   <v-button theme="b-grey" btn-class="me-2" @click="toggleCat">Categorias</v-button>
                   <v-pop-up title="Categorias" v-if="_cat" @close="toggleCat">
                       <template v-slot:content>
                           <v-radio app-class="mb-2" name="categoria"
                                    v-for="obj in categoryStore.categories.data" :content="{ id: obj.id, value: obj.id }"
                                    :label="obj.name"
                                    v-model="postStore.dataStore.category_id" />
                       </template>
                       <template v-slot:footer>
                           <v-paginate :data="categoryStore.categories.paginate" @get-param="paginationCat" />
                       </template>
                   </v-pop-up>
                   <v-button btnClass="me-2" @action="storePost">Salvar</v-button>
               </div>
           </div>
       </div>
    </div>
</template>

<style scoped lang="scss">
.post-insert {
    @include v-content-main;

    .thumbnail-container {
        width: 100%;
        height: 400px;
        background: $grey-secondary;
        border: $grey-primary solid 1px;
        cursor: pointer;

        .thumbnail-content {
            width: 100%;
            height: 100%;
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover;
        }
    }
}
</style>


