import React, {useEffect, useRef, useState} from "react";
import { render } from "react-dom";

interface LoadingCircleComponentPropsInterface {
	width?: string;
	bgcolor?: string;
	fcolor?: string;
	start?: string;
	progress: string;
	element: LoadingCircleComponentElement;
	stroke?: string;
	text?: string;
}

const LoadingCircleComponent: React.FC<LoadingCircleComponentPropsInterface> = (
	props
) => {
	const width = parseInt(props.width, 10);
	const stroke = parseInt(props.stroke, 10);
	const start = parseInt(props.start, 10);
	const [progress, setProgress] = useState(parseInt(props.progress, 10));
	const [text, setText] = useState(props.text);
	const canvas = useRef(null);


	const refreshProgressCircle = (ctx: CanvasRenderingContext2D) => {
		ctx.beginPath();
		ctx.arc(width/2, width/2, width/2-stroke, start*(2*Math.PI)/360 , progress * (2 * Math.PI) / 100+start*(2*Math.PI)/360, false);
		ctx.strokeStyle = props.fcolor;
		ctx.stroke();
	}
	const refreshBackgroundCircle = (ctx: CanvasRenderingContext2D) => {
		ctx.beginPath();
		ctx.arc(width/2, width/2, width/2-stroke, (2*Math.PI)/360, 2 * Math.PI, false);
		ctx.strokeStyle = props.bgcolor;
		ctx.stroke();
	}
	const redraw = (ctx: CanvasRenderingContext2D) => {
		ctx.clearRect(0, 0, width, width);
		refreshBackgroundCircle(ctx);
		refreshProgressCircle(ctx);
		refreshText(ctx);
	}
	const refreshText = (ctx: CanvasRenderingContext2D) => {
		if(!text) return
		ctx.font = '48px ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"';
		ctx.textAlign="center";
		ctx.textBaseline = 'middle';
		ctx.fillStyle=props.fcolor;
		ctx.fillText(text, width/2, width/2);
	}

	useEffect(() => {
		const ctx = canvas.current.getContext('2d');
		setProgress(progress)
		refreshProgressCircle(ctx)
		//TODO: lerp
	}, [progress]);

	useEffect(()=> {
		const ctx = canvas.current.getContext('2d');
		setText(text)
		redraw(ctx)
		//TODO: display text
	}, [text])

	useEffect(()=>{
		props.element.setProgress = setProgress
		props.element.setText = setText

		const ctx = canvas.current.getContext('2d');

		ctx.lineWidth = stroke;
		ctx.lineCap = "round";


		refreshBackgroundCircle(ctx)
		refreshProgressCircle(ctx)
		refreshText(ctx)
	}, [])

	return (
		<>
			<canvas aria-label={text} ref={canvas} width={width} height={width}>
			</canvas>
		</>
	)
};


class LoadingCircleComponentElement extends HTMLElement {
	setProgress: CallableFunction;
	setText: CallableFunction;

	constructor(){
		super();
		this.classList.add("m-2");
	}

	connectedCallback() {
		const width = this.getAttribute("width") ?? "200";
		const bgcolor = this.getAttribute("bgcolor") ?? "#009BBD";
		const fcolor = this.getAttribute("fcolor") ?? "#006D85";
		const start = this.getAttribute("start") ?? "-90";
		const stroke = this.getAttribute("stroke") ?? "10";
		const progress = this.getAttribute("progress") ?? "0";
		const text = this.getAttribute("text") ?? "";
		render(<LoadingCircleComponent width={width} bgcolor={bgcolor} fcolor={fcolor} stroke={stroke} text={text} start={start} progress={progress} element={this} />, this);
	}
}

customElements.define("loading-circle-component", LoadingCircleComponentElement);
