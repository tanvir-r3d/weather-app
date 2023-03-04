import api from "@/network/api";

export const usersApi = () => {
    return api.get('/users');
}

export const weathersApi = (limit: any, offset: any) => {
    return api.get('/weathers', {
        params: {limit, offset}
    });
}