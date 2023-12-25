<?php

use kartik\export\ExportMenu;
use kartik\grid\GridView;
use kartik\icons\Icon;

$pdfFooter = [
    'L' => [
        'content' => '',
    ],
    'C' => [
        'content' => '',
    ],
];

return [
    'bsVersion' => '5.x',
    'adminEmail' => 'noreply@fathproject.site',
    'senderEmail' => 'noreply@fathproject.site',
    'senderName' => 'Example.com mailer',
    // 'bsDependencyEnabled' => false,
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
        'panelTemplate' => '
            {panelHeading}
            {items}
            {panelFooter}
        ',
        'toolbar' => [
            '{export}',
            '{toggleData}',
        ],
    ],
    'exportConfig' => [
        'filename' => 'download',
        // 'target' => ExportMenu::TARGET_BLANK,
        'pjaxContainerId' => 'kv-pjax-container',
        'showColumnSelector' => false,
        'showConfirmAlert' => false,
        'clearBuffers' => true,
        'exportConfig' => [
            ExportMenu::FORMAT_CSV => false,
            ExportMenu::FORMAT_TEXT => false,
            ExportMenu::FORMAT_HTML => false,
            ExportMenu::FORMAT_EXCEL => false,
            ExportMenu::FORMAT_PDF => false,
        ],
    ]
];
