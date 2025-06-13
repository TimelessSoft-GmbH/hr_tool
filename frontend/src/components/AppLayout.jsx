import React from "react";
import Sidebar from "./Sidebar";
import { useAuth } from "../context/AuthContext";

const AppLayout = ({ children }) => {
    const { isAdmin } = useAuth();
  
  return (
    <div className="flex">
      <Sidebar isAdmin={isAdmin} />
      <main className="ml-64 w-full min-h-screen bg-gray-100 p-8">{children}</main>
    </div>
  );
};

export default AppLayout;
