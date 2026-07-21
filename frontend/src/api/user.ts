import axios from "axios";

export const api = axios.create({
    baseURL: "http://192.168.0.147/api",
});

const token = localStorage.getItem("token");

if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
}

export const getUsers = () => {
    return api.get("/users");
};

export const getLogOuts=()=>{
    return api.post('/logout')
}