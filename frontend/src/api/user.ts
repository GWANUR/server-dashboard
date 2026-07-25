import { api } from "../api/api";

export const getUsers = () => {
    return api.get("/users");
};

export const thisUser = async () => {
    const { data } = await api.get("/api/user");
    return data;
}

export const getLogOuts=()=>{
    return api.post('/logout')
}