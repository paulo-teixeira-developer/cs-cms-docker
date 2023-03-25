import { defineStore } from 'pinia'
import { reactive, computed } from 'vue'

export const useAlertStore = defineStore('alert', () => {

    const data = reactive({ status: '', data: [] })
    const active = computed(() => (data.data.length > 0) ? true : false)

    function setAlert(param = {}) {
        data.status = param?.status ?? ''
        data.data = param?.data ?? []
    }

    return { data, active, setAlert }
})