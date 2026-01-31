'use client';

import { useEffect } from "react";
import "../styles/Alert.css";

interface AlertProps {
  message: string;
  type?: "success" | "error";
  onClose?: () => void;
}

export default function Alert({ message, type = "success", onClose }: AlertProps) {
  useEffect(() => {
    const timer = setTimeout(() => {
      if (onClose) onClose();
    }, 3000);
    return () => clearTimeout(timer);
  }, [onClose]);

  return (
    <div className={`alert ${type === "success" ? "alert-success" : "alert-error"}`}>
      {message}
    </div>
  );
}
