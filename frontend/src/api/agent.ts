import { api } from "../api/api";

export async function agent() {
    console.log("GET /agents");
    const response = await api.get("/agents");
    return response.data;
}