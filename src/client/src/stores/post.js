import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import { apiGet, apiPost, apiPut, apiDelete } from '@/services/cs-cms-api.js'
/** composable **/
import { validate } from '@/composables/validator.js'
/** stores **/
import { useProcessStore } from '@/stores/process.js'
import { useAuthStore } from '@/stores/auth.js'

export const usePostStore = defineStore('post', () => {

    /** default import **/
    const processStore = useProcessStore();
    const authStore = useAuthStore();

    /**  states  **/
    const dataStore = reactive({
        id: null,
        title: null,
        summary: null,
        content: null,
        published: false,
        category_id: null,
        file_id: null
    })
    const posts = reactive({ data: [], paginate: {} })
    const post = reactive({ data: {} })
    const thumbnail = ref(null)

    const rulesDataStore = {
        title: { rules: { required: true, min: 5, max: 200 } },
        summary: { rules: { required: true, min: 5, max: 200 } },
        content: { rules: { required: true, min: 5 } },
        published: { rules: { required: true } },
        category_id: { rules: { required: true }, label: 'categoria' },
        file_id: { rules: { required: true }, label: 'thumbnail' },
    };

    /**  set e reset  **/
    function setPosts(response) {
        posts.data = response?.data ?? []
        posts.paginate = response?.paginate ?? {}
    }

    function setPost(response = {}) {
        post.data = response.data ?? {};
    }

    function setDataStore(response = {}) {
        dataStore.id = response.data?.id ?? null
        dataStore.title = response.data?.title ?? null
        dataStore.summary = response.data?.summary ?? null
        dataStore.content = response.data?.content ?? null
        dataStore.published = (response.data?.published) ? true : false
        dataStore.category_id = response.data?.category?.id ?? null
        dataStore.file_id = response.data?.file.id ?? null
    }

    function setThumbnail(file = {}) {
        if (file.id) {
            dataStore.file_id = file.id
            thumbnail.value = `${file.file_path.name}/${file.hash}.${file.file_format.name}`
        }else{
            thumbnail.value = null
        }
    }

    function postCleaning(){
        setPost()
        setDataStore()
        setThumbnail()
    }

    /** todos os posts **/
    async function getPosts(param = {}) {
        await processStore.setProcessing()
        const response = await apiGet('/posts/listing', param, authStore.auth.token)
        await setPosts(response)
        await processStore.setProcessed(response)
    }

    /** post por id **/
    async function getPostById(id = null) {
        await processStore.setProcessing()
        const response = await apiGet('/posts/show/' + id, null, authStore.auth.token)
        await setPost(response)
        await setDataStore(response)
        await setThumbnail(response.data?.file ?? {})
        await processStore.setProcessed(response)
    }

    /** store **/
    async function storePost() {
        await processStore.setProcessing()
        const validation = await validate(dataStore, rulesDataStore)
        if (validation.fails.length) {
            await processStore.setProcessed({ data: null, message: validation.fails, status: "error" })
            return await {};
        } else {
            const response = await apiPost('/posts/store/', dataStore, authStore.auth.token)
            await setPost()
            await processStore.setProcessed(response)
            return await response;
        }
    }

    /** update **/
    async function updatePost() {
        await processStore.setProcessing()
        const validation = await validate(dataStore, rulesDataStore)
        if (validation.fails.length) {
            await processStore.setProcessed({ data: null, message: validation.fails, status: "error" })
        } else {
            const response = await apiPut('/posts/update/' + dataStore.id, dataStore, authStore.auth.token)
            await processStore.setProcessed(response)
            if(response.status == 'success')
                await setPost(response)
        }
    }

    /** deletando post **/
    async function deletePostById(id = null) {
        await processStore.setProcessing()
        const response = await apiDelete('/posts/delete/' + id, null, authStore.auth.token)
        if (response.status == "success") {
            await setPost()
            await setDataStore()
            thumbnail.value = await ''
        }
        await processStore.setProcessed(response)
        return await response;
    }

    return { posts, post, dataStore, thumbnail, getPosts, getPostById, setThumbnail, updatePost, deletePostById, storePost, postCleaning }
})