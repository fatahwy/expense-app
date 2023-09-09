<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components;

use Yii;
use yii\bootstrap5\Html;
use yii\grid\ActionColumn;

class ButtonActionColumn extends ActionColumn {

    public function init() {
        parent::init();
      
        //custom
        $this->contentOptions = ['style' => 'white-space: nowrap;width:150px;'];
    }

    protected function initDefaultButtons() {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<i class="fa fa-search fa-sm"></i>', $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fas fa-pencil-alt"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-danger btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fa fa-trash fa-sm"></span>', $url, $options);
            };
        }
        if (!isset($this->buttons['print'])) {
            $this->buttons['print'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Cetak'),
                    'aria-label' => Yii::t('yii', 'Cetak'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-sm',
                    'style' => 'margin: 2px;',
                    'onclick' => "print_report('$url'); return false;",
                        ], $this->buttonOptions);
                return Html::a('<span class="fa fa-print fa-sm"></span>', '#', $options);
            };
        }
        if (!isset($this->buttons['process'])) {
            $this->buttons['process'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Process'),
                    'aria-label' => Yii::t('yii', 'Process'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-primary btn-sm',
                    'data-toggle' => 'tooltip',
                    'style' => 'margin: 2px;'
                        ], $this->buttonOptions);
                return Html::a('<span class="fas fa-pencil-alt fa-sm"></span>', $url, $options);
            };
        }
    }

}
