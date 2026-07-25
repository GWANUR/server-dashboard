import { api } from "../api/api";

export interface Agent {
    id: number;
    user_id: number;
    agent_id: string;
    name: string;
    token: string;
    enabled: boolean;
    created_at: string;
    updated_at: string;
}

export async function agent() {
    const response = await api.get("/agents");
    return response.data;
}

export async function allAgent() {
    const { data } = await api.get("/allAgents");
    return data;
}