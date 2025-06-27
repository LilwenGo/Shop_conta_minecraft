import axios, { type AxiosResponse } from "axios";

function onResponseSucess(res: any) {
    if (!res.data.success) {
        return {
            code: res.status,
            error: res.data.error
        };
    }
    return res.data;
}

function onResponseFailure(error: any) {
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
}

type SuccessHandler = (value: AxiosResponse<any, any>) => AxiosResponse<any, any> | PromiseLike<AxiosResponse<any, any>>;

type FailureHandler = (reason: any) => PromiseLike<never>;

async function get(url: string, successHandler?: SuccessHandler, failureHandler?: FailureHandler) {
    return await axios.get(url).then(successHandler ?? onResponseSucess).catch(failureHandler ?? onResponseFailure);
}

async function post(url: string, data: object, successHandler?: SuccessHandler, failureHandler?: FailureHandler) {
    return await axios.post(url, data).then(successHandler ?? onResponseSucess).catch(failureHandler ?? onResponseFailure);
}

async function put(url: string, data: object, successHandler?: SuccessHandler, failureHandler?: FailureHandler) {
    return await axios.put(url, data).then(successHandler ?? onResponseSucess).catch(failureHandler ?? onResponseFailure);
}

async function patch(url: string, data: object, successHandler?: SuccessHandler, failureHandler?: FailureHandler) {
    return await axios.patch(url, data).then(successHandler ?? onResponseSucess).catch(failureHandler ?? onResponseFailure);
}

async function del(url: string, successHandler?: SuccessHandler, failureHandler?: FailureHandler) {
    return await axios.delete(url).then(successHandler ?? onResponseSucess).catch(failureHandler ?? onResponseFailure);
}

export const api = {
    get,
    post,
    put,
    patch,
    del
}