import ReactDOM from "react-dom/client";
import { RouterProvider } from "react-router-dom";
import { AuthProvider } from "./context/AuthContext";
import "./index.scss";
import { router } from "./router";

const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

const root = document.getElementById("root")!;

root.dataset.theme = prefersDark ? "dark" : "light";

ReactDOM.createRoot(root).render(
    <AuthProvider>
        <RouterProvider router={router} />
    </AuthProvider>
);