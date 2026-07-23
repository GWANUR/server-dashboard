import {
  Cpu,
  MemoryStick,
  HardDrive,
  Network,
  CircleCheck,
  Play,
  Power,
  RotateCcw
} from "lucide-react";

import {
    LineChart,
    Line,
    XAxis,
    YAxis,
    Tooltip,
    CartesianGrid,
    ResponsiveContainer,
    Pie,
    PieChart,
    Cell,
} from "recharts";
import { useEffect, useMemo, useState } from "react";
import { fetchSystemStats } from "../api/agent";

const COLORS = ["#8b5cf6", "#06b6d4", "#22c55e"];

type Stats = {
    cpu?: number;
    ram?: {
        total: number;
        used: number;
        percent: number;
    };
    disk?: {
        total: number;
        used: number;
        percent: number;
    };
    network?: {
        received_bytes: number;
    };
    load?: number[];
    uptime?: number;
};

export default function Dashboard() {
    const [stats, setStats] = useState<Stats>({});
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const load = async () => {
            try {
                const data = await fetchSystemStats();
                setStats(data);
            } catch (error) {
                console.error(error);
            } finally {
                setLoading(false);
            }
        };
        load();
        const interval = window.setInterval(load, 5000);
        return () => window.clearInterval(interval);
    }, []);

    const cpuData = useMemo(
        () => [{ time: "now", cpu: stats.cpu ?? 0 }],
        [stats.cpu]
    );

    const ramData = useMemo(() => [
        { name: "Used", value: stats.ram?.used ?? 0 },
        { name: "Free", value: Math.max(100 - (stats.ram?.percent ?? 0), 0) },
    ], [stats.ram?.percent]);

    return (
        <section id="dashboard" className="page">
            <header>
                <h2>Welcome back, Alex!</h2>
                <div className="rightheader">
                    <div className="server_controll">
                        <div className="btn_icon"><Play size={18} color="#fff" /></div>
                        <div className="btn_icon"><Power size={18} color="#fff" /></div>
                        <div className="btn_icon"><RotateCcw size={18} color="#fff" /></div>
                    </div>
                    <div className="server_active">
                        <span className="label">Server:</span>
                        <span className="value">Online<CircleCheck size={18} color="green" /></span>
                    </div>
                </div>
            </header>
            <div className="systemstat">
                <h2>Server Status</h2>
                <div className="all_system">
                    <div className="cpu_stat">
                        <Cpu size={60} className="icon" />
                        <span className="label">CPU Usage:</span>
                        <span className="value">{loading ? "—" : `${stats.cpu ?? 0}%`}</span>
                    </div>
                    <div className="ram_stat">
                        <MemoryStick size={60} className="icon" />
                        <span className="label">RAM Usage:</span>
                        <span className="value">{loading ? "—" : `${stats.ram?.percent ?? 0}%`}</span>
                    </div>
                    <div className="disk_stat">
                        <HardDrive size={60} className="icon" />
                        <span className="label">Disk Usage:</span>
                        <span className="value">{loading ? "—" : `${stats.disk?.used ?? 0}/${stats.disk?.total ?? 0} GB`}</span>
                    </div>
                    <div className="network_stat">
                        <Network size={60} className="icon" />
                        <span className="label">Network speed:</span>
                        <span className="value">{loading ? "—" : `${stats.network?.received_bytes ?? 0} bytes`}</span>
                    </div>
                </div>
                <hr />
            </div>
            <div className="services_stats">
                <h2>Services Status</h2>
                <div className="all_services">
                    <div className="running">
                        <span className="label">Load avg:</span>
                        <span className="value">{loading ? "—" : `${stats.load?.[0] ?? 0}`}</span>
                    </div>
                    <div className="active_containers">
                        <span className="label">Uptime:</span>
                        <span className="value">{loading ? "—" : stats.uptime ?? 0}</span>
                    </div>
                </div>
                <hr />
            </div>
            <div className="graph">
                <h2>Server Performance</h2>
                <div className="all_graph">
                    <div className="cpugraph">
                        <span className="label">CPU Usage</span>
                        <ResponsiveContainer width="100%" height={300}>
                            <LineChart data={cpuData}>
                                <CartesianGrid strokeDasharray="3 3" />
                                <XAxis dataKey="time" />
                                <YAxis />
                                <Tooltip />
                                <Line type="monotone" dataKey="cpu" stroke="#7c3aed" strokeWidth={3} />
                            </LineChart>
                        </ResponsiveContainer>
                    </div>
                    <div className="ramgraph">
                        <span className="label">RAM Usage</span>
                        <ResponsiveContainer width="100%" height={300}>
                            <PieChart>
                                <Pie data={ramData} dataKey="value" nameKey="name" outerRadius={100}>
                                    {ramData.map((_, index) => (<Cell key={index} fill={COLORS[index]} />))}
                                </Pie>
                                <Tooltip />
                            </PieChart>
                        </ResponsiveContainer>
                    </div>
                </div>
            </div>
        </section>
    );
}