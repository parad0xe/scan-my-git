import React from "react";

interface TitlePropsInterface {
  name: string;
}
const Title: React.FC<TitlePropsInterface> = ({ name }) => {
  return (
    <div className="text-white font-extrabold text-xl m-6">
      <h2>{name}</h2>
    </div>
  );
};

export default Title;
