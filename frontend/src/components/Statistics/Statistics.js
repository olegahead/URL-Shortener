import React from "react";
import './style.css';
import StatisticItem from '../StatisticItem/StatisticItem';

export default class extends React.Component {

    statistics = [
        {
            title: 'За сегодня',
            name: 'today',
        },
        {
            title: 'За последние 7 дней',
            name: 'last_7_days',
        },
        {
            title: 'За последние 30 дней',
            name: 'total',
        },

    ];

    render() {

        return (
            <div className="statistics-container">
                <div className="title">Статистика посещений</div>

                {this.statistics.map((descriptor, i) =>
                    <StatisticItem title={descriptor.title} value={this.props.data[descriptor.name]} key={i} />
                )}

            </div>
        );
    }
};