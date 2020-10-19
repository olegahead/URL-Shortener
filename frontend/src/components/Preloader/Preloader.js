import React from "react";
import './style.css';
import preloader_gif from './preloader2.gif';

export default class extends React.Component {

    render() {
        return (
            <div className="preloader">
                <img width="70" src={preloader_gif} />
            </div>
        );
    }
};