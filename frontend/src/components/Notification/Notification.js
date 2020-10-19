import React from "react";
import './style.css';

export default class extends React.Component {

    render() {

        return (
            <div className={`message-container ${this.props.type}`}>
                <div className="message">{this.props.text}</div>
                {this.props.link && <a href={this.props.link} className="link" target="_blank">{this.props.link}</a>}
            </div>
        )
    }
}