import React, { useState } from "react";
import axios from "axios";
import logo from "../assets/tlsoftLogo.png";

const Login = () => {
    const [email, setEmail] = useState("");
    const [password, setPassword] = useState("");
    const [remember, setRemember] = useState(false);
    const [errors, setErrors] = useState({});
    const [status, setStatus] = useState("");

    axios.defaults.withCredentials = true;

    const handleSubmit = async (e) => {
        e.preventDefault();

        try {
            await axios.get("/api/sanctum/csrf-cookie");

            const response = await axios.post("/api/login", {
                email,
                password,
                remember,
            });

            setStatus("Login successful");
            setErrors({});

            if (response.data?.redirect) {
                window.location.href = response.data.redirect;
            }
        } catch (err) {
            setStatus("");
            setErrors(err.response?.data?.errors || {});
        }
    };

    return (
        <div className="mx-auto px-4 xl:px-0 lg:mt-24 lg:mb-10 max-w-7xl w-full mt-24 md:mt-10">
            <div className=" flex flex-col items-center justify-center px-4">
                <div className="mb-8">
                    <img src={logo} alt="Timeless Soft" className="h-10" />
                </div>

                <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
                    {status && (
                        <div className="mb-4 text-green-600 text-sm">
                            {status}
                        </div>
                    )}
                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Email
                            </label>
                            <input
                                type="email"
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value={email}
                                onChange={(e) => setEmail(e.target.value)}
                                required
                            />
                            {errors.email && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.email[0]}
                                </p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Password
                            </label>
                            <input
                                type="password"
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                value={password}
                                onChange={(e) => setPassword(e.target.value)}
                                required
                            />
                            {errors.password && (
                                <p className="text-sm text-red-500 mt-1">
                                    {errors.password[0]}
                                </p>
                            )}
                        </div>

                        <div className="flex items-center">
                            <input
                                type="checkbox"
                                id="remember"
                                className="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                checked={remember}
                                onChange={(e) => setRemember(e.target.checked)}
                            />
                            <label
                                htmlFor="remember"
                                className="ml-2 block text-sm text-gray-700"
                            >
                                Remember me
                            </label>
                        </div>

                        <div className="flex justify-between text-sm text-gray-600 mt-4">
                            <a href="/register" className="hover:underline">
                                Register
                            </a>
                            <a
                                href="/forgot-password"
                                className="hover:underline"
                            >
                                Forgot your password?
                            </a>
                        </div>

                        <div className="mt-4">
                            <button
                                type="submit"
                                className="w-full bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                LOG IN
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    );
};

export default Login;
