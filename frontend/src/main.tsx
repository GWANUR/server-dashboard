import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import { RouterProvider } from "react-router-dom";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";

import { AuthProvider } from "./context/AuthContext";
import { router } from "./router";

const prefersDark = window.matchMedia("(prefers-color-scheme: dark)").matches;

const queryClient = new QueryClient();

const root = document.getElementById("root")!;
root.dataset.theme = prefersDark ? "dark" : "light";

createRoot(root).render(
    <StrictMode>
        <QueryClientProvider client={queryClient}>
            <AuthProvider>
                <RouterProvider router={router} />
            </AuthProvider>
        </QueryClientProvider>
    </StrictMode>
);