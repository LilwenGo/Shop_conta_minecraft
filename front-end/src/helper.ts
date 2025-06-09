import axios from "axios";

async function get(url: string) {
    return await axios.get(url).then(res => {
        if (!res.data.success) {
            return {
                code: res.status,
                error: res.data.error
            };
        }
        return res;
    }).catch(error => {
        if (error.response) {
            return {
                code: error.response.status,
                error: error.response.data?.error || 'Erreur inconnue',
                raw: error.response.data
            };
        }
        return {
            code: 0,
            error: error.message || 'Erreur réseau inconnue'
        };
    });
}

async function post(url: string, data: object) {
    return await axios.post(url, data).then(res => {
        if (!res.data.success) {
            return {
                code: res.status,
                error: res.data.error
            };
        }
        return res;
    }).catch(error => {
        if (error.response) {
            return {
                code: error.response.status,
                error: error.response.data?.error || 'Erreur inconnue',
                raw: error.response.data
            };
        }
        return {
            code: 0,
            error: error.message || 'Erreur réseau inconnue'
        };
    });
}

async function put(url: string, data: object) {
    return await axios.put(url, data).then(res => {
        if (!res.data.success) {
            return {
                code: res.status,
                error: res.data.error
            };
        }
        return res;
    }).catch(error => {
        if (error.response) {
            return {
                code: error.response.status,
                error: error.response.data?.error || 'Erreur inconnue',
                raw: error.response.data
            };
        }
        return {
            code: 0,
            error: error.message || 'Erreur réseau inconnue'
        };
    });
}

async function patch(url: string, data: object) {
    return await axios.patch(url, data).then(res => {
        if (!res.data.success) {
            return {
                code: res.status,
                error: res.data.error
            };
        }
        return res;
    }).catch(error => {
        if (error.response) {
            return {
                code: error.response.status,
                error: error.response.data?.error || 'Erreur inconnue',
                raw: error.response.data
            };
        }
        return {
            code: 0,
            error: error.message || 'Erreur réseau inconnue'
        };
    });
}

async function del(url: string) {
    return await axios.delete(url).then(res => {
        if (!res.data.success) {
            return {
                code: res.status,
                error: res.data.error
            };
        }
        return res;
    }).catch(error => {
        if (error.response) {
            return {
                code: error.response.status,
                error: error.response.data?.error || 'Erreur inconnue',
                raw: error.response.data
            };
        }

        return {
            code: 0,
            error: error.message || 'Erreur réseau inconnue'
        };
    });
}

export const api = {
    get,
    post,
    put,
    patch,
    del
}