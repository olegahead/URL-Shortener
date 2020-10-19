import React from "react";
import DatePicker, { registerLocale, setDefaultLocale } from 'react-datepicker';
import ru from 'date-fns/locale/ru';
import './style.css';
import "react-datepicker/dist/react-datepicker.css";

registerLocale('ru', ru);

export default class extends React.Component {

    state = {
        link: '',
        date: null
    };

    onSubmit = (e) => {

        e.preventDefault();

        let data = {
            link: this.state.link
        };

        if (this.props.showDateField && this.state.date) {
            data['ttl'] = Math.round(this.state.date.getTime() / 1000);
        }

        this.props.onSendTriggered(data);
    };

    onDateChange = (date) => {
        this.setState({date});
    };

    onLinkChange = (e) => {
        this.setState({link: e.target.value});
    };

    render() {

        return (
            <form className="link-form-container" onSubmit={this.onSubmit}>
                <input
                    placeholder="Укажите ссылку"
                    id="url_input"
                    className="url-input"
                    type="text"
                    onChange={this.onLinkChange}
                    value={this.state.link}
                    required
                    pattern="^https?://.+"
                />

                {this.props.showDateField && <DatePicker
                    className="ttl-input"
                    placeholderText="Активна до..."
                    dateFormat="yyyy/MM/dd HH:mm"
                    timeInputLabel="Время:"
                    showTimeInput
                    minDate={new Date()}
                    selected={this.state.date}
                    onChange={this.onDateChange}
                    locale="ru"
                    required
                />}

                <input type="submit" className="send-link-btn" value="Отправить" />
            </form>
        );

    }

}