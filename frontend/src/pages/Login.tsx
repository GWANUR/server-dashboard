import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { api } from "../api/user";
import { useAuth } from "../context/AuthContext";

export default function Login() {
    const navigate = useNavigate();
    const { setUser } = useAuth();

    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");

    async function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        try {
            await api.post("/login", {
                email,
                password,
            });

            const { data } = await api.get("/user");

            setUser(data);

            navigate("/");
        } catch (err) {
            console.error(err);
        }
    }

    return (
        <section className="page" id="login">
            <h1>Dashboard <a href="https://github.com/GWANUR" target="_blank" rel="noopener noreferrer">By StackAlex</a></h1>
            <div className="login_window">
            <h2>Login</h2>
                <form onSubmit={handleSubmit}>
                    <input
                        type="email"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                    />

                    <input
                        type="password"
                        value={password}
                        onChange={(e) => setPassword(e.target.value)}
                    />

                    <button type="submit">
                        Log In
                    </button>
                </form>
            </div>
        </section>
    );
}