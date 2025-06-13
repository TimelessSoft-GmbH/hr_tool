import React, { useState } from "react";
import api from "../utils/api";

const WorkHoursForm = ({ users, selectedYear, onSuccess }) => {
  const [form, setForm] = useState({
    user_id: "",
    year: selectedYear,
    month: 1,
    hours: "",
  });
  const [error, setError] = useState("");

  const handleChange = (e) => {
    setForm(prev => ({ ...prev, [e.target.name]: e.target.value }));
  };

 const handleSubmit = async (e) => {
  e.preventDefault();
  setError("");

  try {
    await api.post("/work-hours/update", {
      ...form,
      year: parseInt(form.year),
      month: parseInt(form.month),
      hours: parseFloat(form.hours),
    });
    setForm({ ...form, hours: "" });
    onSuccess();
  } catch (err) {
    setError("Fehler beim Speichern.");
  }
};

  return (
    <form onSubmit={handleSubmit} className="flex flex-wrap items-end gap-4 mt-4">
      <div>
        <label>User:</label>
        <select name="user_id" onChange={handleChange} className="border rounded px-2 py-1" required>
          <option value="">-- auswählen --</option>
          {users.map(u => (
            <option key={u.id} value={u._id}>{u.name}</option>
          ))}
        </select>
      </div>

      <div>
        <label>Jahr:</label>
        <select name="year" value={form.year} onChange={handleChange} className="border rounded px-2 py-1">
          {[...Array(new Date().getFullYear() - 2020).keys()].map(i => {
            const year = new Date().getFullYear() - i;
            return <option key={year} value={year}>{year}</option>;
          })}
        </select>
      </div>

      <div>
        <label>Monat:</label>
        <select name="month" onChange={handleChange} className="border rounded px-2 py-1">
          {Array.from({ length: 12 }, (_, i) => (
            <option key={i} value={i + 1}>{monthNames[i]}</option>
          ))}
        </select>
      </div>

      <div>
        <label>Stunden:</label>
        <input type="number" name="hours" onChange={handleChange} value={form.hours}
          className="border rounded px-2 py-1 w-24" required />
      </div>

      <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Update
      </button>

      {error && <p className="text-red-500">{error}</p>}
    </form>
  );
};

const monthNames = [
  "Jänner", "Februar", "März", "April", "Mai", "Juni",
  "Juli", "August", "September", "Oktober", "November", "Dezember"
];

export default WorkHoursForm;
