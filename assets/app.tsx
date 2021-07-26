import React from "react";
import "./styles/app.css";
import ReactDOM from "react-dom";
import { Route, Switch, BrowserRouter } from "react-router-dom";
import Layout from "./components/Layout/Layout";
import QuickAnalysisForm from "./components/Forms/AnalysisForm/QuickAnalysisForm";
import Navbar from "./components/Nav/Navbar";

const App: React.FC = () => {
  return (
    <>
      <Layout>
        <Navbar />
        <BrowserRouter>
          <Switch>
            <Route path="/" component={QuickAnalysisForm} />
          </Switch>
        </BrowserRouter>
      </Layout>
    </>
  );
};

const rootElement = document.querySelector("#app");
ReactDOM.render(<App />, rootElement);
