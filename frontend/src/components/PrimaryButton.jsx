import React from "react";

const PrimaryButton = ({ children, onClick, type = "button", className = "" }) => (
  <button
    onClick={onClick}
    type={type}
    className={`bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 text-white font-medium rounded-lg text-sm px-4 py-2 ${className}`}
  >
    {children}
  </button>
);

export default PrimaryButton;
