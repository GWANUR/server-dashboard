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

import {  } from 'recharts';

const cpuData = [
    { time: "12:00", cpu: 15 },
    { time: "12:05", cpu: 23 },
    { time: "12:10", cpu: 18 },
    { time: "12:15", cpu: 42 },
    { time: "12:20", cpu: 37 },
];
const ramData = [
    { name: "Used", value: 6 },
    { name: "Docker", value: 5 },
    { name: "Free", value: 10 },
]

const COLORS = [
    "#8b5cf6",
    "#06b6d4",
    "#22c55e",
];


export default function Dashboard() {
    return (
        <>
            <section id="dashboard" className="page">
                <header>
                    <h2>Welcome back, Alex!</h2> 
                    <div className="rightheader">
                        <div className="server_controll">
                            <div className="btn_icon">
                                <Play size={18} color="#fff"/>
                            </div>
                            <div className="btn_icon">
                                <Power size={18} color="#fff"/>
                            </div>
                            <div className="btn_icon">
                                <RotateCcw size={18} color="#fff"/>
                            </div>
                        </div>
                        <div className="server_active">
                            <span className="label">Server:</span>
                            <span className="value">Online<CircleCheck size={18} color="green"/></span>
                        </div>
                    </div>
                </header>  
                {/* System Status */}
                <div className="systemstat">
                    <h2>Server Status</h2>
                    <div className="all_system">
                        <div className="cpu_stat">
                            <Cpu size={60} className="icon"/>
                            <span className="label">CPU Usage:</span>
                            <span className="value">45%</span>
                        </div>
                        <div className="ram_stat">
                            <MemoryStick size={60} className="icon"/>
                            <span className="label">RAM Usage:</span>
                            <span className="value">63%</span>
                        </div>
                        <div className="disk_stat">
                            <HardDrive  size={60} className="icon"/>
                            <span className="label">Disk Usage:</span>
                            <span className="value">512/1000 GB</span>
                        </div>
                        <div className="network_stat">
                            <Network size={60} className="icon"/>
                            <span className="label">Network speed:</span>
                            <span className="value">156 Mbps</span>
                        </div>
                    </div>
                    <hr/>
                </div>
                {/* Services Status */}
                <div className="services_stats">
                    <h2>Services Status</h2>
                    <div className="all_services">
                        <div className="running">
                            <span className="label">Running services:</span>
                            <span className="value">12</span>
                        </div>
                        <div className="active_containers">
                            <span className="label">Active containers:</span>
                            <span className="value">8</span>
                        </div>
                        <div className="active_users">
                            <span className="label">Active users:</span>
                            <span className="value">15</span>
                        </div>
                        <div className="uptime">
                            <span className="label">Uptime:</span>
                            <span className="value">2 days</span>
                        </div>
                    </div>
                    <hr/>
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

                                    <Line
                                        type="monotone"
                                        dataKey="cpu"
                                        stroke="#7c3aed"
                                        strokeWidth={3}
                                    />
                                </LineChart>
                            </ResponsiveContainer>
                        </div>
                        <div className="ramgraph">
                            <span className="label">RAM Usage</span>
                            <ResponsiveContainer width="100%" height={300}>
                                <PieChart>
                                    <Pie
                                        data={ramData}
                                        dataKey="value"
                                        nameKey="name"
                                        outerRadius={100}
                                    >
                                        {ramData.map((_, index) => (
                                            <Cell
                                                key={index}
                                                fill={COLORS[index]}
                                            />
                                        ))}
                                    </Pie>

                                    <Tooltip />
                                </PieChart>
                            </ResponsiveContainer>
                        </div>
                    </div>
                </div>
            </section>
        </>
    );
}