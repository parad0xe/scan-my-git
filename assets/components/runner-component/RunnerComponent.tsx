import React, {useEffect} from "react";
import {render} from 'react-dom';

interface RunnerComponentPropsInterface {
	name: string;
	output: string;
	element: RunnerComponentElement;
}

const RunnerComponent: React.FC<RunnerComponentPropsInterface> = (props) => {
	const output = props.output.replace(/((\\033)|\\e)\[[0-9;]*m/,"")

	useEffect(() => {

	}, [])

	return (
		<>
			<div className="p-4 text-left mt-12">
				<div>{props.name}</div>
				<pre>{output}</pre>
				<div>OK</div>
			</div>
		</>
	);
};


class RunnerComponentElement extends HTMLElement {
	setProgress: CallableFunction;

	connectedCallback() {
		const name = this.getAttribute("name");
		const output = this.getAttribute("output");
		render(<RunnerComponent name={name} output={output} element={this}/>, this);
	}
}

customElements.define("runner-component", RunnerComponentElement);
