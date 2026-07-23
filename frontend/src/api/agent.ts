import axios from "axios";

const api = axios.create({
  baseURL: "/api",
  withCredentials: true,
});

export async function fetchSystemStats() {
  const token = localStorage.getItem("token");
  if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
  }
  const { data } = await api.get("/system");
  return data;
}

export async function sendTerminalCommand(command: string) {
  const token = localStorage.getItem("token");
  if (token) {
    api.defaults.headers.common.Authorization = `Bearer ${token}`;
  }
  const { data } = await api.post("/terminal", { command });
  return data;
}
