import { CornerDownLeft } from "lucide-react";
import { useState, useRef } from "react";
import { Terminal } from "xterm";
import { FitAddon } from "xterm-addon-fit";


export default function Terminal_page() {
    
    return (
        <section id="terminal" className="pages">
            <div className="terminal_window">
                <pre></pre>
                <div className="input_icon">
                    <input
                        type="text"
                        value=""
                        placeholder="Enter command..."
                    />
                    <button className="btn" onClick={() => ("ss")}>
                        <CornerDownLeft size={20} color="#fff" />
                    </button>
                </div>
            </div>
        </section>
    );
}