import React, { useEffect, useState } from "react";
import axios from "axios";
import AppLayout from "../components/AppLayout";
import api from "../utils/api";
import Card from "../components/Card";
import UpdatePasswordForm from "./UpdatePasswordForm";
import UpdateProfileImage from "./UpdateProfileImage";
import DeleteAccount from "./DeleteAccount";

const ProfileForm = () => {
    const [form, setForm] = useState({ name: "", email: "" });
    const [errors, setErrors] = useState({});
    const [status, setStatus] = useState("");
    const [needsVerification, setNeedsVerification] = useState(false);
    const [emailVerified, setEmailVerified] = useState(true);

    useEffect(() => {
        api.get("/users/me", {
            withCredentials: true,
        }).then((res) => {
            setForm({ name: res.data.name, email: res.data.email });
            setNeedsVerification(res.data.must_verify_email ?? false);
            setEmailVerified(res.data.email_verified_at !== null);
        });
    }, []);

    const handleChange = (e) => {
        setForm((prev) => ({ ...prev, [e.target.name]: e.target.value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setStatus("");

        try {
            await api.patch("/users", form);
            setStatus("profile-updated");
            setErrors({});
        } catch (err) {
            setErrors(err.response?.data?.errors || {});
        }
    };

    const resendVerification = async () => {
        try {
            await axios.post("/api/email/verification-notification");
            setStatus("verification-link-sent");
        } catch (err) {
            console.error(err);
        }
    };

    return (
        <AppLayout>
            <section>
                <Card>
                    <header>
                        <h2 className="text-lg font-medium text-gray-900">
                            Profile Information
                        </h2>
                        <p className="mt-1 text-sm text-gray-600">
                            Update your account's profile information and email
                            address.
                        </p>
                    </header>
                    <form onSubmit={handleSubmit} className="mt-6 space-y-6">
                        <div>
                            <label
                                htmlFor="name"
                                className="block font-medium text-sm text-gray-700"
                            >
                                Name
                            </label>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                className="mt-1 block w-full border rounded p-2"
                                value={form.name}
                                onChange={handleChange}
                                required
                                autoComplete="name"
                            />
                            {errors.name && (
                                <p className="text-sm text-red-500">
                                    {errors.name[0]}
                                </p>
                            )}
                        </div>

                        <div>
                            <label
                                htmlFor="email"
                                className="block font-medium text-sm text-gray-700"
                            >
                                Email
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                className="mt-1 block w-full border rounded p-2"
                                value={form.email}
                                onChange={handleChange}
                                required
                                autoComplete="email"
                            />
                            {errors.email && (
                                <p className="text-sm text-red-500">
                                    {errors.email[0]}
                                </p>
                            )}

                            {needsVerification && !emailVerified && (
                                <div className="mt-2 text-sm text-gray-800">
                                    Your email address is unverified.
                                    <button
                                        onClick={resendVerification}
                                        className="ml-2 underline text-sm text-gray-600 hover:text-gray-900 focus:outline-none"
                                        type="button"
                                    >
                                        Click here to re-send the verification
                                        email.
                                    </button>
                                    {status === "verification-link-sent" && (
                                        <p className="mt-2 font-medium text-sm text-green-600">
                                            A new verification link has been
                                            sent to your email address.
                                        </p>
                                    )}
                                </div>
                            )}
                        </div>

                        <div className="flex items-center gap-4">
                            <button
                                type="submit"
                                className="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500"
                            >
                                Save
                            </button>
                            {status === "profile-updated" && (
                                <p className="text-sm text-gray-600 animate-pulse">
                                    Saved.
                                </p>
                            )}
                        </div>
                    </form>
                </Card>
                <Card>
                    <UpdatePasswordForm />
                </Card>
                <Card>
                    <UpdateProfileImage />
                </Card>
                <Card>
                    <DeleteAccount />
                </Card>
            </section>
        </AppLayout>
    );
};

export default ProfileForm;
