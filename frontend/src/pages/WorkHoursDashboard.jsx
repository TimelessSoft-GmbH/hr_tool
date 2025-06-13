import React, { useEffect, useState } from "react";
import WorkHoursTable from "../components/WorkHoursTable";
import WorkHoursForm from "../components/WorkHoursForm";
import AppLayout from "../components/AppLayout";
import api from "../utils/api";

const WorkHoursDashboard = () => {
  const [selectedYear, setSelectedYear] = useState(new Date().getFullYear());
  const [users, setUsers] = useState([]);
  const [workHours, setWorkHours] = useState([]);

  useEffect(() => {
    fetchData(selectedYear);
  }, [selectedYear]);

  const fetchData = async (year) => {
    const response = await api.get(`/work-hours`, { params: { year } });
    setUsers(response.data.users);
    setWorkHours(response.data.workHours);
  };

  return (
    <AppLayout>
      <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
          <div className="flex items-center mb-4">
            <h2 className="text-xl font-semibold">Monatsstunden für</h2>
            <select
              value={selectedYear}
              onChange={(e) => setSelectedYear(e.target.value)}
              className="ml-5 border border-gray-300 rounded px-2 py-1"
            >
              {[...Array(new Date().getFullYear() - 2020).keys()].map((i) => {
                const year = new Date().getFullYear() - i;
                return (
                  <option key={year} value={year}>
                    {year}
                  </option>
                );
              })}
            </select>
          </div>

          <WorkHoursTable users={users} workHours={workHours} year={selectedYear} />

          <h2 className="mt-6 underline">Ändern oder Eintragen:</h2>
          <WorkHoursForm users={users} selectedYear={selectedYear} onSuccess={() => fetchData(selectedYear)} />
        </div>
      </div>
    </AppLayout>
  );
};

export default WorkHoursDashboard;
