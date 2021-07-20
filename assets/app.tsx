import React from "react";
import "./styles/app.css";
import ReactDOM from "react-dom";

const App: React.FC = () => {
  return (
    <>
      <p>Test</p>
    </>
  );
};

const rootElement = document.querySelector("#app");
ReactDOM.render(<App />, rootElement);
