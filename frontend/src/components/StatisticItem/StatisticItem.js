import React from "react";
import './style.css';

export default class extends React.Component {

    render() {
        return (
            <div className="item">
                <div className="count">{this.props.value}</div>
                <div className="counter-description">{this.props.title}</div>
            </div>
        );
    }
};