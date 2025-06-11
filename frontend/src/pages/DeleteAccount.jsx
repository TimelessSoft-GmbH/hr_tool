import React, { useState } from "react";
import axios from "axios";

const DeleteAccount = () => {
  const [showModal, setShowModal] = useState(false);
  const [password, setPassword] = useState("");
  const [errors, setErrors] = useState({});
  const [status, setStatus] = useState("");

  const handleDelete = async (e) => {
    e.preventDefault();
    setErrors({});
    setStatus("");

    try {
      await axios.delete("/api/profile", {
        data: { password },
        withCredentials: true,
      });
      // Redirect or show confirmation
      window.location.href = "/goodbye"; // or logout
    } catch (err) {
      if (err.response?.data?.errors) {
        setErrors(err.response.data.errors);
      } else {
        setStatus("An error occurred. Try again.");
      }
    }
  };

  return (
    <section className="space-y-6">
      <header>
        <h2 className="text-lg font-medium text-gray-900">Delete Account</h2>
        <p className="mt-1 text-sm text-gray-600">
          Once your account is deleted, all of its resources and data will be permanently deleted. 
          Please download any data or information you wish to retain.
        </p>
      </header>

      <button
        onClick={() => setShowModal(true)}
        className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
      >
        Delete Account
      </button>

      {showModal && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
          <div className="bg-white rounded shadow-lg p-6 w-full max-w-md">
            <h2 className="text-lg font-medium text-gray-900 mb-2">
              Are you sure you want to delete your account?
            </h2>
            <p className="text-sm text-gray-600 mb-4">
              Once your account is deleted, all resources and data will be permanently deleted.
              Enter your password to confirm.
            </p>

            <form onSubmit={handleDelete}>
              <input
                type="password"
                name="password"
                placeholder="Password"
                className="w-full border rounded p-2 mb-2"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
              />
              {errors.password && (
                <p className="text-sm text-red-500">{errors.password[0]}</p>
              )}

              <div className="flex justify-end mt-4">
                <button
                  type="button"
                  onClick={() => setShowModal(false)}
                  className="mr-3 px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                >
                  Delete Account
                </button>
              </div>

              {status && (
                <p className="mt-2 text-sm text-red-500">{status}</p>
              )}
            </form>
          </div>
        </div>
      )}
    </section>
  );
};

export default DeleteAccount;
