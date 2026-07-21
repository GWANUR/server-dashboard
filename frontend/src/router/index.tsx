import { createBrowserRouter } from "react-router-dom";

import MainLayout from "../layouts/MainLayout";
import Dashboard from "../pages/Dashboard";
import Servers from "../pages/Servers";
import Login from "../pages/Login";
import NotFound from "../pages/NotFound";
import Terminal from "../pages/Terminal";
import Log from "../pages/Log";
import Users_page from "../pages/UsersPage"; 

export const router = createBrowserRouter([
    {
        element: <MainLayout />,
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
                element: <Terminal />,
            },
            {
                path: "/log",
                element: <Log />,
            },
            {
                path: "/users",
                element: <Users_page />,
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