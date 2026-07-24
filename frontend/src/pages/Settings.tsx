import { useState } from "react";
import { api } from "../api/api";

export default function Settings() {
    const [settingsInfo, setSettingsInfo] = useState("");
    // function getSettings(){
    //     try{
    //         api
    //     }
    // }
    return (
        <>
            <section id="settings" className="page">
                <div className="window_all_settings">
                    <div className="settings_group">
                        <form action="" className="input_icon">
                            <span className="label">Agent token:</span>
                            <input type="password" name="agent_token" value={settingsInfo}></input>
                        </form>
                    </div>
                </div>
            </section>
        </>
    );
}