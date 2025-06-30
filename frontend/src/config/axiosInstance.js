import axios from 'axios';

const gatewayUrl = "http://localhost:8000";

// Create an Axios instance with default configurations
const instance = axios.create({
    baseURL: `${gatewayUrl}`,
    headers: {
        'Accept': 'application/json',
    },
    withCredentials: false,
});

// --- request interceptor – attach bearer token
instance.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// --- response interceptor – try silent refresh once when we hit 401
let isRefreshing = false;
let subscribers = [];

function onRefreshed(newToken) {
    subscribers.forEach((cb) => cb(newToken));
    subscribers = [];
}

function addSubscriber(cb) {
    subscribers.push(cb);
}

instance.interceptors.response.use(
    (r) => r,
    async (error) => {
        const original = error.config;
        if (error.response?.status === 401 && error.response?.data?.error_message === 'Invalid access token.' && !original._retry) {
            console.log("refresh");
            original._retry = true;
            if (!isRefreshing) {
                isRefreshing = true;
                try {
                    const refresh = localStorage.getItem('refresh');
                    const {data} = await axios.post(
                        `${gatewayUrl}/auth/refresh_token`,
                        {},
                        {headers: {'Refresh-Token': refresh}}
                    );
                    localStorage.setItem('token', data.token);
                    onRefreshed(data.access_token);
                    console.log(data);
                } catch (error) {
                    console.log(error);
                    localStorage.setItem("auth-user", null);
                    localStorage.setItem("token", null);
                    localStorage.setItem("refresh", null);
                } finally {
                    isRefreshing = false;
                }
            }

            return new Promise((resolve) => {
                addSubscriber((token) => {
                    original.headers.Authorization = `Bearer ${token}`;
                    resolve(axios(original));
                });
            });
        }
        return Promise.reject(error);
    }
);

export default instance;
