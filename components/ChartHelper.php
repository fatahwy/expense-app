<?php

namespace app\components;

use app\components\Helper;
use dosamigos\chartjs\ChartJs;
use yii\web\JsExpression;

class ChartHelper
{
    public static function getPie($labels = [], $backgroundColor = [], $data = [], $height = 200)
    {
        if (empty($labels)) {
            return 'No Data';
        }

        $locale = Helper::identity()->client->locale;

        return  ChartJs::widget([
            'type' => 'pie',
            'options' => [
                'maintainAspectRatio' => false,
                'responsive' => true,
                'height' => $height,
            ],
            'clientOptions' => [
                'tooltips' => [
                    'enabled' => true,
                    'mode' => 'nearest',
                    'intersect' => true,
                    'callbacks' => [
                        'label' => new JsExpression('function(tooltipItem, data) {
                            return data.labels[tooltipItem.index] + ": " + toCurrency(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], "' . $locale . '");
                        }'),
                    ],
                ]
            ],
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => "Dataset",
                        'backgroundColor' => $backgroundColor,
                        'data' => $data
                    ],
                ]
            ]
        ]);
    }

    public static function getBar($labels = [], $label = [], $backgroundColor = [], $data = [], $height = 200)
    {
        if (empty($labels)) {
            return 'No Data';
        }

        $locale = Helper::identity()->client->locale;

        $datasets = [];
        foreach ($data as $index => $d) {
            $datasets[] = [
                'label' => $label[$index] ?? '',
                'backgroundColor' => $backgroundColor[$index],
                'data' => $d
            ];
        }

        return  ChartJs::widget([
            'type' => 'bar',
            'options' => [
                'height' => $height,
                'scales' => [
                    'y' => [
                        'beginAtZero' => true
                    ]
                ],
            ],
            'clientOptions' => [
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => false,
                ],
                'tooltips' => [
                    'enabled' => true,
                    'mode' => 'nearest',
                    'intersect' => true,
                    'callbacks' => [
                        'label' => new JsExpression('function(tooltipItem, data) {
                            return data.datasets[tooltipItem.datasetIndex].label + ": " + toCurrency(data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index], "' . $locale . '");
                        }'),
                    ],
                ]
            ],
            'data' => [
                'labels' => $labels,
                'datasets' => $datasets,
            ]
        ]);
    }
}
