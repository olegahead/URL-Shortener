import React from "react";
import './style.css';

export default class extends React.Component {

    state = {
        active_section_index: null
    };

    constructor(props) {
        super(props);
        this.sections = props.sections;
    }

    onSectionChange = (e) => {

        let selected_item_index = e.target.dataset.sectionIndex,
            selected_section_name;

        this.sections.forEach((item, i) => {

            item.active = i == selected_item_index;

            if (item.active) {
                selected_section_name = item.name;
            }

        });

        this.setState({active_section_index: selected_item_index});

        this.props.onSectionChange(selected_section_name);

    };

    render() {
        return (
            <div className="sections-switcher">
                {this.sections.map((item, i) => (
                    <div
                        className={`section-btn ${item.active ? 'active' : ''}`}
                        key={i}
                        onClick={this.onSectionChange}
                        data-section-index={i}
                    >
                        {item.title}
                    </div>
                ))}
            </div>
        );
    }
};