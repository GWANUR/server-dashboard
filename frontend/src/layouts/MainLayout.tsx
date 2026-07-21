import { Outlet, Link, useNavigate } from "react-router-dom";
import { api } from "../api/user";
import { useState } from "react";
import {
  LayoutDashboard,
  Server,
  Logs,
  Terminal,
  Users,
  LogOut,
} from "lucide-react";


export default function MainLayout() {

    const navigate = useNavigate();
    const [error,setError]=useState("")

    async function handleLogout(e: React.FormEvent) {
        e.preventDefault();
        setError("")

        try{
            await api.post("/logout")
            navigate("/login");
        } catch (err){
            setError((err as Error).message);
            console.log(error);
        }
    }


    return (
        <div className="main-layout">
            <div className="sidebar">
                <h2>Dashboard <br/><a href="https://github.com/GWANUR" target="_blank" rel="noopener noreferrer">By StackAlex</a></h2>
                <nav>
                    <ul>
                        <li>
                            <Link  to="/">
                                <LayoutDashboard />
                                Dashboard
                            </Link >
                        </li>
                        <li>
                            <Link to="/servers">
                                <Server size={18} />
                                Servers
                            </Link>
                        </li>
                        <li>
                            <Link to="/log">
                                <Logs size={18} />
                                Log
                            </Link>
                        </li>
                        <li>
                            <Link to="/terminal">
                                <Terminal size={18} />
                                Terminal
                            </Link>
                        </li>
                        <li>
                            <Link to="/users">
                                <Users size={18} />
                                Users
                            </Link>
                        </li>
                    </ul>
                <div className="logout btn_icon"
                    onClick={handleLogout}
                    >
                    <LogOut size={18}/>
                    Logout
                </div>
                </nav>
            </div>
                
                <main>
                    <Outlet />
                </main>
        </div>
    );
}