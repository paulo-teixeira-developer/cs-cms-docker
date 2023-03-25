import { defineStore } from 'pinia'
import { useAlertStore } from '@/stores/alert.js'
import { useLoadStore } from '@/stores/load.js'

export const useProcessStore = defineStore('process', () => {

    const alertStore = useAlertStore();
    const loadStore = useLoadStore();

    function setProcessing() {
        loadStore.setLoad(true)
        alertStore.setAlert()
    }

    async function setProcessed(response) {
        await loadStore.setLoad();
        if (response.status)
            await alertStore.setAlert({ status: response.status, data: response.message })
        else
            await alertStore.setAlert()
    }

    return { setProcessing, setProcessed }
})