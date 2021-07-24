import React from "react";
import { render } from "react-dom";

const Form: React.FC = () => {
  return (
    <form action="{{ path('context.quick-analysis') }}" method="post">
      <div className="row">
        <div className="col-md-12">
          <div className="form-group">
            <label htmlFor="github_url" className="form-label"></label>
            <input
              id="github_url"
              type="url"
              name="github_url"
              className="form-control"
            />
          </div>
        </div>
        <div className="col-md-12">
          <button type="submit" className="text-green-600">
            Quick Analysis
          </button>
          <button className="mdc-button">
            <span className="mdc-button__ripple"></span>
            <span className="mdc-button__label">Text Button</span>
          </button>
        </div>
      </div>
    </form>
  );
};

class FormComponent extends HTMLElement {
  connectedCallback() {
    render(<Form />, this);
  }
}

customElements.define("form-analysis", FormComponent);
