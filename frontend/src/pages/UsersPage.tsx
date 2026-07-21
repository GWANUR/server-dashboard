import {
  UserRoundPlus,
  UserPen,
} from "lucide-react";


export default function Users_page() {
    return (
        <>
            <section id="users" className="page">
                <div className="table_users">
                    <div className="active">
                        <button className="add_users btn_icon"><UserRoundPlus size={20}/>Add User</button>
                        <button className="edit_users btn_icon"><UserPen size={20}/>Edit User</button>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr className="item">
                                <td>
                                    <input type="checkbox" />
                                </td>
                                <td>John Doe</td>
                                <td>john.doe@example.com</td>
                                <td>Admin</td>
                            </tr>
                            <tr className="item">
                                <td>
                                    <input type="checkbox" />
                                </td>
                                <td>Jane Smith</td>
                                <td>jane.smith@example.com</td>
                                <td>User</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </>
    );
}