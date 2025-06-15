import { useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import api from "../utils/api";
import logo from "../assets/tlsoftLogo.png";

function ResetPassword() {
  const { token } = useParams();
  const navigate = useNavigate();
  const { login } = useAuth();

  const [password, setPassword] = useState("");
  const [confirm, setConfirm] = useState("");
  const [error, setError] = useState(null);

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (password !== confirm) {
      setError("Passwords do not match.");
      return;
    }

    try {
      const res = await api.patch(`/auth/reset-password/${token}`, {
        password,
      });
      await login(res.data.access_token, true);
      navigate("/dashboard");
    } catch (err) {
      setError("Reset failed. Try again.");
    }
  };

  return (
    <div className="mx-auto px-4 xl:px-0 lg:mt-24 lg:mb-10 max-w-7xl w-full mt-24 md:mt-10">
      <div className="flex flex-col items-center justify-center px-4">
        <div className="mb-8">
          <img src={logo} alt="Timeless Soft" className="h-10" />
        </div>

        <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-xl font-semibold mb-4">Set New Password</h2>
          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <input
                type="password"
                placeholder="New Password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required
              />
            </div>
            <div>
              <input
                type="password"
                placeholder="Confirm Password"
                value={confirm}
                onChange={(e) => setConfirm(e.target.value)}
                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                required
              />
            </div>
            {error && <p className="text-sm text-red-600">{error}</p>}
            <button
              type="submit"
              className="w-full bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
              Reset Password
            </button>
          </form>
        </div>
      </div>
    </div>
  );
}

export default ResetPassword;
