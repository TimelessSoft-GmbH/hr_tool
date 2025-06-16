import React, { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import api from "../utils/api";
import AppLayout from "../components/AppLayout";

const UserUpdateScreen = () => {
    const { id } = useParams(); 
    const navigate = useNavigate();
    const isEditMode = !!id;

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

    const [errors, setErrors] = useState({});
    const roles = ["admin", "user"];
    const weekdays = [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday",
        "Sunday",
    ];

    useEffect(() => {
        if (isEditMode) {
            api.get(`/users/${id}`)
                .then((res) => {
                    setFormData({ ...res.data });
                })
                .catch((err) => console.error("Fetch error", err));
        }
    }, [id]);

    const handleChange = (e) => {
        const { name, value, type, checked, files } = e.target;

        if (name === "files") {
            setFormData((prev) => ({ ...prev, files }));
        } else if (name.startsWith("workdays")) {
            const val = value;
            const newWorkdays = new Set(formData.workdays || []);
            checked ? newWorkdays.add(val) : newWorkdays.delete(val);
            setFormData({ ...formData, workdays: [...newWorkdays] });
        } else if (name === "hasrole") {
            setFormData({ ...formData, roles: [value] });
        } else {
            setFormData({ ...formData, [name]: value });
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            const payload = {
                ...formData,
                vacationDays: Number(formData.vacationDays || 0),
                hours_per_week: Number(formData.hours_per_week || 0),
                salary: Number(formData.salary || 0),
                roles: [formData.roles?.[0] || "user"],
            };

            if (isEditMode) {
                await api.patch(`/users/${id}`, payload);
            } else {
                await api.post(`/users`, payload);
            }

            navigate("/users");
        } catch (err) {
            setErrors(err.response?.data?.errors || {});
        }
    };

    return (
        <AppLayout>
            <div className="w-full bg-white p-6 rounded-lg shadow-md">
                <h2 className="text-2xl font-semibold text-gray-800 mb-6">
                    {isEditMode ? "Update User" : "Create User"}
                </h2>
                <form onSubmit={handleSubmit} className="space-y-6">
                    <div>
                        <label className="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input
                            type="email"
                            name="email"
                            value={formData.email || ""}
                            onChange={handleChange}
                            className="w-full border px-3 py-2 rounded-md"
                            required
                        />
                        {errors.email && (
                            <p className="text-sm text-red-500 mt-1">{errors.email[0]}</p>
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
                            className="w-full border px-3 py-2 rounded-md"
                            required
                        />
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm">Phone Number</label>
                            <input
                                type="tel"
                                name="phoneNumber"
                                value={formData.phoneNumber || ""}
                                onChange={handleChange}
                                className="w-full border px-3 py-2 rounded-md"
                            />
                        </div>
                        <div>
                            <label className="block text-sm">Address</label>
                            <input
                                type="text"
                                name="address"
                                value={formData.address || ""}
                                onChange={handleChange}
                                className="w-full border px-3 py-2 rounded-md"
                            />
                        </div>
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm">Weekly Hours</label>
                            <input
                                type="number"
                                name="hours_per_week"
                                value={formData.hours_per_week || ""}
                                onChange={handleChange}
                                className="w-full border px-3 py-2 rounded-md"
                            />
                        </div>
                        <div>
                            <label className="block text-sm">Vacation Days</label>
                            <input
                                type="number"
                                name="vacationDays"
                                value={formData.vacationDays || ""}
                                onChange={handleChange}
                                className="w-full border px-3 py-2 rounded-md"
                            />
                        </div>
                    </div>

                    <div className="grid md:grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm">Salary</label>
                            <input
                                type="number"
                                name="salary"
                                value={formData.salary || ""}
                                onChange={handleChange}
                                className="w-full border px-3 py-2 rounded-md"
                            />
                        </div>
                        <div>
                            <label className="block text-sm">Start of Work</label>
                            <input
                                type="date"
                                name="start_of_work"
                                value={formData.start_of_work?.slice(0, 10) || ""}
                                onChange={handleChange}
                                className="w-full border px-3 py-2 rounded-md"
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm">Role</label>
                        <select
                            name="hasrole"
                            value={formData.roles?.[0] || ""}
                            onChange={handleChange}
                            className="w-full border px-3 py-2 rounded-md"
                        >
                            {roles.map((role) => (
                                <option key={role} value={role}>
                                    {role}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm">Upload PDF Files</label>
                        <input
                            type="file"
                            name="files"
                            accept=".pdf"
                            multiple
                            onChange={handleChange}
                            className="w-full border px-3 py-2 rounded-md"
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-2">Workdays</label>
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
                                        checked={formData.workdays?.includes(day) || false}
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
                            className="w-full bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-700"
                        >
                            {isEditMode ? "Update User" : "Create User"}
                        </button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
};

export default UserUpdateScreen;
