import React, { useEffect, useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import AppLayout from "../components/AppLayout";
import api from "../utils/api";
import { useAuth } from "../context/AuthContext";

const UserListScreen = () => {
    const { user, logout, isAdmin } = useAuth();
    const navigate = useNavigate();
    const [users, setUsers] = useState([]);

    useEffect(() => {
        const fetchUsers = async () => {
            try {
                const res = await api.get("/users");
                setUsers(res.data);
            } catch (err) {
                console.error("Error fetching users:", err);
            }
        };
        fetchUsers();
    }, []);

    const handleDeleteUser = async (userId) => {
        try {
            await api.delete(`/users/${userId}`);
            setUsers((prev) => prev.filter((u) => u._id !== userId));

            if (userId === user?._id) {
                logout(); // Logs out
                navigate("/login"); // Optional redirect
            }
        } catch (err) {
            console.error("Delete failed:", err);
        }
    };

    return (
        <AppLayout>
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            <div className="pb-4 pt-4">
                                    <div className="relative overflow-x-auto shadow-md sm:rounded-lg">
                                        <table className="w-full text-sm text-left text-gray-500">
                                            <thead className="text-xs text-gray-700 uppercase bg-gray-200">
                                                <tr>
                                                    <th className="px-6 py-3">
                                                        Image
                                                    </th>
                                                    <th className="px-6 py-3">
                                                        Username
                                                    </th>
                                                    <th className="px-6 py-3">
                                                        Email
                                                    </th>
                                                    <th className="px-6 py-3">
                                                        Role
                                                    </th>
                                                    <th className="px-4 py-3">
                                                        Edit
                                                    </th>
                                                    <th className="pr-2 py-3">
                                                        Delete
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {users.map((u) => (
                                                    <tr
                                                        className="bg-gray-100"
                                                        key={u._id}
                                                    >
                                                        <td className="py-4 px-6">
                                                            {u.image ? (
                                                                <img
                                                                    src={`/images/${u.image}`}
                                                                    alt="profile"
                                                                    className="rounded-full w-10 h-10 ml-1"
                                                                />
                                                            ) : (
                                                                <div className="inline-flex items-center justify-center w-10 h-10 bg-gray-300 rounded-full">
                                                                    <span className="font-medium text-gray-600">
                                                                        {
                                                                            u.initials
                                                                        }
                                                                    </span>
                                                                </div>
                                                            )}
                                                        </td>
                                                        <td className="py-4 px-6">
                                                            {u.name}
                                                        </td>
                                                        <td className="py-4 px-6">
                                                            {u.email}
                                                        </td>
                                                        <td className="py-4 px-6">
                                                            {u.roles?.includes(
                                                                "admin"
                                                            ) ? (
                                                                <span className="text-red-600 font-bold">
                                                                    ADMIN
                                                                </span>
                                                            ) : (
                                                                "USER"
                                                            )}
                                                        </td>
                                                        <td className="py-4 px-4">
                                                            <Link
                                                                to={`/admin/user/update/${u._id}`}
                                                            >
                                                                <button className="w-full md:w-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                                                    Edit
                                                                </button>
                                                            </Link>
                                                        </td>
                                                        <td className="pt-3 flex items-center">
                                                            {(u._id === user?._id) ||
                                                            isAdmin ? (
                                                                <button
                                                                    onClick={() => {
                                                                        if (
                                                                            confirm(
                                                                                `Are you sure you want to delete user ${u.name}?`
                                                                            )
                                                                        ) {
                                                                            handleDeleteUser(
                                                                                u._id
                                                                            );
                                                                        }
                                                                    }}
                                                                    className="font-bold text-white bg-red-500 text-sm rounded-lg py-3"
                                                                >
                                                                    Delete
                                                                </button>
                                                            ) : (
                                                                <p className="py-4 px-2 italic text-gray-400">
                                                                    -----
                                                                </p>
                                                            )}
                                                        </td>
                                                    </tr>
                                                ))}
                                            </tbody>
                                        </table>
                                    </div>
                                    <Link to="/admin/user/update">
                                        <button className="mt-5 ml-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5">
                                            <span className="font-bold">+</span>{" "}
                                            Neuer User
                                        </button>
                                    </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
};

export default UserListScreen;
