import React, { useState } from "react";
import AppLayout from "../components/AppLayout"; 

const Dashboard = () => {
  const [showVacReq, setShowVacReq] = useState(false);
  const [showSickReq, setShowSickReq] = useState(false);

  return (
    <AppLayout isAdmin={true}> 
      <div className="py-12">
         <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="flex pb-5">
              <h2 className="p-6 text-gray-900">Vacation Request:</h2>
              <button
                type="button"
                onClick={() => setShowVacReq(!showVacReq)}
                className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-large rounded-lg text-md px-4 py-0.5 mr-2 mb-4 mt-3"
              >
                + Stelle einen Urlaubsantrag
              </button>
            </div>

            {showVacReq && (
              <div className="flex flex-row justify-center pb-5">
                <form method="POST" action="/dashboard/vacation">
                  <label className="mb-2 text-sm font-medium text-gray-900">
                    Start Datum:
                  </label>
                  <input
                    type="date"
                    name="start_date"
                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 mr-10"
                    required
                  />
                  <label className="mb-2 text-sm font-medium text-gray-900">
                    End Datum:
                  </label>
                  <input
                    type="date"
                    name="end_date"
                    className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5"
                    required
                  />
                  <input type="hidden" name="user_id" value="USER_ID" />
                  <button
                    type="submit"
                    className="ml-10 text-white bg-green-600 font-medium rounded-lg text-sm px-4 py-2"
                  >
                    Anfragen
                  </button>
                </form>
              </div>
            )}
          </div>
        </div>

        <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-12">
          <div className="flex pb-5">
            <h2 className="p-6 text-gray-900">Sickness Request:</h2>
            <button
              type="button"
              onClick={() => setShowSickReq(!showSickReq)}
              className="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-large rounded-lg text-md px-4 py-0.5 mr-2 mb-4 mt-3"
            >
              + Stelle eine Krankenstand Anfrage
            </button>
          </div>

          {showSickReq && (
            <div className="flex flex-row justify-center pb-5">
              <form method="POST" action="/dashboard/sickness">
                <label className="mb-2 text-sm font-medium text-gray-900">
                  Start Datum:
                </label>
                <input
                  type="date"
                  name="start_date"
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 mr-10"
                  required
                />
                <label className="mb-2 text-sm font-medium text-gray-900">
                  End Datum:
                </label>
                <input
                  type="date"
                  name="end_date"
                  className="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5"
                  required
                />
                <input type="hidden" name="user_id" value="USER_ID" />
                <button
                  type="submit"
                  className="ml-10 text-white bg-green-600 font-medium rounded-lg text-sm px-4 py-2"
                >
                  Anfragen
                </button>
              </form>
            </div>
          )}
        </div>
      </div>
    </AppLayout>
  );
};

export default Dashboard;
