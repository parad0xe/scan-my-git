import React from "react";
import { render } from "react-dom";

const Layout: React.FC = ({ children }) => {
  return (
    <>
      <div className="container-fluid mx-auto flex flex-col min-h-screen bg-blueWallpaper">
        {children}
      </div>
    </>
  );
};

export default Layout;
