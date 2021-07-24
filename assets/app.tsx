import React from "react";
import "./styles/app.css";
import ReactDOM from "react-dom";
import "react";
import "./components/FormComponent";

const App: React.FC = () => {
  return (
    <>
      <p className="text-3xl">Hello</p>
    </>
  );
};

const rootElement = document.querySelector("#app");
ReactDOM.render(<App />, rootElement);
