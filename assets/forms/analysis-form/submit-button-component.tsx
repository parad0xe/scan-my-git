import * as React from "react";

interface SubmitButtonComponentPropsInterface {
	name: string;
	classes: string;
	isVerified: boolean;
}

const SubmitButtonComponent: React.FC<SubmitButtonComponentPropsInterface> = ({name, classes, isVerified}) => {
	return (
		<>
			<button type="submit" className={classes} disabled={!isVerified}>
				{name}
			</button>
		</>
	);
};

export default SubmitButtonComponent;
