import {
    CornerDownLeft
} from "lucide-react";

const log = [
    {Errors: 0, Warnings: 0, Info: 0, Debug: 0, Timestamp: "2023-06-01T12:00:00Z"},
    {Errors: 1, Warnings: 2, Info: 5, Debug: 10, Timestamp: "2023-06-01T12:05:00Z"},
    {Errors: 0, Warnings: 1, Info: 3, Debug: 8, Timestamp: "2023-06-01T12:10:00Z"},
    {Errors: 2, Warnings: 0, Info: 4, Debug: 6, Timestamp: "2023-06-01T12:15:00Z"},
    {Errors: 0, Warnings: 1, Info: 2, Debug: 5, Timestamp: "2023-06-01T12:20:00Z"},
]

export default function Terminal() {
    return (
        <>
            <section id="terminal" className="pages">
                <div className="terminal_window">
                    <pre>
                        {log.map((entry, index) => (
                            <div key={index}>
                                <strong>Timestamp:</strong> {entry.Timestamp}<br />
                                <strong>Errors:</strong> {entry.Errors}<br />
                                <strong>Warnings:</strong> {entry.Warnings}<br />
                                <strong>Info:</strong> {entry.Info}<br />
                                <strong>Debug:</strong> {entry.Debug}<br />
                            </div>
                        ))}
                    </pre>
                    <div className="input_icon">
                        <input type="text" placeholder="Enter command..." />
                        <button className="btn"><CornerDownLeft size={20} color="#fff"/></button> 
                    </div>
                </div>
            </section>
        </>
    );
}