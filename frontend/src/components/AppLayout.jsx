import React from "react";
import Sidebar from "./Sidebar";

const AppLayout = ({ children, isAdmin }) => {
  return (
    <div className="flex">
      <Sidebar isAdmin={isAdmin} />
      <main className="ml-64 w-full min-h-screen bg-gray-100 p-8">{children}</main>
    </div>
  );
};

export default AppLayout;
