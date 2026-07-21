import { Outlet } from "react-router-dom";
import {
  LayoutDashboard,
  Server,
  Logs,
  Terminal,
  Users,
  LogOut,
} from "lucide-react";

export default function MainLayout() {
    return (
        <div className="main-layout">
            <div className="sidebar">
                <h2>Dashboard <br/><a href="https://github.com/GWANUR" target="_blank" rel="noopener noreferrer">By StackAlex</a></h2>
                <nav>
                    <ul>
                        <li>
                            <a href="/">
                                <LayoutDashboard />
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="/servers">
                                <Server size={18} />
                                Servers
                                </a>
                        </li>
                        <li>
                            <a href="/log">
                                <Logs size={18} />
                                Log
                            </a>
                        </li>
                        <li>
                            <a href="/terminal">
                                <Terminal size={18} />
                                Terminal
                            </a>
                        </li>
                        <li>
                            <a href="/users">
                                <Users size={18} />
                                Users
                            </a>
                        </li>
                    </ul>
                <div className="logout btn_icon">
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