<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use \kartik\icons\Icon;

$pdfHeader = [
    'L' => [
        'content' => 'asdasdasd',
    ],
    'C' => [
        'content' => 'CENTER CONTENT (HEAD)',
        //        'content' => '',
        'font-size' => 10,
        'font-style' => 'B',
        'font-family' => 'arial',
        'color' => '#333333',
    ],
    'R' => [
        'content' => 'xxxxxxxxxxxxxxxxxxxxxxxxx',
    ],
    //    'line' => true,
];

$pdfFooter = [
    'L' => [
        'content' => '',
    ],
    'C' => [
        'content' => '',
    ],
    //    'R' => [
    //        'content' => 'RIGHT CONTENT (FOOTER)',
    //        'font-size' => 10,
    //        'color' => '#333333',
    //        'font-family' => 'arial',
    //    ],
    //    'line' => true,
];

return [
    'bsVersion' => '5.x',
    'adminEmail' => 'fatahwidiyanto11@gmail.com',
    'senderEmail' => 'noreply@fathproject.site',
    'senderName' => 'Example.com mailer',
    //    'bsDependencyEnabled' => false
    'icon-framework' => Icon::FA,  // Font Awesome Icon framework,
    'kartikConfig' => [
        'fileInput' => [
            'showRemove' => false,
            'showUpload' => false,
            'showCancel' => false,
            'overwriteInitial' => false,
            'previewFileType' => 'image',
            'maxFileSize' => 3 * 1024 * 1024,
            'allowedExtensions' => ['jpg', 'png', 'jpeg'],
        ]
    ],
    'gridConfig' => [
        'autoXlFormat' => true,
        'export' => [
            'skipExportElements' => ['.d-none'],
            'showConfirmAlert' => false,
            'target' => GridView::TARGET_BLANK
        ],
        'exportConfig' => [
            GridView::PDF => [
                'filename' => "download",
                'config' => [
                    'mode' => 'c',
                    'format' => 'A4',
                    'orientation' => 'P',
                    'cssInline' => '.kv-grid-table {font-size:12px;}'
                        . '.table-sm td, .table-sm th {padding: 0px;}'
                        . '.kv-page-summary{background-color: white;}',
                    'methods' => [
                        'SetHeader' => null,
                        'SetFooter' => [
                            ['odd' => $pdfFooter, 'even' => $pdfFooter]
                        ],
                    ],
                ]
            ],
            GridView::EXCEL => [
                'filename' => "download",
            ]
        ],
        'showPageSummary' => true,
        'pageSummaryContainer' => ['class' => 'text-right'],
        'pageSummaryRowOptions' => ['class' => 'kv-page-summary'],
        'pjax' => true,
        'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container']],
        'condensed' => true,
        'resizeStorageKey' => 'expense-' . date("m"),
        'responsiveWrap' => false,
        'panel' => [
            'type' => GridView::TYPE_SUCCESS,
            'footer' => false,
        ],
        'perfectScrollbar' => true,
        'headerRowOptions' => [
            'class' => 'text-center',
        ],
        'panelHeadingTemplate' => '
            <div class="float-left">
                {summary}
            </div>
            <div class="float-right">
                {toolbar}
            </div>
        ',
        'panelBeforeTemplate' => '
            <div class="float-right">
                {export}
            </div>
        ',
        //        {toggleData}
        'panelTemplate' => '
            {panelHeading}
            {items}
            {panelFooter}
        ',
        // {panelBefore}
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
    ],
    'exportConfig' => [
        'filename' => 'download',
        //        'target' => ExportMenu::TARGET_BLANK,
        'pjaxContainerId' => 'kv-pjax-container',
        'showColumnSelector' => false,
        'showConfirmAlert' => false,
        'clearBuffers' => true,
        'exportConfig' => [
            ExportMenu::FORMAT_CSV => false,
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_EXCEL => false,
        ],
    ]
];
