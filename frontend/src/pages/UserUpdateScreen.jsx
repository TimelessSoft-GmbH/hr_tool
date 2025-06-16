import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import logo from "../assets/tlsoftLogo.png";
import api from "../utils/api";
import AppLayout from "../components/AppLayout";

const UserUpdateScreen = () => {
    const { id } = useParams();
    const navigate = useNavigate();
    const [user, setUser] = useState(null);
    const [formData, setFormData] = useState({
        name: "",
        email: "",
        phoneNumber: "",
        address: "",
        hours_per_week: 0,
        vacationDays: 0,
        salary: 0,
        start_of_work: "",
        workdays: [],
        roles: ["user"],
    });

    const weekdays = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
    ];
    const [errors, setErrors] = useState({});
    const roles = ["admin", "user"];

    const fetchUser = async () => {
        const res = await api.get(`/users/${id}`);
        setUser(res.data);
        setFormData({ ...res.data });
    };

    useEffect(() => {
        fetchUser();
    }, [id]);

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;

        if (name === "files") {
            setFormData((prev) => ({ ...prev, files: e.target.files }));
        } else if (name.startsWith("workdays")) {
            const val = value;
            const newWorkdays = new Set(formData.workdays || []);
            checked ? newWorkdays.add(val) : newWorkdays.delete(val);
            setFormData({ ...formData, workdays: [...newWorkdays] });
        } else {
            setFormData({ ...formData, [name]: value });
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const payload = {
                ...formData,
                roles: [formData.roles?.[0] || "user"],
                vacationDays: Number(formData.vacationDays || 0),
                hours_per_week: Number(formData.hours_per_week || 0),
                salary: Number(formData.salary || 0),
            };

            await api.patch(`/users/${id}`, payload);
            navigate("/users");
        } catch (err) {
            setErrors(err.response?.data?.errors || {});
        }
    };

    if (!user) return <div className="text-center py-20">Loading...</div>;

    return (
        <AppLayout>
            <div className="w-full bg-white p-6 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold text-gray-800 mb-6">
                    Update User
                </h2>
                <form
                    onSubmit={handleSubmit}
                    className="space-y-6"
                    encType="multipart/form-data"
                >
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value={formData.email || ""}
                            onChange={handleChange}
                            className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
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
                            Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            value={formData.name || ""}
                            onChange={handleChange}
                            className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            required
                        />
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Phone Number
                            </label>
                            <input
                                type="tel"
                                name="phoneNumber"
                                value={formData.phoneNumber || ""}
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Address
                            </label>
                            <input
                                type="text"
                                name="address"
                                value={formData.address || ""}
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Weekly Hours
                            </label>
                            <input
                                type="number"
                                name="hours_per_week"
                                value={formData.hours_per_week || ""}
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Vacation Days
                            </label>
                            <input
                                type="number"
                                name="vacationDays"
                                value={formData.vacationDays || ""}
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Salary
                            </label>
                            <input
                                type="number"
                                name="salary"
                                value={formData.salary || ""}
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Start of Work
                            </label>
                            <input
                                type="date"
                                name="start_of_work"
                                value={
                                    formData.start_of_work?.slice(0, 10) || ""
                                }
                                onChange={handleChange}
                                className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            />
                        </div>
                    </div>
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Role
                        </label>
                        <select
                            name="hasrole"
                            value={formData.roles[0] || ""}
                            onChange={handleChange}
                            className="w-full border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            {roles.map((role) => (
                                <option key={role} value={role}>
                                    {role}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Upload PDF Files
                        </label>
                        <input
                            type="file"
                            name="files"
                            accept=".pdf"
                            multiple
                            onChange={handleChange}
                            className="block w-full border border-gray-300 px-3 py-2 rounded-md"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-2">
                            Workdays
                        </label>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-2">
                            {weekdays.map((day) => (
                                <label
                                    key={day}
                                    className="flex items-center space-x-2 text-sm"
                                >
                                    <input
                                        type="checkbox"
                                        name={`workdays-${day}`}
                                        value={day}
                                        checked={
                                            formData.workdays?.includes(day) ||
                                            false
                                        }
                                        onChange={handleChange}
                                        className="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                    />
                                    <span>{day}</span>
                                </label>
                            ))}
                        </div>
                    </div>
                    <div className="mt-4">
                        <button
                            type="submit"
                            className="w-full bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                            Update User
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
};

export default UserUpdateScreen;
