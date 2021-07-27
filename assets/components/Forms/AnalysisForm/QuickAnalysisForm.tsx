import React, {Component} from "react";
import {render} from 'react-dom';
import Title from "../../Widgets/Title";

interface QuickAnalysisFormPropsInterface {
	action: string,
	token: string
}

interface QuickAnalysisFormStatesInterface {}

class QuickAnalysisForm extends Component<QuickAnalysisFormPropsInterface, QuickAnalysisFormStatesInterface> {
	render() {
		return (
			<div className="p-4 text-left mt-12">
				<div className="text-center">
					<Title name="Analyse Rapide"/>
				</div>

				<form action={this.props.action} method="post">
					<input type="hidden" name="csrf_token" value={this.props.token}/>
					<input type="text" name="github_url"/>
				</form>
			</div>
		)
	}
}

class QuickAnalysisFormElement extends HTMLElement {
	connectedCallback() {
		render(<QuickAnalysisForm
			action={this.dataset.action}
			token={this.dataset.token}/>, this
		)
	}
}

customElements.define('quick-analysis-form', QuickAnalysisFormElement)
