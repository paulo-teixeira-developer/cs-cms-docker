import { reactive, computed } from 'vue'
import { defineStore } from 'pinia'
import { apiPost, apiPut } from '@/services/cs-cms-api.js'
/** stores **/
import { useProcessStore } from '@/stores/process.js'

/** composable **/
import { abbrName } from '@/composables/helper.js'

export const useAuthStore = defineStore('auth', () => {

    /** default import **/
    const processStore = useProcessStore();

    /**  states  **/
    const auth = reactive({
        isLogged: false,
        token: null,
        user: {
            id: null,
            name: null,
            lastName: null,
            abbrName: null,
            email: null,
            imgAccount: null,
        }
    })

    /**  set e reset  **/
    async function setAuth() {
        const user = await localStorage.getItem('cscmsapp');
        if (user) {
            const userAux = JSON.parse(user)
            auth.isLogged = true;
            auth.user.id = userAux.user.id;
            auth.user.name = userAux.user.name;
            auth.user.lastName = userAux.user.last_name;
            auth.user.abbrName = (userAux.user.name && userAux.user.last_name) ? abbrName(`${userAux.user.name} ${userAux.user.last_name}`) : "?"
            auth.user.email = userAux.user.email;
            auth.user.imgAccount = userAux.user.img_account;
            auth.token = userAux.token;
        } else {
            auth.isLogged = false;
            auth.user.id = null;
            auth.user.name = null;
            auth.user.lastName = null;
            auth.user.abbrName = null;
            auth.user.email = null;
            auth.user.imgAccount = null;
            auth.token = null;
        }
    }

    const accountImg = computed(() => {
        return (auth.user.imgAccount) ? `${import.meta.env.VITE_API_CMS}files/streaming/user-img/${auth.user.imgAccount}` : 'null'
    })


    /** efetuando login **/
    async function setLogin(params) {
        await processStore.setProcessing()
        const response = await apiPost('/auth/login', params)
        if (response.status == "success")
            await localStorage.setItem('cscmsapp', JSON.stringify(response.data))
        await setAuth()
        await processStore.setProcessed(response)
    }

    /** efetuando logout **/
    async function setLogout() {
        await processStore.setProcessing()
        const response = await apiPost('/auth/logout', '', auth.token)
        await localStorage.clear();
        await setAuth()
        await processStore.setProcessed(response)
    }

    return { auth, accountImg, setAuth, setLogin, setLogout }
})