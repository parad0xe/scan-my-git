import React from "react";
import "./styles/app.css";
import ReactDOM from "react-dom";
import "react";

const App: React.FC = () => {
  return (
    <>
      <p>React App Component</p>
    </>
  );
};

const rootElement = document.querySelector("#app");
ReactDOM.render(<App />, rootElement);
