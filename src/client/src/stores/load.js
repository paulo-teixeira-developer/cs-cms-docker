import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLoadStore = defineStore('load', () => {

    const active = ref(false)

    function setLoad(param = false) {
        active.value = param
    }

    return { active, setLoad }
})