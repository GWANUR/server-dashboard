// =====================================
// This component created by StackAlex
// https://github.com/StackAlex
// Rules:
// 1. You cannot delete this comment
// 2. You cannot rename classes in this file, only add classes
// 3. You can edit file index.css 
// =====================================


import { useState } from "react";
import {
  Eye,
  EyeOff
} from "lucide-react";
import "./index.css";

interface SecretInputProps {
    nameInput: string;
}
export function SecretInput({ nameInput }: SecretInputProps){
    const [password,setPas] = useState(true)
    return (
        <div className="SecretInput__SA">
            <input 
                name={nameInput} 
                type={password ? "password" : "text"}
                autoComplete="off"
                spellCheck={false}
                autoCapitalize="off"
            />
            <button
            onClick={()=> setPas(!password)}
            >
                { password ? (
                    <Eye />
                    ) : (
                    <EyeOff />
                    )
                }
            </button>
        </div>
    )
}