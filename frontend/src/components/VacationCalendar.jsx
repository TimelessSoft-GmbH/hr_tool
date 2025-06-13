import React, { useState } from "react";
import dayjs from "dayjs";
import weekday from "dayjs/plugin/weekday";
import isSameOrBefore from "dayjs/plugin/isSameOrBefore";
import isSameOrAfter from "dayjs/plugin/isSameOrAfter";
import Card from "./Card";
dayjs.extend(weekday);
dayjs.extend(isSameOrBefore);
dayjs.extend(isSameOrAfter);

const weekdays = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];

const VacationCalendar = ({
  initialYear,
  initialMonth,
  holidays = [],
  vacations = [],
  sickness = [],
  users = [],
}) => {
  const now = dayjs();
  const [selectedDate, setSelectedDate] = useState(
    dayjs(`${initialYear}-${initialMonth}-01`)
  );

  const year = selectedDate.year();
  const month = selectedDate.month();
  const daysInMonth = selectedDate.daysInMonth();
  const firstDayOfWeek = selectedDate.startOf("month").day();
  const adjustedFirstDay = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;

  const currentMonthHolidays = holidays.map((h) => dayjs(h).date().toString());

  const mergeRequests = (requests, type) =>
    requests
      .filter((r) => r.status === "approved")
      .flatMap((r) => {
        const start = dayjs(r.startDate);
        const end = dayjs(r.endDate);
        const user = users?.find((u) => u._id === r.userId);
        const initials = user?.name
          .split(" ")
          .map((n) => n[0])
          .join("")
          .toUpperCase();
        const days = [];
        let date = start;
        while (date.isSameOrBefore(end)) {
          if (date.year() === year && date.month() === month) {
            days.push({
              date: date.date().toString(),
              initials,
              type,
            });
          }
          date = date.add(1, "day");
        }
        return days;
      });

  const currentMonthEntries = [
    ...mergeRequests(vacations, "vacation"),
    ...mergeRequests(sickness, "sickness"),
  ];

  const groupedEntries = currentMonthEntries.reduce((acc, cur) => {
    acc[cur.date] = acc[cur.date] || [];
    acc[cur.date].push({ initials: cur.initials, type: cur.type });
    return acc;
  }, {});

  const prevMonth = () => setSelectedDate((d) => d.subtract(1, "month"));
  const nextMonth = () => setSelectedDate((d) => d.add(1, "month"));
  const jumpToToday = () => setSelectedDate(dayjs());

  let date = 1;
  const rows = [];

  for (let i = 0; i < 6; i++) {
    const cells = [];
    for (let j = 0; j < 7; j++) {
      const cellIndex = i * 7 + j;
      const isCurrentMonth =
        cellIndex >= adjustedFirstDay && date <= daysInMonth;

      const currentDate = date.toString().padStart(2, "0");
      const isHoliday = currentMonthHolidays.includes(currentDate);
      const isWeekend = j === 5 || j === 6;
      const isToday =
        date === now.date() && year === now.year() && month === now.month();

      cells.push(
        <td
          key={j}
          className={`w-20 h-20 border relative align-top text-sm p-1 ${
            isCurrentMonth ? "" : "bg-gray-100"
          } ${isToday ? "bg-red-200" : ""}`}
        >
          {isCurrentMonth && (
            <>
              <div
                className={`absolute top-0 left-0 w-full h-1 ${
                  isHoliday || isWeekend ? "bg-red-600" : ""
                }`}
              ></div>
              <div className="pl-1 pt-4">
                <div className="font-bold">{date}</div>
                {groupedEntries[currentDate] && (
                  <div className="flex flex-wrap">
                    {groupedEntries[currentDate].map((entry, idx) => (
                      <div
                        key={idx}
                        className={`w-6 h-6 rounded-full text-xs text-center leading-6 mr-1 mt-1 ${
                          entry.type === "sickness"
                            ? "bg-red-500 text-white"
                            : "bg-green-500 text-white"
                        }`}
                      >
                        {entry.initials}
                      </div>
                    ))}
                  </div>
                )}
              </div>
            </>
          )}
          {isCurrentMonth && date++}
        </td>
      );
    }
    rows.push(<tr key={i}>{cells}</tr>);
    if (date > daysInMonth) break;
  }

  return (
    <Card>
      <div className="flex justify-between bg-gray-200 px-3 py-2">
        <button onClick={prevMonth} className="text-gray-600 bg-white hover:text-red-800 font-bold">
          &lt;
        </button>
        <span className="text-gray-800 font-bold">
          {selectedDate.format("MMMM YYYY")}
        </span>
        <button onClick={nextMonth} className="text-gray-600 bg-white hover:text-red-800 font-bold">
          &gt;
        </button>
      </div>
      <table className="w-full">
        <thead>
          <tr>
            {weekdays.map((d) => (
              <th key={d} className="py-2 px-1 text-xs text-gray-500">
                {d}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>{rows}</tbody>
      </table>
      <div className="flex justify-center mt-4">
        <button
          onClick={jumpToToday}
          className="bg-gray-400 hover:bg-red-600 text-white font-bold py-2 px-4 rounded"
        >
          Jump to Current Month
        </button>
      </div>
    </Card>
  );
};

export default VacationCalendar;