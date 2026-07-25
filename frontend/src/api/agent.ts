import { api } from "../api/api";

export async function agent() {
    const response = await api.get("/agents");
    return response.data;
}