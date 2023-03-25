import axios from 'axios'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_CMS
});

const errorResponse = {
    status: 'error',
    message: ["Falha de Transmissão."],
    data: null,
}

async function apiGet(url, param, token) {
    let responseAPI = ''
    api.defaults.headers.common['Authorization'] = await `Bearer ${token ?? ''}`;
    await api.get(url, { params: param })
        .then(function (response) {
            responseAPI = {
                status: response.data.status,
                message: response.data.message,
                data: response.data.data ?? null
            }
            if (response.data.paginate)
                responseAPI['paginate'] = response.data.paginate

        })
        .catch(function (error) {
            responseAPI = {
                status: error?.response?.status == 401 ? error?.response?.data.status : 'error',
                message: error?.response?.data?.message ?? [error.message],
                data: null
            }
        });

    return responseAPI;
}

async function apiPost(url, param, token) {
    let responseAPI = ''
    api.defaults.headers.common['Authorization'] = await `Bearer ${token ?? ''}`;
    await api.post(url, param)
        .then(function (response) {
            responseAPI = {
                status: response.data.status,
                message: response.data.message,
                data: response.data.data ?? null
            }
        })
        .catch(function (error) {
            responseAPI = {
                status: error?.response?.status == 401 ? error?.response?.data.status : 'error',
                message: error?.response?.data?.message ?? [error.message],
                data: null
            }
        });

    return responseAPI;
}


async function apiPut(url, param, token) {
    let responseAPI = ''
    api.defaults.headers.common['Authorization'] = await `Bearer ${token ?? ''}`;
    await api.put(url, param)
        .then(function (response) {
            responseAPI = {
                status: response.data.status,
                message: response.data.message,
                data: response.data.data ?? null
            }
        })
        .catch(function (error) {
            responseAPI = {
                status: error?.response?.status == 401 ? error?.response?.data.status : 'error',
                message: error?.response?.data?.message ?? [error.message],
                data: null
            }
        });

    return responseAPI;
}

async function apiPostFormData(url, formData, token) {
    let responseAPI = ''
    api.defaults.headers.common['Authorization'] = await `Bearer ${token ?? ''}`;
    api.defaults.headers.post['Content-Type'] = await 'multipart/form-data';
    await api.post(url, formData)
        .then(function (response) {
            responseAPI = {
                status: response.data.status,
                message: response.data.message,
                data: response.data.data ?? null
            }
        })
        .catch(function (error) {
            responseAPI = {
                status: error?.response?.status == 401 ? error?.response?.data.status : 'error',
                message: error?.response?.data?.message ?? [error.message],
                data: null
            }
        });

    return responseAPI;
}

async function apiPutFormData(url, formData, token) {
    let responseAPI = ''
    api.defaults.headers.common['Authorization'] = await `Bearer ${token ?? ''}`;
    api.defaults.headers.post['Content-Type'] = await 'multipart/form-data';
    await api.post(url, formData)
        .then(function (response) {
            responseAPI = {
                status: response.data.status,
                message: response.data.message,
                data: response.data.data ?? null
            }
        })
        .catch(function (error) {
            responseAPI = {
                status: error?.response?.status == 401 ? error?.response?.data.status : 'error',
                message: error?.response?.data?.message ?? [error.message],
                data: null
            }
        });

    return responseAPI;
}

async function apiDelete(url, param, token) {
    let responseAPI = ''
    api.defaults.headers.common['Authorization'] = await `Bearer ${token ?? ''}`;
    await api.delete(url, param)
        .then(function (response) {
            responseAPI = {
                status: response.data.status,
                message: response.data.message,
                data: response.data.data ?? null
            }
        })
        .catch(function (error) {
            responseAPI = {
                status: error?.response?.status == 401 ? error?.response?.data.status : 'error',
                message: error?.response?.data?.message ?? [error.message],
                data: null
            }
        });

    return responseAPI;
}

export { apiGet, apiPost, apiPostFormData, apiDelete, apiPut }