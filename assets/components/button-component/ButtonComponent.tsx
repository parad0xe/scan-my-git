import React, {useEffect, useState} from "react";
import {render} from 'react-dom';

interface ButtonComponentPropsInterface {
	label: string;
	link: string;
	shadow: boolean;
	raised: boolean;
}

const ButtonComponent: React.FC<ButtonComponentPropsInterface> = (props) => {
	useEffect(() => {

	}, []);

	const link_class = ["p-2"]
	const button_class = ["rounded"]

	if(props.raised) {
		link_class.push("border-white border-2 bg-blueWallpaper")
		button_class.push("text-white")
	} else {
		link_class.push("bg-white border-2 border-blueWallpaper")
		button_class.push("text-blueWallpaper")
	}

	if(props.shadow) {
		link_class.push("shadow-md")
	}

	return (
		<>
			<a href={props.link} className={link_class.join(" ")}>
				<button className={button_class.join(" ")}>
					{props.label}
				</button>
			</a>
		</>
	);
};


class ButtonComponentElement extends HTMLElement {
	connectedCallback() {
		const label = this.getAttribute("label");
		const link = this.getAttribute("link");
		const raised = (this.getAttribute("raised") !== null);
		const shadow = (this.getAttribute("shadow") !== null);
		render(<ButtonComponent link={link} label={label} raised={raised} shadow={shadow}/>, this);
	}
}

customElements.define("button-component", ButtonComponentElement);
