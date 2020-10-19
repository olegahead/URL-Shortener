import React from "react";
import './App.css';
import Preloader from '../Preloader/Preloader';
import LinkForm from '../LinkForm/LinkForm';
import Notification from '../Notification/Notification';
import Statistics from '../Statistics/Statistics';
import SectionsSwitcher from '../SectionsSwitcher/SectionsSwitcher';

export default class extends React.Component {

    state = {
        activeSection: 'add_url',
        showPreloader: false,
        message: {
            visible: false,
            type: null,
            text: null,
            link: null
        },
        statistics: null
    };

    messageTypes = [
        'ok', 'info', 'warning', 'error'
    ];

    apiAccessPoints = {
        add_url: '/api/add',
        statistics: '/api/statistics',
    };

    onSectionChange = (sectionName) => {

        this.setState({
            activeSection: sectionName,
            statistics: null,
            message: {
                visible: false
            }
        });

    };

    onSendLinkForm = async (fields) => {

        this.setState({
            showPreloader: true,
            message: {visible: false}
        });

        let response = await fetch(
            this.apiAccessPoints[this.state.activeSection],
            {
                method: 'POST',
                body: JSON.stringify(fields)
            }
        );

        if (response.ok) {
            let response_body = await response.json();
            this.showMessage(response_body);

            this.setState({
                statistics: this.state.activeSection === 'statistics' ? response_body.payload : null
            });

        }

        this.setState({showPreloader: false});

    };

    showMessage = (responseBody) => {

        if (responseBody.message && responseBody.code !== undefined) {

            this.setState({
                message: {
                    visible: true,
                    type: this.messageTypes[responseBody.code],
                    text: responseBody.message,
                    link: responseBody.payload
                }
            });

        }

    };

    render() {

        return (
            <div className="action-container">
                {this.state.showPreloader && <Preloader />}
                <h1 className="title">Минимизатор URL</h1>
                <p className="description">
                    Это инструмент для сокращения URL-адресов. <br />
                    Создавайте короткие и запоминающиеся ссылки за секунды.
                </p>

                <SectionsSwitcher
                    sections={[
                        {title: 'Добавление URL', name: 'add_url', active: true},
                        {title: 'Статистика', name: 'statistics'},
                    ]}
                    onSectionChange={this.onSectionChange}
                />

                <LinkForm
                    onSendTriggered={this.onSendLinkForm}
                    showDateField={this.state.activeSection === 'add_url'}
                />

                {this.state.message.visible &&
                    <Notification
                        link={this.state.message.link}
                        type={this.state.message.type}
                        text={this.state.message.text}
                    />
                }

                {this.state.statistics
                 && this.state.activeSection === 'statistics'
                    && <Statistics data={this.state.statistics}/>}
            </div>
        );

    }

}