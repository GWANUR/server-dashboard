import { CornerDownLeft } from "lucide-react";
import { useState } from "react";
import { sendTerminalCommand } from "../api/agent";

export default function Terminal() {
    const [command, setCommand] = useState("");
    const [output, setOutput] = useState("Waiting for command...");
    const [loading, setLoading] = useState(false);

    const runCommand = async () => {
        if (!command.trim()) return;
        setLoading(true);
        try {
            const data = await sendTerminalCommand(command);
            setOutput(data.output || "No output returned");
        } catch (error) {
            setOutput(`Error: ${error}`);
        } finally {
            setLoading(false);
        }
    };

    return (
        <section id="terminal" className="pages">
            <div className="terminal_window">
                <pre>{output}</pre>
                <div className="input_icon">
                    <input
                        type="text"
                        value={command}
                        onChange={(event) => setCommand(event.target.value)}
                        onKeyDown={(event) => {
                            if (event.key === "Enter") {
                                void runCommand();
                            }
                        }}
                        placeholder="Enter command..."
                    />
                    <button className="btn" onClick={() => void runCommand()} disabled={loading}>
                        <CornerDownLeft size={20} color="#fff" />
                    </button>
                </div>
            </div>
        </section>
    );
}