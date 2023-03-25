import { reactive, ref } from 'vue'
import { defineStore } from 'pinia'
import { apiGet, apiPostFormData, apiDelete } from '@/services/cs-cms-api.js'
/** stores **/
import { useProcessStore } from '@/stores/process.js'
import { useAuthStore } from '@/stores/auth.js'

export const useFileStore = defineStore('file', () => {

    /** default import **/
    const processStore = useProcessStore();
    const authStore = useAuthStore();

    /**  states  **/
    const files = reactive({ data: [], paginate: {} })
    const file = reactive({ data: [] })
    const deleteStatus = ref(null)

    /**  set e reset  **/
    function setFiles(response) {
        if (response.data) {
            files.data = response.data
            files.paginate = response.paginate
        } else {
            files.data = []
            files.paginate = ''
        }
    }

    function setFile(response = {}) {
        if (response.data)
            file.data = response.data
        else
            file.data = []
    }

    /** todas os arquivos **/
    async function getFiles(param = {}) {
        await processStore.setProcessing()
        const response = await apiGet('/files/listing', param, authStore.auth.token)
        await setFiles(response)
        await processStore.setProcessed(response)
    }

    /** todas os arquivos por path **/
    async function getFilesByPath(param = {}) {
        await processStore.setProcessing()
        const response = await apiGet('/files/listing-by-path', param, authStore.auth.token)
        await setFiles(response)
        await processStore.setProcessed(response)
    }

    /** arquivos por id **/
    async function getFileById(id = null) {
        await processStore.setProcessing()
        const response = await apiGet('/files/show/' + id, null, authStore.auth.token)
        await setFile(response)
        await processStore.setProcessed(response)
    }

    /** enviar arquivo **/
    async function uploadFile(formData) {
        await processStore.setProcessing()
        const response = await apiPostFormData('/files/store/', formData, authStore.auth.token)
        await processStore.setProcessed(response)
    }

    /** deletando arquivo **/
    async function deleteFileById(id = null) {
        await processStore.setProcessing()
        const response = await apiDelete('/files/delete/' + id, null, authStore.auth.token)
        if (response.status == "success")
            await setFile()
        await processStore.setProcessed(response)
        deleteStatus.value = await response.status
    }

    return { files, file, deleteStatus, getFiles, getFilesByPath, getFileById, uploadFile, deleteFileById }
})