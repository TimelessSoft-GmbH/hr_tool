import React from "react";

const monthNames = [
    "Jänner",
    "Februar",
    "März",
    "April",
    "Mai",
    "Juni",
    "Juli",
    "August",
    "September",
    "Oktober",
    "November",
    "Dezember",
];

const WorkHoursTable = ({ users, workHours, year }) => {
    const getHours = (userId, month) =>
        workHours.find(
            (w) =>
                w.user_id === userId &&
                w.month === month &&
                w.year === parseInt(year)
        )?.hours ?? "N/A";

    return (
        <div className="overflow-x-auto mt-4">
            <table className="min-w-full border border-gray-200">
                <thead className="bg-gray-200">
                    <tr>
                        <th className="px-4 py-2 text-center">User</th>
                        {monthNames.map((m, i) => (
                            <th key={i} className="px-4 py-2 text-center">
                                {m.slice(0, 3)}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody>
                    {users.map((user) => (
                        <tr key={user._id} className="bg-gray-50">
                            <td className="px-4 py-2 text-center">
                                {user.name}
                            </td>
                            {Array.from({ length: 12 }, (_, monthIdx) => (
                                <td
                                    key={monthIdx}
                                    className="px-4 py-2 text-center"
                                >
                                    {getHours(user._id, monthIdx + 1)}
                                </td>
                            ))}
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default WorkHoursTable;
