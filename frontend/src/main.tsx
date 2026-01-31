import React from "react";
import ReactDOM from "react-dom/client";
import UrlForm from "./components/UrlForm";
import "./styles/UrlForm.css"; // CSS global ou local

ReactDOM.createRoot(document.getElementById("root")!).render(
  <React.StrictMode>
    <UrlForm />
  </React.StrictMode>
);
