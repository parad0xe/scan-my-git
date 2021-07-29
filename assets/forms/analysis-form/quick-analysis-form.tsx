import React, {useState} from "react";

import {render} from "react-dom";
import Recaptcha from "react-recaptcha";
import SubmitButtonComponent from "./submit-button-component";

interface QuickAnalysisFormPropsInterface {
	action: string;
	token: string;
}

const QuickAnalysisForm: React.FC<QuickAnalysisFormPropsInterface> = (
	props
) => {
	const githubUrl = props.action;
	const token = props.token;

	const [isVerified, setIsVerified] = useState(false);
	const verifyCallback = () => {
		setIsVerified(!isVerified);
	};
	const callback = () => {
		return;
	};
	return (
		<>
			<div className="p-4 text-left mt-12">
				<div className="text-center text-white font-extrabold text-xl m-6">
					<h1 className="font-bold text-5xl text-white">Analyse Rapide</h1>
				</div>

				<form method="post" action={githubUrl} className="flex flex-col m-auto items-center">
					<div className="w-full">
						<input type="hidden" name="csrf_token" value={token}/>
						<input
							placeholder="Github URL"
							className="form-input mt-1 block h-10 placeholder-opacity-5 w-full px-4"
							type="text"
							id="github_url"
							name="github_url"
						/>
					</div>
					<div className="w-4/5 mt-4">
						<SubmitButtonComponent
							name="Analyse rapide"
							classes="justify-center py-2 mt-1 border text-white w-full"
							isVerified={isVerified}
						/>
					</div>
				</form>
				<div className="my-8 flex justify-center">
					<Recaptcha
						sitekey="6Le7IL8bAAAAAELg75XwkJTOLJww822VXPx6GPky"
						render="explicit"
						verifyCallback={verifyCallback}
						onloadCallback={callback}
					/>
				</div>
			</div>
		</>
	);
};

class QuickAnalysisFormElement extends HTMLElement {
	connectedCallback() {
		const action = this.dataset.action;
		const token = this.dataset.action;
		render(<QuickAnalysisForm action={action} token={token}/>, this);
	}
}

customElements.define("quick-analysis-form", QuickAnalysisFormElement);
