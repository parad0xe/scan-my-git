import React from "react";
import {render} from 'react-dom';
import stripAnsi from 'strip-ansi';

interface RunnerComponentPropsInterface {
	name: string;
	output: string;
	element: RunnerComponentElement;
}

const RunnerComponent: React.FC<RunnerComponentPropsInterface> = (props) => {
	return (
		<>
			<div className="p-4 text-left mt-12">
				<div>{props.name}</div>
				<pre>{stripAnsi(props.output)}</pre>
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
