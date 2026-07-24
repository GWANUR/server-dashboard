import { CornerDownLeft } from "lucide-react";
import { useState, useRef } from "react";
import { sendTerminalCommand } from "../api/agent";
import { Terminal } from "xterm";
import { FitAddon } from "xterm-addon-fit";


export default function Terminal_page() {
    const [command, setCommand] = useState("");
    const [output, setOutput] = useState("Waiting for command...");
    const [loading, setLoading] = useState(false);
    const containerRef = useRef<HTMLDivElement>(null); 
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
    const terminal = new Terminal({
        cursorBlink: true,
        fontFamily: "JetBrains Mono",
        fontSize: 14,
    });

    const fitAddon = new FitAddon();

    terminal.loadAddon(fitAddon);
    terminal.open(containerRef.current!);

    fitAddon.fit();

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