import React, {Component, useState} from "react";
import {render} from "react-dom";


interface LoadingCirclePropsInterface {
	name: string;
	width?: number;
	bgColor?: string;
	fColor?: string;
	start?: string;
	perCent: number;
}
const defaultProps: Partial<LoadingCirclePropsInterface> = {
	width: 100,
	bgColor: "#000000",
	fColor: "#FFFFFF",
	start: "-Math.PI/2"
}

interface LoadingCircleStatesInterface {}

const LoadingCircle: React.FC<LoadingCirclePropsInterface> = (
	props
) => {
	const name = props.name;
	const width = props.width;
	const bgColor = props.bgColor;
	const fColor = props.fColor;
	const start = props.start;
	const perCent = props.perCent;

	return (
		<div>
			<p>{props.name}</p>
		</div>
	);
};

class LoadingCircleElement extends HTMLElement {
	connectedCallback() {
		const name = this.dataset.name;
		const width = parseInt(this.dataset.width,10 );
		const bgColor = this.dataset.bgColor;
		const fColor = this.dataset.fColor;
		const start = this.dataset.start;
		const perCent = parseInt(this.dataset.perCent, 10);
		render(<LoadingCircle name={name} width={width} bgColor={bgColor} fColor={fColor} start={start} perCent={perCent} />, this);
	}
}

customElements.define("loading-circle", LoadingCircleElement);