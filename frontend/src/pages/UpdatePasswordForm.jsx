import React, { useState } from "react";
import api from "../utils/api";

const UpdatePasswordForm = () => {
  const [form, setForm] = useState({
    current_password: "",
    password: "",
    password_confirmation: "",
  });

  const [errors, setErrors] = useState({});
  const [status, setStatus] = useState("");

  const handleChange = (e) => {
    setForm((prev) => ({
      ...prev,
      [e.target.name]: e.target.value,
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setStatus("");
    setErrors({});

    try {
      await api.put("/users/password", form,{ withCredentials: true });
      setStatus("password-updated");
    } catch (error) {
      setErrors(error.response?.data?.errors || {});
    }
  };

  return (
    <section>
      <header>
        <h2 className="text-lg font-medium text-gray-900">Update Password</h2>
        <p className="mt-1 text-sm text-gray-600">
          Ensure your account is using a long, random password to stay secure.
        </p>
      </header>

      <form onSubmit={handleSubmit} className="mt-6 space-y-6">
        <div>
          <label htmlFor="current_password" className="block text-sm font-medium text-gray-700">
            Current Password
          </label>
          <input
            id="current_password"
            name="current_password"
            type="password"
            autoComplete="current-password"
            className="mt-1 block w-full border rounded p-2"
            value={form.current_password}
            onChange={handleChange}
          />
          {errors.current_password && (
            <p className="text-sm text-red-500 mt-2">{errors.current_password[0]}</p>
          )}
        </div>

        <div>
          <label htmlFor="password" className="block text-sm font-medium text-gray-700">
            New Password
          </label>
          <input
            id="password"
            name="password"
            type="password"
            autoComplete="new-password"
            className="mt-1 block w-full border rounded p-2"
            value={form.password}
            onChange={handleChange}
          />
          {errors.password && (
            <p className="text-sm text-red-500 mt-2">{errors.password[0]}</p>
          )}
        </div>

        <div>
          <label htmlFor="password_confirmation" className="block text-sm font-medium text-gray-700">
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            name="password_confirmation"
            type="password"
            autoComplete="new-password"
            className="mt-1 block w-full border rounded p-2"
            value={form.password_confirmation}
            onChange={handleChange}
          />
          {errors.password_confirmation && (
            <p className="text-sm text-red-500 mt-2">{errors.password_confirmation[0]}</p>
          )}
        </div>

        <div className="flex items-center gap-4">
          <button
            type="submit"
            className="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500"
          >
            Save
          </button>

          {status === "password-updated" && (
            <p className="text-sm text-gray-600 animate-pulse">Saved.</p>
          )}
        </div>
      </form>
    </section>
  );
};

export default UpdatePasswordForm;
