import { api } from "../api/api";export const getUsers = () => {
    return api.get("/users");
};

export const getLogOuts=()=>{
    return api.post('/logout')
}