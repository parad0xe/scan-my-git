import * as React from "react";

const Button: React.FC<{
  name: string;
  classes: string;
  isVerified: boolean;
  isSubmitting: boolean;
}> = ({ name, classes, isVerified, isSubmitting }) => {
  return (
    <>
      <button
        type="submit"
        className={classes}
        disabled={!isVerified && isSubmitting}
      >
        {name}
      </button>
    </>
  );
};

export default Button;
