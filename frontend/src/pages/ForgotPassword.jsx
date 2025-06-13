import React, { useState } from "react";
import axios from "axios";

const ForgotPassword = () => {
    const [email, setEmail] = useState("");
    const [status, setStatus] = useState("");
    const [errors, setErrors] = useState({});

    axios.defaults.withCredentials = true;

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus("");
        setErrors({});

        try {
            const response = await axios.post("/api/auth/forgot-password", {
                email,
            });
            setStatus("We have emailed your password reset link!");
        } catch (err) {
            setErrors(err.response?.data?.errors || {});
        }
    };

    return (
        <div className="min-h-screen bg-gray-100 flex items-center justify-center px-4">
            <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
                <p className="mb-4 text-sm text-gray-600">
                    Forgot your password? No problem. Just let us know your
                    email address and we will email you a password reset link.
                </p>

                {status && (
                    <div className="mb-4 text-sm text-green-600">{status}</div>
                )}

                <form onSubmit={handleSubmit} className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            required
                            className="w-full px-3 py-2 border rounded-md focus:ring-indigo-500 focus:outline-none"
                        />
                        {errors.email && (
                            <p className="text-sm text-red-500 mt-1">
                                {errors.email[0]}
                            </p>
                        )}
                    </div>

                    <div className="flex justify-end">
                        <button
                            type="submit"
                            className="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500"
                        >
                            Email Password Reset Link
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
};

export default ForgotPassword;
