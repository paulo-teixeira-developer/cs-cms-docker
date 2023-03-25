<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

/**component**/
import VButton from '@/components/VButton.vue'
import VRadio from '@/components/VRadio.vue'
import VReadOnlyField from '@/components/VReadOnlyField.vue'
import VButtonLink from '@/components/VButtonLink.vue'
import VImage from '@/components/VImage.vue'
import VInput from '@/components/VInput.vue'
import VTextArea from '@/components/VTextArea.vue'
import VTextEditor from '@/components/VTextEditor.vue'
import VCheckbox from '@/components/VCheckbox.vue'
import VPopUp from '@/components/VPopUp.vue'
import VPaginate from '@/components/VPaginate.vue'
import FileTable from '@/components/FileTable.vue'
import VConfirm from '@/components/VConfirm.vue'

/**stores**/
import { usePostStore } from '@/stores/post.js'
import { useFileStore } from '@/stores/file.js'
import { useCategoryStore } from '@/stores/category.js'

/**propertys**/
const route = useRoute()
const router = useRouter()
const postStore = usePostStore()
const fileStore = useFileStore()
const categoryStore = useCategoryStore()
const targetPage = computed(() => { return `${import.meta.env.VITE_SITE}/posts/${postStore.post.data?.slug}` })
const _file = ref(false)
const _cat = ref(false)
const confirmDelete = ref(false)

/** load data **/
onMounted(() => {
    fileStore.getFilesByPath({file_path_id: [3]})
    categoryStore.getCategories()
    postStore.getPostById(route.params.id)
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

function updatePost() {
    postStore.updatePost()
}

function toggleConfirmDelete() {
    confirmDelete.value = !confirmDelete.value
}

async function deletePost(id) {
    await toggleConfirmDelete()
    const response = await postStore.deletePostById(postStore.dataStore.id)
    await deletePostStatus(response)
}

function deletePostStatus(response) {
    if (response.status == "success")
        router.push({ name: 'postIndex' })
}


const urlImage = computed(() => {
    return (postStore.thumbnail) ? `${import.meta.env.VITE_API_FILE}${postStore.thumbnail}` : ''
})

</script>

<template>
    <div class="post-insert">
       <div class="container-fluid">
           <div class="row">
               <div class="col-12 d-flex justify-content-between">
                   <v-read-only-field size="small" class="mb-2" label="Criado" :content="postStore.post.data?.created_at" />
                   <v-read-only-field size="small" class="mb-2" label="Atualizado" :content="postStore.post.data?.updated_at" />
               </div>
               <div class="col-12">
                   <div class="thumbnail-container d-flex justify-content-center align-items-center mb-3">
                       <v-image :url="urlImage" size="cover" :descriptive="(!postStore.thumbnail) ? 'Atualizar Thumbnail': ''" @action="toggleThumbnail" v-if="postStore.dataStore.id" />
                       <v-pop-up title="File" v-if="_file && postStore.dataStore.id" @close="toggleThumbnail">
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
                   <v-input btn-class="mb-3" v-model.trim="postStore.dataStore.title" :max="200" label="Titulo" placeholder="digite o título do post." />
                   <v-text-area btn-class="mb-3" v-model.trim="postStore.dataStore.summary" :max="200" label="Resumo" placeholder="digite o resumo do post." />
                   <v-text-editor btn-class="mb-3" v-model.trim="postStore.dataStore.content" label="Conteúdo Principal" />
               </div>
           </div>
           <div class="row">
               <div class="col-6 d-flex align-items-center justify-content-start">
                   <v-checkbox class="me-2" label="Publicar" inputValue="item" v-model="postStore.dataStore.published" />
                   <v-button-link theme="b-outline-blue" btn-class="me-2" :disabled="!postStore.dataStore.published" :target="targetPage">Visualizar Página</v-button-link>
                   <v-button theme="b-outline-blue" btn-class="me-2" @click="toggleCat">Categorias</v-button>
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
               </div>
               <div class="col-6 d-flex align-items-center justify-content-end">

                   <v-button btnClass="me-2" theme="b-grey" @action="toggleConfirmDelete" :disabled="!postStore.dataStore.id">Excluir</v-button>
                   <v-button btnClass="me-2" @action="updatePost" :disabled="!postStore.dataStore.id">Salvar</v-button>
                   <v-confirm v-if="confirmDelete" @accepted="deletePost(postStore.dataStore.id)" @rejected="toggleConfirmDelete"
                              title="Ao confirmar está ação, o post será excluído."
                              message="Tem certeza que deseja excluir? Lembre-se que está ação não poderá ser desfeita."/>
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
        user-select: none;

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


