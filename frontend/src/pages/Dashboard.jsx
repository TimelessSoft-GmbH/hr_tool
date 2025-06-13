import React, { useEffect, useState } from "react";
import AppLayout from "../components/AppLayout";
import api from "utils/api";
import { useAuth } from "../context/AuthContext";
import ConfirmModal from "../components/ConfirmModal";
import PrimaryButton from "../components/PrimaryButton";
import DataTable from "../components/DataTable";

const Dashboard = () => {
  const { user } = useAuth();
  const [showVacReq, setShowVacReq] = useState(false);
  const [showSickReq, setShowSickReq] = useState(false);

  const [vacForm, setVacForm] = useState({ start_date: "", end_date: "" });
  const [sickForm, setSickForm] = useState({ start_date: "", end_date: "" });

  const [vacations, setVacations] = useState([]);
  const [sicknesses, setSicknesses] = useState([]);

  const [deleteId, setDeleteId] = useState(null);
  const [deleteSickId, setDeleteSickId] = useState(null);

  useEffect(() => {
    const fetchData = async () => {
      const vacRes = await api.get("/dashboard/vacations");
      const sickRes = await api.get("/dashboard/sicknesses");
      setVacations(vacRes.data);
      setSicknesses(sickRes.data);
    };
    fetchData();
  }, []);

  const handleVacationSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post("/dashboard/vacation", { ...vacForm, user_id: user._id });
      alert("Urlaubsantrag erfolgreich abgeschickt!");
      setShowVacReq(false);
    } catch (err) {
      console.error(err);
      alert("Fehler beim Abschicken des Urlaubsantrags");
    }
  };

  const handleSicknessSubmit = async (e) => {
    e.preventDefault();
    try {
      await api.post("/dashboard/sickness", { ...sickForm, user_id: user._id });
      alert("Krankmeldung erfolgreich abgeschickt!");
      setShowSickReq(false);
    } catch (err) {
      console.error(err);
      alert("Fehler beim Abschicken der Krankmeldung");
    }
  };

  const columns = [
    { key: "userId", label: "User", format: () => user?.name || "—" },
    {
      key: "startDate",
      label: "Start Datum",
      format: (val) => new Date(val).toLocaleDateString("en-GB", { day: "2-digit", month: "short" }),
    },
    {
      key: "endDate",
      label: "End Datum",
      format: (val) => new Date(val).toLocaleDateString("en-GB", { day: "2-digit", month: "short" }),
    },
    {
      key: "_id",
      label: "Jahr",
      format: (val) => new Date(val).getFullYear(),
    },
    { key: "totalDays", label: "Tage" },
    { key: "status", label: "Stand" },
  ];

  if (!user) return null;

  return (
    <AppLayout>
      <div className="py-12">
        <section className="bg-white shadow-sm sm:rounded-lg mt-12 p-6">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-gray-900 text-lg">Vacation Request</h2>
            <PrimaryButton onClick={() => setShowVacReq(!showVacReq)}>
              + Stelle einen Urlaubsantrag
            </PrimaryButton>
          </div>

          {showVacReq && (
            <form onSubmit={handleVacationSubmit} className="flex gap-4 items-end mb-6">
              <div>
                <label className="block text-sm">Start Datum:</label>
                <input type="date" className="p-2 rounded border" required value={vacForm.start_date} onChange={(e) => setVacForm({ ...vacForm, start_date: e.target.value })} />
              </div>
              <div>
                <label className="block text-sm">End Datum:</label>
                <input type="date" className="p-2 rounded border" required value={vacForm.end_date} onChange={(e) => setVacForm({ ...vacForm, end_date: e.target.value })} />
              </div>
              <PrimaryButton type="submit">Anfragen</PrimaryButton>
            </form>
          )}

          <DataTable
            columns={columns}
            data={vacations}
            renderActions={(row) => (
              <button className="bg-red-500 text-white px-2 py-1 rounded" onClick={() => setDeleteId(row._id)}>
                Löschen
              </button>
            )}
          />
        </section>

        <section className="bg-white shadow-sm sm:rounded-lg mt-12 p-6">
          <div className="flex justify-between items-center mb-4">
            <h2 className="text-gray-900 text-lg">Sickness Request</h2>
            <PrimaryButton onClick={() => setShowSickReq(!showSickReq)}>
              + Stelle eine Krankenstand Anfrage
            </PrimaryButton>
          </div>

          {showSickReq && (
            <form onSubmit={handleSicknessSubmit} className="flex gap-4 items-end mb-6">
              <div>
                <label className="block text-sm">Start Datum:</label>
                <input type="date" className="p-2 rounded border" required value={sickForm.start_date} onChange={(e) => setSickForm({ ...sickForm, start_date: e.target.value })} />
              </div>
              <div>
                <label className="block text-sm">End Datum:</label>
                <input type="date" className="p-2 rounded border" required value={sickForm.end_date} onChange={(e) => setSickForm({ ...sickForm, end_date: e.target.value })} />
              </div>
              <PrimaryButton type="submit">Anfragen</PrimaryButton>
            </form>
          )}

          <DataTable
            columns={columns}
            data={sicknesses}
            renderActions={(row) => (
              <button className="bg-red-500 text-white px-2 py-1 rounded" onClick={() => setDeleteSickId(row._id)}>
                Löschen
              </button>
            )}
          />
        </section>

        {deleteId && (
          <ConfirmModal
            title="Urlaubsantrag löschen?"
            message="Bist du sicher, dass du diesen Antrag löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden."
            onCancel={() => setDeleteId(null)}
            onConfirm={async () => {
              await api.delete(`/dashboard/vacation/${deleteId}`);
              setVacations((prev) => prev.filter((v) => v._id !== deleteId));
              setDeleteId(null);
            }}
          />
        )}

        {deleteSickId && (
          <ConfirmModal
            title="Krankmeldung löschen?"
            message="Bist du sicher, dass du diese Krankmeldung löschen möchtest? Diese Aktion kann nicht rückgängig gemacht werden."
            onCancel={() => setDeleteSickId(null)}
            onConfirm={async () => {
              await api.delete(`/dashboard/sickness/${deleteSickId}`);
              setSicknesses((prev) => prev.filter((s) => s._id !== deleteSickId));
              setDeleteSickId(null);
            }}
          />
        )}
      </div>
    </AppLayout>
  );
};

export default Dashboard;
