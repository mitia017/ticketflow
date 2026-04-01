import axios from "axios";

const instance = axios.create({
    baseURL: "/api",
    headers: { "Content-Type": "application/json", Accept: "application/json" },
});

instance.interceptors.request.use((config) => {
    const token = localStorage.getItem("token");
    if (token) config.headers.Authorization = `Bearer ${token}`;
    return config;
});

instance.interceptors.response.use(
    (response) => response,
    (error) => {
        // Ne pas supprimer le token pour des erreurs 5xx ou réseau temporaires
        if (error.response?.status === 401) {
            localStorage.removeItem("token");
            window.location.href = "/login";
        }
        return Promise.reject(error);
    },
);

export default instance;
