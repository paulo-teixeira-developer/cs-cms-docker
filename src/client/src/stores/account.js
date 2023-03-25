import { reactive, computed } from 'vue'
import { defineStore } from 'pinia'
import { apiGet, apiPostFormData, apiPut } from '@/services/cs-cms-api.js'
/** stores **/
import { useProcessStore } from '@/stores/process.js'
import { useAuthStore } from '@/stores/auth.js'

export const useAccountStore = defineStore('account', () => {

    /** default import **/
    const processStore = useProcessStore();
    const authStore = useAuthStore();
    const accountNav = [
        { name: 'Informações básicas', route: 'accountBasicInfo' },
        { name: 'Foto do perfil', route: 'accountProfilePicture' },
        { name: 'Credenciais', route: 'accountCredential' },
    ]

    /**  states  **/
    const accountState = reactive({
        id: null,
        email: null,
        password: null,
        person: {
            name: null,
            last_name: null,
            birth: null,
            profession: null,
            biography: null,
            file: {
                id: null,
                name: null,
                hash: null,
                size: null,
                file_format: {
                    name: null
                }
            }
        }
    })

    function setAccount(response = {}) {
        accountState.id = response.data.id ?? null
        accountState.email = response.data.email ?? null
        accountState.person.name = response.data.person.name ?? null
        accountState.person.last_name = response.data.person.last_name ?? null
        accountState.person.birth = response.data.person.birth ?? null
        accountState.person.profession = response.data.person.profession ?? null
        accountState.person.biography = response.data.person.biography ?? null
        accountState.person.file.id = response.data.person.file?.id ?? null
        accountState.person.file.name = response.data.person.file?.name ?? null
        accountState.person.file.hash = response.data.person.file?.hash ?? null
        accountState.person.file.size = response.data.person.file?.size ?? null
        accountState.person.file.file_format.name = response.data.person.file?.file_format.name ?? null
    }

    const accountImg = computed(() => {
        return (accountState.person.file.id) ? `${import.meta.env.VITE_API_CMS}files/streaming/user-img/${accountState.person.file.hash}.${accountState.person.file.file_format.name}` : null
    })

    /** todas os arquivos **/
    async function getAccount() {
        await processStore.setProcessing()
        const response = await apiGet('/users/show/' + authStore.auth.user.id, null, authStore.auth.token)
        if (response.status == "success")
            await setAccount(response)
        await processStore.setProcessed(response)
    }

    /** enviar arquivo **/
    async function uploadFile(formData) {
        await processStore.setProcessing()
        const response = await apiPostFormData('/users/update-img/' + authStore.auth.user.id, formData, authStore.auth.token)
        if (response.status == "success")
            await getAccount()
        await processStore.setProcessed(response)
    }

    /** update **/
    async function updateAccount() {
        await processStore.setProcessing()
        const response = await apiPut('/users/update/' + authStore.auth.user.id, accountState, authStore.auth.token)
        await processStore.setProcessed(response)
    }

    /** update credential **/
    async function updateCredential() {
        await processStore.setProcessing()
        const response = await apiPut('/users/update-credential/' + authStore.auth.user.id, { email: accountState.email, password: accountState.password }, authStore.auth.token)
        await processStore.setProcessed(response)
    }

    return { accountState, accountImg, accountNav, getAccount, uploadFile, updateAccount, updateCredential }
})