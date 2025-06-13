import React from "react";
import { NavLink, useNavigate } from "react-router-dom";
import logo from "../assets/tlsoftLogo.png";
import { useAuth } from "../context/AuthContext";

const Sidebar = ({ isAdmin }) => {
    const { logout } = useAuth();
    const navigate = useNavigate();

    const handleLogout = async (e) => {
        e.preventDefault();

        try {
            await logout();
            navigate("/login");
        } catch (error) {
            console.error("Logout failed:", error);
        }
    };

    return (
        <aside className="fixed top-0 left-0 h-screen w-64 bg-gray-700 border-r border-gray-200 flex flex-col justify-between">
            <div>
                <div className="py-4 px-6 flex flex-col items-center">
                    <img
                        src={logo}
                        alt="Timeless Soft"
                        className="w-40 h-auto"
                    />
                    <hr className="border-t border-white mt-8 w-full" />
                </div>

                <nav className="px-6 py-4 space-y-2">
                    <SidebarLink to="/dashboard" icon="📊" label="Dashboard" />
                    {isAdmin && (
                        <>
                            <SidebarLink
                                to="/enter-hours"
                                icon="🕒"
                                label="Stunden"
                            />
                            <SidebarLink
                                to="/users"
                                icon="👥"
                                label="Angestellte"
                            />
                            <SidebarLink
                                to="/requests"
                                icon="📦"
                                label="Anfragen"
                            />
                        </>
                    )}
                    <SidebarLink to="/profile" icon="👤" label="Profile" />
                </nav>
            </div>

            <div className="px-6 py-4">
                <div className="px-6 py-4">
                    <button
                        onClick={handleLogout}
                        className="bg-red-700 flex items-center py-3 px-7 text-white hover:bg-red-800 focus:ring-4 focus:ring-red-300 rounded-lg"
                    >
                        <span className="text-xl mr-3">⏎</span>
                        <span className="text-md">Log Out</span>
                    </button>
                </div>
            </div>
        </aside>
    );
};

const SidebarLink = ({ to, icon, label }) => (
    <NavLink
        to={to}
        className={({ isActive }) =>
            `flex items-center py-3 px-4 text-white hover:bg-gray-200 hover:text-gray-800 rounded-lg ${
                isActive ? "bg-gray-200 text-black" : ""
            }`
        }
    >
        <span className="text-xl mr-3">{icon}</span>
        <span className="text-md">{label}</span>
    </NavLink>
);

export default Sidebar;
