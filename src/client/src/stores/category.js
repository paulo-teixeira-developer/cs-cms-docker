import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import { apiGet, apiPost, apiDelete } from '@/services/cs-cms-api.js'
/** stores **/
import { useProcessStore } from '@/stores/process.js'
import { useAuthStore } from '@/stores/auth.js'

export const useCategoryStore = defineStore('category', () => {

    /** default import **/
    const processStore = useProcessStore();
    const authStore = useAuthStore();

    /**  states  **/
    const categories = reactive({ data: [], paginate: {} })

    /**  set e reset  **/
    function setCategories(response) {
        if (response.data) {
            categories.data = response.data
            categories.paginate = response.paginate
        } else {
            categories.data = []
            categories.paginate = ''
        }
    }

    /** todas os arquivos **/
    async function getCategories(param = {}) {
        await processStore.setProcessing()
        const response = await apiGet('/categories/listing', param, authStore.auth.token)
        await setCategories(response)
        await processStore.setProcessed(response)
    }

    return { categories, getCategories }
})