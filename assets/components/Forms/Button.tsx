import * as React from "react";

interface ButtonPropsInterface {
  name: string;
  classes: string;
  isVerified: boolean;
}

const Button: React.FC<ButtonPropsInterface> = ({
  name,
  classes,
  isVerified,
}) => {
  return (
    <>
      <button type="submit" className={classes} disabled={!isVerified}>
        {name}
      </button>
    </>
  );
};

export default Button;