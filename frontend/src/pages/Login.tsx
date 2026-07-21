export default function Login() {
    return (
        <>
        <section className="pages" id="Login">
            <h1 className="">DashBoard by StackAlex</h1>
            <div className="login_window">
                <h2>Log In</h2>
                <form action="/">
                    <div className="input_icon">
                        <input type="text" placeholder="Username" />
                    </div>
                    <div className="input_icon">
                        <input type="password" placeholder="Password" />
                    </div>
                    <button type="submit" className="btn_icon">
                        Log In
                    </button>
                </form>
            </div>
        </section>
        </>
    );
}