import React, {useEffect, useRef, useState} from "react";
import { render } from "react-dom";
import arrow from './../img/arrow-right.png';
import parse from 'html-react-parser'


interface RowComponentPropsInterface {
	leftText: string;
	rightText?: string;
	subText?: string;
	dropdown: boolean;
	element: RowComponentElement;
	open: boolean;
}


const RowComponent: React.FC<RowComponentPropsInterface> = (
	props
) => {
	const children_container = useRef(null);

	const [active, setActive] = useState(props.open);
	const [text, setText] = useState(props.rightText);
	function dropdownToggle(){
		setActive(!active)
	}

	useEffect(() => {
		setText(text)
	}, [text]);

	useEffect(()=>{
		props.element.setText = setText
	}, [])

	return (
		<>
			<div className={`flex justify-between bg-white rounded-t-3 ${active ? "active" : "rounded-b-3"}`}>
				<div className="left m-4 flex flex-col justify-center">
					<p>{props.leftText}</p>
				</div>
				<div className="right m-4 flex">
					<div className="flex flex-col justify-center">
						<div>{text!==""? parse(text) : text}</div>
						{ !props.subText
							? ""
							: <div>{props.subText}</div>
						}
					</div>
					{!props.dropdown
						? ""
						: <img src={arrow} alt="" className={`transform duration-150 w-px h-auto ${active ? "rotate-90": ""}`} onClick={dropdownToggle}/>
					}
				</div>
			</div>
			{!props.dropdown
				? ""
				:	<div ref={children_container} className={`bg-gray-300 min-h-10 rounded-b-3 p-3 ${active ? "" : "hidden"}`}>{props.element.HTMLNodes.map(item => parse(item))}</div>
			}
		</>
	);
};

class RowComponentElement extends HTMLElement {
	HTMLNodes: Array<string>;

	setText: CallableFunction;

	constructor(){
		super();
		this.classList.add("w-9/12");
		this.classList.add("test");
		this.classList.add("m-2");
		this.HTMLNodes = Array.from([...this.children]).map(item => item.outerHTML)
	}

	connectedCallback() {
		const leftText = this.getAttribute("leftText");
		const rightText = this.getAttribute("rightText") ?? "";
		const subText = this.getAttribute("subText");
		const dropdown = (this.getAttribute("dropdown") !== null);
		const open = (this.getAttribute("open") !== null);
		render(<RowComponent leftText={leftText} rightText={rightText} subText={subText} dropdown={dropdown} open={open} element={this}/>, this);
	}
}

customElements.define("row-component", RowComponentElement);
