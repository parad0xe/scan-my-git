import React, {useEffect, useRef, useState} from "react";
import { render } from "react-dom";

interface LoadingCirclePropsInterface {
	width?: string;
	bgcolor?: string;
	fcolor?: string;
	start?: string;
	progress: string;
	element: LoadingCircleElement;
	stroke?: string;
	text?: string;
}

const LoadingCircle: React.FC<LoadingCirclePropsInterface> = (
	props
) => {
	const width = parseInt(props.width, 10);
	const stroke = parseInt(props.stroke, 10);
	const start = parseInt(props.start, 10);
	const [progress, setProgress] = useState(parseInt(props.progress, 10));
	const [text, setText] = useState(props.text);

	const canvas = useRef(null);

	const refreshProgressCircle = () => {
		const ctx = canvas.current.getContext('2d');
		ctx.beginPath();
		ctx.arc(width/2, width/2, width/2-stroke, start*(2*Math.PI)/360 , progress * (2 * Math.PI) / 100, false);
		ctx.strokeStyle = props.fcolor;
		ctx.stroke();
	}

	useEffect(() => {
		setProgress(progress)
		refreshProgressCircle()
		//TODO: lerp
	}, [progress]);

	useEffect(()=> {
		// setProgress(progress)
		//TODO: display text
	}, [text])

	useEffect(()=>{
		props.element.setProgress = setProgress

		const ctx = canvas.current.getContext('2d');

		ctx.lineWidth = stroke;
		ctx.lineCap = "round";

		//background
		ctx.beginPath();
		ctx.arc(width/2, width/2, width/2-stroke, start*(2*Math.PI)/360, 2 * Math.PI, false);
		ctx.strokeStyle = props.bgcolor;
		ctx.stroke();

		refreshProgressCircle()
	}, [])

	return (
		<>
			<canvas ref={canvas} width={width} height={width}>
			</canvas>
		</>
	)
};


class LoadingCircleElement extends HTMLElement {
	setProgress: CallableFunction;
	setText: CallableFunction;

	connectedCallback() {
		const width = this.getAttribute("width") ?? "200";
		const bgcolor = this.getAttribute("bgcolor") ?? "#009BBD";
		const fcolor = this.getAttribute("fcolor") ?? "#006D85";
		const start = this.getAttribute("start") ?? "0";
		const stroke = this.getAttribute("stroke") ?? "10";
		const progress = this.getAttribute("progress") ?? "0";
		const text = this.getAttribute("text") ?? "";
		render(<LoadingCircle width={width} bgcolor={bgcolor} fcolor={fcolor} stroke={stroke} text={text} start={start} progress={progress} element={this} />, this);
	}
}

customElements.define("loading-circle", LoadingCircleElement);
