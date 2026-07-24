import { api } from "../api/api";

export const getSettings = () => {
    return api.get(`/dashboard/settings`);
};