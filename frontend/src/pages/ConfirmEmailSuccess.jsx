import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import api from "../utils/api";
import logo from "../assets/tlsoftLogo.png";

function ConfirmEmailSuccess() {
  const { token } = useParams();
  const navigate = useNavigate();
  const { login } = useAuth();
  const [status, setStatus] = useState("loading");

  useEffect(() => {
    const confirmEmail = async () => {
      try {
        const res = await api.get(`/auth/confirm-email/${token}`);
        await login(res.data.access_token, true);
        setStatus("success");
        navigate("/dashboard");
      } catch (e) {
        setStatus("error");
      }
    };

    confirmEmail();
  }, [token, login, navigate]);

  return (
    <div className="mx-auto px-4 xl:px-0 lg:mt-24 lg:mb-10 max-w-7xl w-full mt-24 md:mt-10">
      <div className="flex flex-col items-center justify-center px-4">
        <div className="mb-8">
          <img src={logo} alt="Timeless Soft" className="h-10" />
        </div>

        <div className="w-full max-w-md bg-white p-6 rounded-lg shadow-md text-center">
          {status === "loading" && (
            <p className="text-gray-700">E-Mail wird bestätigt…</p>
          )}
          {status === "error" && (
            <p className="text-red-600">
              Bestätigung fehlgeschlagen. Versuche es erneut.
            </p>
          )}
        </div>
      </div>
    </div>
  );
}

export default ConfirmEmailSuccess;
