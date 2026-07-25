import {
  SmilePlus,
  Pencil
} from "lucide-react";
import { useState, useEffect } from "react";
import { LoadPage } from "./LoadPage";
import { thisUser } from "../api/user"
import { allAgent } from "../api/agent"
import { useQuery } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";


export default function AgentPage(){
    
    const navigate = useNavigate();
    const {
        data: user,
        isLoading: isUserLoading,
        isError: isUserError,
        error: userError,
    } = useQuery({
        queryKey: ["user"],
        queryFn: thisUser,
    });

    const {
        data: agents,
        isLoading: isAgentsLoading,
    } = useQuery({
        queryKey: ["agents"],
        queryFn: allAgent,
        enabled: user?.type === "admin",
    });

    useEffect(() => {
        if (!isUserLoading && user?.type !== "admin") {
            navigate("/");
        }
    }, [isUserLoading, user, navigate]);
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
                    {isUserLoading || isAgentsLoading ? (
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
                                {agents?.map((el) => (
                                    <tr key={el.id}>
                                        <td>
                                            <input type="checkbox" />
                                        </td>
                                        <td>{el.name}</td>
                                        <td>{el.email}</td>
                                        <td>{el.type}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    )}
                </div>
            </section>
        )
}