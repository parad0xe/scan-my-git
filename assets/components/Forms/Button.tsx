import * as React from "react";

const Button: React.FC<{ name: string; classes: string; isVerified: boolean }> =
  ({ name, classes, isVerified }) => {
    return (
      <>
        <button type="submit" className={classes} disabled={!isVerified}>
          {name}
        </button>
      </>
    );
  };

export default Button;
