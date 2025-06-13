import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import api from "utils/api";
import { useAuth } from "../context/AuthContext";

const Register = () => {
  const { login } = useAuth();
  const navigate = useNavigate();

  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });

  const [errors, setErrors] = useState({});

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrors({});
    try {
      const response = await api.post("/auth/register", form);
      const token = response.data.token;
      await login(token);
      navigate("/dashboard");
    } catch (err) {
      setErrors(err.response?.data?.errors || {});
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 flex items-center justify-center px-4">
      <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
        {status && <p className="mb-4 text-green-600 text-sm">{status}</p>}
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
              type="text"
              name="name"
              value={form.name}
              onChange={handleChange}
              required
              className="w-full px-3 py-2 border rounded-md focus:ring-indigo-500 focus:outline-none"
            />
            {errors.name && <p className="text-sm text-red-500 mt-1">{errors.name[0]}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              type="email"
              name="email"
              value={form.email}
              onChange={handleChange}
              required
              className="w-full px-3 py-2 border rounded-md focus:ring-indigo-500 focus:outline-none"
            />
            {errors.email && <p className="text-sm text-red-500 mt-1">{errors.email[0]}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              name="password"
              value={form.password}
              onChange={handleChange}
              required
              autoComplete="new-password"
              className="w-full px-3 py-2 border rounded-md focus:ring-indigo-500 focus:outline-none"
            />
            {errors.password && <p className="text-sm text-red-500 mt-1">{errors.password[0]}</p>}
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input
              type="password"
              name="password_confirmation"
              value={form.password_confirmation}
              onChange={handleChange}
              required
              className="w-full px-3 py-2 border rounded-md focus:ring-indigo-500 focus:outline-none"
            />
            {errors.password_confirmation && (
              <p className="text-sm text-red-500 mt-1">{errors.password_confirmation[0]}</p>
            )}
          </div>

          <div className="flex items-center justify-between pt-4">
            <a
              href="/login"
              className="text-sm text-gray-600 hover:text-gray-900 underline"
            >
              Already registered?
            </a>
            <button
              type="submit"
              className="ml-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500"
            >
              Register
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default Register;
