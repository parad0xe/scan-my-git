import React, {useEffect, useRef, useState} from "react";
import {render} from 'react-dom';

interface ButtonComponentPropsInterface {
	label: string;
	link: string;
	shadow: boolean;
	raised: boolean;
	disabled: boolean;
	element: ButtonComponentElement;
}

const ButtonComponent: React.FC<ButtonComponentPropsInterface> = (props) => {
	const $a = useRef(null)
	const [disabled, setDisabled] = useState(props.disabled)

	useEffect(() => {
		props.element.setDisabled = setDisabled
	}, [])

	const handleClick = (e) => {
		if(disabled) {
			e.preventDefault()
			e.stopPropagation()
		}
	}

	return (
		<>
			<a ref={$a} href={props.link} className={`p-2 border-2 ${props.raised ? "border-white bg-blueWallpaper" : "bg-white border-blueWallpaper"} ${disabled ? "cursor-default opacity-70" : ""}`} onClick={handleClick}>
				<button className={`rounded ${props.raised ? "text-white" : "text-blueWallpaper"} ${props.shadow ? "shadow-md" : ""} ${disabled ? "cursor-default" : ""}`}>
					{props.label}
				</button>
			</a>
		</>
	);
};


class ButtonComponentElement extends HTMLElement {
	setDisabled: CallableFunction;

	connectedCallback() {
		const label = this.getAttribute("label");
		const link = this.getAttribute("link");
		const raised = (this.getAttribute("raised") !== null);
		const shadow = (this.getAttribute("shadow") !== null);
		const disabled = (this.getAttribute("disabled") !== null);
		render(<ButtonComponent link={link} label={label} raised={raised} disabled={disabled} shadow={shadow} element={this}/>, this);
	}
}

customElements.define("button-component", ButtonComponentElement);
