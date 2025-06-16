import React, { useState, useEffect } from "react";
import api from "../utils/api";
import AppLayout from "../components/AppLayout";
import VacationCalendar from "../components/VacationCalendar";
import Card from "../components/Card";
import ConfirmModal from "../components/ConfirmModal";

const VacationDashboard = () => {
    const [users, setUsers] = useState([]);
    const [vacationRequests, setVacationRequests] = useState([]);
    const [sicknessRequests, setSicknessRequests] = useState([]);
    const [selectedUser, setSelectedUser] = useState("");
    const [selectedYear, setSelectedYear] = useState("");
    const [selectedSickUser, setSelectedSickUser] = useState("");
    const [selectedSickYear, setSelectedSickYear] = useState("");
    const [newVacation, setNewVacation] = useState({
        user_id: "",
        start_date: "",
        end_date: "",
    });
    const [newSickness, setNewSickness] = useState({
        user_id: "",
        start_date: "",
        end_date: "",
    });
    const [showAllVacation, setShowAllVacation] = useState(false);
    const [showAllSickness, setShowAllSickness] = useState(false);
    const [confirmDelete, setConfirmDelete] = useState(null);
    const [editingId, setEditingId] = useState(null);
    const [editDates, setEditDates] = useState({});
    const [holidays, setHolidays] = useState([]);

    const [selectedRegion, setSelectedRegion] = useState("AT");
    const [holidayYear, setHolidayYear] = useState(
        new Date().getFullYear().toString()
    );

    useEffect(() => {
        loadHolidays(holidayYear, selectedRegion);
    }, [holidayYear, selectedRegion]);

    const loadHolidays = async (year, region = "AT") => {
        try {
            const response = await api.get(
                `/dashboard/holidays/${region}/${year}`
            );
            setHolidays(response.data);
        } catch (error) {
            console.error("Error loading holidays:", error);
            setHolidays([]);
        }
    };

    useEffect(() => {
        loadData();
    }, []);

    const loadData = async () => {
        const dashboard = await api.get("/dashboard");
        setUsers(dashboard.data.users);
        setVacationRequests(dashboard.data.vacationRequests);
        setSicknessRequests(dashboard.data.sicknessRequests);
    };

    const handleSubmitVacation = async (e) => {
        e.preventDefault();
        await api.post("/dashboard/vacation", {
            ...newVacation,
            status: "approved",
        });
        setNewVacation({ user_id: "", start_date: "", end_date: "" });
        loadData();
    };

    const handleSubmitSickness = async (e) => {
        e.preventDefault();
        await api.post("/dashboard/sickness", {
            ...newSickness,
            status: "approved",
        });
        setNewSickness({ user_id: "", start_date: "", end_date: "" });
        loadData();
    };

    const approveRequest = async (id, type) => {
        await api.patch(`/dashboard/${type}/${id}/approve`);
        loadData();
    };

    const confirmDeleteRequest = (id, type) => {
        setConfirmDelete({ id, type });
    };

    const handleDeleteConfirmed = async () => {
        if (confirmDelete) {
            await api.delete(
                `/dashboard/${confirmDelete.type}/${confirmDelete.id}`
            );
            setConfirmDelete(null);
            loadData();
        }
    };

    const saveEdit = async (id, type) => {
        await api.patch(`/dashboard/${type}/${id}`, {
            startDate: editDates[id].startDate,
            endDate: editDates[id].endDate,
        });
        setEditingId(null);
        setEditDates((prev) => {
            const updated = { ...prev };
            delete updated[id];
            return updated;
        });
        loadData();
    };

    const getUserName = (userId) => {
        const user = users.find((u) => u._id === userId);
        return user ? user.name : "Unbekannt";
    };

    const filterData = (list, userId, year) =>
        list.filter(
            (req) =>
                (!userId || req.userId === userId) &&
                (!year ||
                    new Date(req.startDate).getFullYear().toString() === year)
        );

    const renderTable = (requests, type) => {
        const isVacation = type === "vacation";
        const filtered = filterData(
            requests,
            isVacation ? selectedUser : selectedSickUser,
            isVacation ? selectedYear : selectedSickYear
        );
        const rowsToShow = isVacation
            ? showAllVacation
                ? filtered
                : filtered.slice(0, 10)
            : showAllSickness
            ? filtered
            : filtered.slice(0, 10);

        return (
            <table className="w-full text-sm text-left text-gray-500">
                <thead className="bg-gray-200">
                    <tr>
                        <th className="px-6 py-3 text-center">Name</th>
                        <th className="px-6 py-3 text-center">Start</th>
                        <th className="px-6 py-3 text-center">Ende</th>
                        <th className="px-6 py-3 text-center">Status</th>
                        <th className="px-6 py-3 text-center">Aktion</th>
                    </tr>
                </thead>
                <tbody>
                    {rowsToShow.map((req) => (
                        <tr key={req._id} className="bg-gray-100">
                            <td className="py-4 px-6 text-center">
                                {getUserName(req.userId)}
                            </td>
                            <td className="py-4 px-6 text-center">
                                {editingId === req._id ? (
                                    <input
                                        type="date"
                                        value={
                                            editDates[req._id]?.startDate || ""
                                        }
                                        onChange={(e) =>
                                            setEditDates((prev) => ({
                                                ...prev,
                                                [req._id]: {
                                                    ...prev[req._id],
                                                    startDate: e.target.value,
                                                },
                                            }))
                                        }
                                        className="border p-1 rounded"
                                    />
                                ) : (
                                    new Date(req.startDate).toLocaleDateString()
                                )}
                            </td>
                            <td className="py-4 px-6 text-center">
                                {editingId === req._id ? (
                                    <input
                                        type="date"
                                        value={
                                            editDates[req._id]?.endDate || ""
                                        }
                                        onChange={(e) =>
                                            setEditDates((prev) => ({
                                                ...prev,
                                                [req._id]: {
                                                    ...prev[req._id],
                                                    endDate: e.target.value,
                                                },
                                            }))
                                        }
                                        className="border p-1 rounded"
                                    />
                                ) : (
                                    new Date(req.endDate).toLocaleDateString()
                                )}
                            </td>
                            <td className="py-4 px-6 text-center text-sm font-semibold">
                                <span
                                    className={
                                        req.status === "approved"
                                            ? "text-green-500"
                                            : req.status === "pending"
                                            ? "text-yellow-600"
                                            : "text-red-500"
                                    }
                                >
                                    {req.status}
                                </span>
                            </td>
                            <td className="py-4 px-6 text-center space-x-2">
                                {editingId === req._id ? (
                                    <>
                                        <button
                                            onClick={() =>
                                                saveEdit(req._id, type)
                                            }
                                            className="bg-blue-600 text-white px-2 py-1 rounded"
                                        >
                                            Speichern
                                        </button>
                                        <button
                                            onClick={() => setEditingId(null)}
                                            className="bg-gray-400 text-white px-2 py-1 rounded"
                                        >
                                            Abbrechen
                                        </button>
                                    </>
                                ) : (
                                    <>
                                        <button
                                            onClick={() => {
                                                setEditingId(req._id);
                                                setEditDates((prev) => ({
                                                    ...prev,
                                                    [req._id]: {
                                                        startDate:
                                                            req.startDate.slice(
                                                                0,
                                                                10
                                                            ),
                                                        endDate:
                                                            req.endDate.slice(
                                                                0,
                                                                10
                                                            ),
                                                    },
                                                }));
                                            }}
                                            className="bg-yellow-500 text-white px-2 py-1 rounded"
                                        >
                                            Bearbeiten
                                        </button>
                                        <button
                                            onClick={() =>
                                                confirmDeleteRequest(
                                                    req._id,
                                                    type
                                                )
                                            }
                                            className="bg-red-500 text-white px-2 py-1 rounded"
                                        >
                                            Löschen
                                        </button>
                                        {req.status === "pending" && (
                                            <button
                                                onClick={() =>
                                                    approveRequest(
                                                        req._id,
                                                        type
                                                    )
                                                }
                                                className="bg-green-600 text-white px-2 py-1 rounded"
                                            >
                                                Genehmigen
                                            </button>
                                        )}
                                    </>
                                )}
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        );
    };

    return (
        <AppLayout>
            <h2 className="text-2xl font-bold mb-6">
                Anfrageverwaltung Übersicht
            </h2>
            <Card>
                <section className="mb-4">
                    <h3 className="text-lg font-semibold mb-2">Feiertage</h3>
                    <div className="flex gap-4 items-center">
                        <select
                            className="border p-2 rounded"
                            value={selectedRegion}
                            onChange={(e) => setSelectedRegion(e.target.value)}
                        >
                            <option value="AT">Österreich</option>
                            <option value="DE">Deutschland</option>
                            <option value="CH">Schweiz</option>
                            <option value="AL">Albania</option>
                        </select>
                        <input
                            type="number"
                            className="border p-2 rounded w-20"
                            value={holidayYear}
                            onChange={(e) => setHolidayYear(e.target.value)}
                        />
                        <button
                            className="bg-blue-500 text-white px-4 py-2 rounded"
                            onClick={() =>
                                loadHolidays(holidayYear, selectedRegion)
                            }
                        >
                            Laden
                        </button>
                    </div>
                </section>
            </Card>
            <VacationCalendar
               initialYear={parseInt(holidayYear)}
    initialMonth={new Date().getMonth() + 1}
                holidays={holidays}
                vacations={vacationRequests}
                sickness={sicknessRequests}
                users={users}
            />

            <Card>
                <section className="mb-12">
                    <h3 className="text-lg font-semibold mb-4">
                        Urlaub nachtragen
                    </h3>
                    <form
                        onSubmit={handleSubmitVacation}
                        className="flex gap-4 items-center flex-wrap"
                    >
                        <select
                            required
                            className="border p-2 rounded"
                            value={newVacation.user_id}
                            onChange={(e) =>
                                setNewVacation({
                                    ...newVacation,
                                    user_id: e.target.value,
                                })
                            }
                        >
                            <option value="">Benutzer wählen</option>
                            {users.map((u) => (
                                <option key={u._id} value={u._id}>
                                    {u.name}
                                </option>
                            ))}
                        </select>
                        <input
                            type="date"
                            required
                            className="border p-2 rounded"
                            value={newVacation.start_date}
                            onChange={(e) =>
                                setNewVacation({
                                    ...newVacation,
                                    start_date: e.target.value,
                                })
                            }
                        />
                        <input
                            type="date"
                            required
                            className="border p-2 rounded"
                            value={newVacation.end_date}
                            onChange={(e) =>
                                setNewVacation({
                                    ...newVacation,
                                    end_date: e.target.value,
                                })
                            }
                        />
                        <button
                            type="submit"
                            className="bg-green-600 text-white px-4 py-2 rounded"
                        >
                            Eintragen
                        </button>
                    </form>
                </section>
            </Card>

            <Card>
                <section className="mb-12">
                    <h3 className="text-lg font-semibold mb-4">
                        Urlaub-Anträge
                    </h3>
                    {renderTable(vacationRequests, "vacation")}
                    <div className="flex justify-center mt-4">
                        <button
                            className="bg-blue-500 text-white px-4 py-2 rounded"
                            onClick={() => setShowAllVacation((prev) => !prev)}
                        >
                            {showAllVacation
                                ? "Weniger anzeigen"
                                : "Mehr anzeigen"}
                        </button>
                    </div>
                </section>
            </Card>

            <Card>
                <section className="mb-12">
                    <h3 className="text-lg font-semibold mb-4">
                        Krankenstand nachtragen
                    </h3>
                    <form
                        onSubmit={handleSubmitSickness}
                        className="flex gap-4 items-center flex-wrap"
                    >
                        <select
                            required
                            className="border p-2 rounded"
                            value={newSickness.user_id}
                            onChange={(e) =>
                                setNewSickness({
                                    ...newSickness,
                                    user_id: e.target.value,
                                })
                            }
                        >
                            <option value="">Benutzer wählen</option>
                            {users.map((u) => (
                                <option key={u._id} value={u._id}>
                                    {u.name}
                                </option>
                            ))}
                        </select>
                        <input
                            type="date"
                            required
                            className="border p-2 rounded"
                            value={newSickness.start_date}
                            onChange={(e) =>
                                setNewSickness({
                                    ...newSickness,
                                    start_date: e.target.value,
                                })
                            }
                        />
                        <input
                            type="date"
                            required
                            className="border p-2 rounded"
                            value={newSickness.end_date}
                            onChange={(e) =>
                                setNewSickness({
                                    ...newSickness,
                                    end_date: e.target.value,
                                })
                            }
                        />
                        <button
                            type="submit"
                            className="bg-green-600 text-white px-4 py-2 rounded"
                        >
                            Eintragen
                        </button>
                    </form>
                </section>
            </Card>

            <Card>
                <section>
                    <h3 className="text-lg font-semibold mb-4">
                        Krankenstand-Anfragen
                    </h3>
                    {renderTable(sicknessRequests, "sickness")}
                    <div className="flex justify-center mt-4">
                        <button
                            className="bg-blue-500 text-white px-4 py-2 rounded"
                            onClick={() => setShowAllSickness((prev) => !prev)}
                        >
                            {showAllSickness
                                ? "Weniger anzeigen"
                                : "Mehr anzeigen"}
                        </button>
                    </div>
                </section>
            </Card>
            {confirmDelete && (
                <ConfirmModal
                    title="Löschen bestätigen"
                    message="Möchten Sie diesen Eintrag wirklich löschen?"
                    onCancel={() => setConfirmDelete(null)}
                    onConfirm={handleDeleteConfirmed}
                />
            )}
        </AppLayout>
    );
};

export default VacationDashboard;
