import React from "react";

const DataTable = ({ columns, data, renderActions }) => {
  if (!data || data.length === 0) return null;

  return (
    <table className="w-full table-auto text-sm text-left mt-6">
      <thead className="bg-gray-100">
        <tr>
          {columns.map((col) => (
            <th key={col.key} className="px-4 py-2">
              {col.label}
            </th>
          ))}
          {renderActions && <th className="px-4 py-2">Aktionen</th>}
        </tr>
      </thead>
      <tbody>
        {data.map((row) => (
          <tr key={row._id} className="border-t">
            {columns.map((col) => (
              <td key={col.key} className="px-4 py-2">
                {typeof col.format === "function"
                  ? col.format(row[col.key], row)
                  : row[col.key]}
              </td>
            ))}
            {renderActions && (
              <td className="px-4 py-2">{renderActions(row)}</td>
            )}
          </tr>
        ))}
      </tbody>
    </table>
  );
};

export default DataTable;
