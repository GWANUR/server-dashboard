import {
  SmilePlus,
  Pencil
} from "lucide-react";
import { useState, useEffect } from "react";
import { LoadPage } from "./LoadPage";
import { thisUser } from "../api/user"
import { allAgent } from "../api/agent"

export default function AgentPage(){
    const [user, setUser] = useState<any>();
    const [loading, setLoading] = useState<boolean>(false);

    useEffect(() =>{
        setLoading(true);
        const loadData= async ()=>{
            try {
                const [userData, tableAgents] = await Promise.all([
                    thisUser(),
                    allAgent()
                ]);

                console.log(userData);
                setUser(userData)
                console.log(tableAgents);

            } catch(error){
                console.error('Error loading data:', error);
            }
        }
        loadData();
    })
        return (
            <section id="agents" className="page">
                <div className="window_table_agents">
                    <div className="table_head">
                        <div className="search_agent">
                            <span className="label">Agent token:</span>
                            <input type="text" name="agent_token"></input>
                        </div>
                        <div className="active">
                            <button className="btn_icon">
                                <SmilePlus size={18}/>
                            </button>
                            <button className="btn_icon">
                                <Pencil size={18}/>
                            </button>
                        </div>
                    </div>
                    {!loading ? (
                        <LoadPage/>
                    ) : (
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Owner</th>
                                    <th>Add at</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td><input type="checkbox" name="" id="" /></td>
                                <td></td>
                            </tbody>
                        </table>
                    )}
                </div>
            </section>
        )
}