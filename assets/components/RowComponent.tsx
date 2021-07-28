import React, {useEffect, useRef, useState} from "react";
import { render } from "react-dom";
import arrow from './../img/arrow-right.png';
import parse from 'html-react-parser'


interface RowComponentPropsInterface {
	leftText: string;
	rightText: string;
	subText?: string;
	dropdown: boolean;
	element: RowComponentElement;
}


const RowComponent: React.FC<RowComponentPropsInterface> = (
	props
) => {
	const children_container = useRef(null);

	const [active, setActive] = useState(false);

	function dropdownToggle(){
		setActive(!active)
	}

	// useEffect(() => {
	// 	props.element.HTMLNodes.map((item) => {
	// 		children_container.current.innerHTML += item.outerHTML
	// 	})
	// }, [])

	return (
		<>
			<div className={`flex justify-between bg-white ${active ? "active" : ""}`}>
				<div className="left m-4 flex flex-col justify-center">
					<p>{props.leftText}</p>
				</div>
				<div className="right m-4 flex">
					<div className="flex flex-col justify-center">
						<div>{props.rightText}</div>
						{ !props.subText
							? ""
							: <div>{props.subText}</div>
						}
					</div>
					{!props.dropdown
						? ""
						: <img src={arrow} alt="" className={`transform duration-150 ${active ? "rotate-90": ""}`} onClick={dropdownToggle}/>
					}
				</div>
			</div>
			{!props.dropdown
				? ""
				:	<div ref={children_container} className={`bg-gray-300 min-h-10 ${active ? "" : "hidden"}`}>{props.element.HTMLNodes.map(item => parse(item))}</div>
			}
		</>
	);
};

// const defaultProps: Partial<RowComponentPropsInterface> = {
// }
// RowComponent.defaultProps = defaultProps;


class RowComponentElement extends HTMLElement {
	HTMLNodes: Array<string>;

	constructor(){
		super();
		this.classList.add("w-9/12");
		this.classList.add("test");
		this.HTMLNodes = Array.from([...this.children]).map(item => item.outerHTML)
	}

	connectedCallback() {
		const leftText = this.getAttribute("leftText");
		const rightText = this.getAttribute("rightText");
		const subText = this.getAttribute("subText");
		const dropdown = (this.getAttribute("dropdown") !== null);
		render(<RowComponent leftText={leftText} rightText={rightText} subText={subText} dropdown={dropdown} element={this}/>, this);
	}
}

customElements.define("row-component", RowComponentElement);
