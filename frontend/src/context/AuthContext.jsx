import { createContext, useContext, useState, useEffect } from "react";
import api from "utils/api";

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
    const [user, setUser] = useState(null);
    const [token, setToken] = useState(
        localStorage.getItem("token") || sessionStorage.getItem("token")
    );

    const fetchUser = async () => {
        try {
            const res = await api.get("/users/me");
            setUser(res.data);
        } catch {
            setUser(null);
        }
    };

    useEffect(() => {
        if (token) fetchUser();
    }, [token]);

    const login = async (token, remember = false) => {
        if (remember) {
            localStorage.setItem("token", token);
        } else {
            sessionStorage.setItem("token", token);
        }
        setToken(token);
        await fetchUser();
    };

    const logout = () => {
        localStorage.removeItem("token");
        setUser(null);
        setToken(null);
    };

    const isAuthenticated = !!token;
    const isAdmin = user?.roles?.includes("admin") || false;

    return (
        <AuthContext.Provider
            value={{ user, token, isAuthenticated, isAdmin, login, logout }}
        >
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => useContext(AuthContext);
