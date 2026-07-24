import { createBrowserRouter } from "react-router-dom";

import MainLayout from "../layouts/MainLayout";
import Dashboard from "../pages/Dashboard";
import Servers from "../pages/Servers";
import Login from "../pages/Login";
import NotFound from "../pages/NotFound";
import Terminal_page from "../pages/Terminal";
import Settings from "../pages/Settings";
import Log from "../pages/Log";
import Users_page from "../pages/UsersPage"; 
import ProtectedRoute from "./ProtectedRoute";

export const router = createBrowserRouter([
    {
        element: 
        <ProtectedRoute>
            <MainLayout />
        </ProtectedRoute>,
        children: [
            {
                path: "/",
                element: <Dashboard />,
            },
            {
                path: "/servers",
                element: <Servers />,
            },
            {
                path: "/terminal",
                element: <Terminal_page />,
            },
            {
                path: "/log",
                element: <Log />,
            },
            {
                path: "/users",
                element: <Users_page />,
            },
            {
                path: "/settings",
                element: <Settings />,
            },
        ],
    },
    {
        path: "/login",
        element: <Login />,
    },
    {
        path: "*",
        element: <NotFound />,
    },
]);