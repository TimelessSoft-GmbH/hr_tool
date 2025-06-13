import React, { useState, useEffect } from "react";
import axios from "axios";
import api from "../utils/api";
import AppLayout from "../components/AppLayout";

const VacationDashboard = () => {
  const [users, setUsers] = useState([]);
  const [requests, setRequests] = useState([]);
  const [selectedUser, setSelectedUser] = useState("");
  const [selectedYear, setSelectedYear] = useState("");
  const [showSubmit, setShowSubmit] = useState(false);
  const [editingId, setEditingId] = useState(null);
  const [dates, setDates] = useState({ start: "", end: "" });

  useEffect(() => {
    loadData();
  }, []);

  const loadData = async () => {
    const usersRes = await api.get("/users");
    const reqRes = await api.get("/vacation-requests");
    setUsers(usersRes.data);
    setRequests(reqRes.data);
  };

  const handleEdit = (req) => {
    setEditingId(req.id);
    setDates({ start: req.start_date, end: req.end_date });
  };

  const handleCancel = () => setEditingId(null);

  const saveChanges = async (id) => {
    await api.post(`/vacation-requests/${id}/update`, dates);
    setEditingId(null);
    loadData();
  };

  const handleAnswer = async (id, answer) => {
    await api.post(`/vacation-requests/${id}/answer`, { answer });
    loadData();
  };

  const filtered = requests.filter((req) => {
    return (
      (!selectedUser || req.user_name === selectedUser) &&
      (!selectedYear || new Date(req.start_date).getFullYear().toString() === selectedYear)
    );
  });

  return (
    <AppLayout>
      <h2 className="text-2xl font-bold mb-6">Urlaub-Anträge</h2>

      <div className="flex gap-4 mb-4">
        <select
          className="border p-2 rounded"
          onChange={(e) => setSelectedUser(e.target.value)}
        >
          <option value="">Alle Benutzer</option>
          {users.map((u) => (
            <option key={u.id} value={u.name}>{u.name}</option>
          ))}
        </select>
        <select
          className="border p-2 rounded"
          onChange={(e) => setSelectedYear(e.target.value)}
        >
          <option value="">Alle Jahre</option>
          {[...Array(10).keys()].map((i) => {
            const y = new Date().getFullYear() - i;
            return <option key={y} value={y}>{y}</option>;
          })}
        </select>
      </div>

      <table className="w-full text-sm text-left text-gray-500">
        <thead className="bg-gray-200">
          <tr>
            <th className="px-6 py-3 text-center">Name</th>
            <th className="px-6 py-3 text-center">Start</th>
            <th className="px-6 py-3 text-center">Ende</th>
            <th className="px-6 py-3 text-center">Status</th>
            <th className="px-6 py-3 text-center">Antwort</th>
            <th className="px-6 py-3 text-center">Aktion</th>
          </tr>
        </thead>
        <tbody>
          {filtered.map((req) => (
            <tr key={req.id} className="bg-gray-100">
              <td className="py-4 px-6 text-center">{req.user_name}</td>
              <td className="py-4 px-6 text-center">
                {editingId === req.id ? (
                  <input
                    type="date"
                    value={dates.start}
                    onChange={(e) => setDates((d) => ({ ...d, start: e.target.value }))}
                    className="border rounded px-2"
                  />
                ) : (
                  new Date(req.start_date).toLocaleDateString()
                )}
              </td>
              <td className="py-4 px-6 text-center">
                {editingId === req.id ? (
                  <input
                    type="date"
                    value={dates.end}
                    onChange={(e) => setDates((d) => ({ ...d, end: e.target.value }))}
                    className="border rounded px-2"
                  />
                ) : (
                  new Date(req.end_date).toLocaleDateString()
                )}
              </td>
              <td className="py-4 px-6 text-center text-sm font-semibold">
                <span className={
                  req.status === "accepted"
                    ? "text-green-500"
                    : req.status === "pending"
                    ? "text-yellow-600"
                    : "text-red-500"
                }>
                  {req.status}
                </span>
              </td>
              <td className="py-4 px-6 text-center">
                {req.status === "pending" ? (
                  <select
                    onChange={(e) => handleAnswer(req.id, e.target.value)}
                    className="border px-2 py-1 rounded"
                  >
                    <option value="">Antwort...</option>
                    <option value="accepted">accepted</option>
                    <option value="declined">declined</option>
                  </select>
                ) : (
                  <span className="text-gray-400 italic">Bearbeitet</span>
                )}
              </td>
              <td className="py-4 px-6 text-center">
                {editingId === req.id ? (
                  <>
                    <button
                      onClick={() => saveChanges(req.id)}
                      className="mr-2 bg-green-500 text-white px-2 py-1 rounded"
                    >
                      ✓
                    </button>
                    <button
                      onClick={handleCancel}
                      className="bg-red-500 text-white px-2 py-1 rounded"
                    >
                      ×
                    </button>
                  </>
                ) : (
                  <button
                    onClick={() => handleEdit(req)}
                    className="text-blue-500 hover:underline"
                  >
                    Bearbeiten
                  </button>
                )}
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </AppLayout>
  );
};

export default VacationDashboard;