import React from "react";

const Title: React.FC<{ name: string }> = ({ name }) => {
  return (
    <div className="text-white font-extrabold text-xl m-6">
      <h2>{name}</h2>
    </div>
  );
};

export default Title;
