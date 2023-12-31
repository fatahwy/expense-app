<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\components\Helper;
use app\widgets\Alert;
use kartik\icons\Icon;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Modal;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$linkIcon = 'https://cdn-icons-png.flaticon.com/512/4604/4604286.png';

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);

$this->registerMetaTag(['rel' => 'shortcut icon', 'type' => 'image/png', 'href' => $linkIcon]);
// android
$this->registerMetaTag(['name' => 'application-name', 'content' => Yii::$app->name]);
$this->registerMetaTag(['name' => 'mobile-web-app-capable', 'content' => 'yes']);
$this->registerMetaTag(['name' => 'theme-color', 'content' => '#fff']);
// apple
$this->registerMetaTag(['name' => 'apple-mobile-web-app-capable', 'content' => 'yes']);
$this->registerMetaTag(['name' => 'apple-mobile-web-app-title', 'content' => Yii::$app->name]);
$this->registerMetaTag(['name' => 'apple-mobile-web-app-status-bar-style', 'content' => 'black-translucent']);
$this->registerLinkTag(['rel' => 'apple-touch-icon', 'href' => $linkIcon]);

Icon::map($this);
$isGuest = Helper::isGuest();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => ['class' => 'navbar-expand-md navbar-dark bg-success fixed-top']
        ]);
        if (!$isGuest) {
            $urlLinks = [
                ['label' => 'Transaction', 'url' => ['/transaction/index']],
                ['label' => 'Report', 'url' => ['/report/index']],
                ['label' => 'Bank Account', 'url' => ['/bank/index']],
            ];

            if (Helper::isAdmin()) {
                $urlLinks[] = ['label' => 'Member', 'url' => ['/member/index']];
                $urlLinks[] = ['label' => 'Setting', 'url' => ['/setting/index']];
            }
            $urlLinks[] =
                '<li class="nav-item">'
                . Html::beginForm(['/site/logout'])
                . Html::submitButton('Logout (' . Yii::$app->user->identity->username . ')', ['class' => 'nav-link btn btn-link logout'])
                . Html::endForm()
                . '</li>';

            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => $urlLinks
            ]);
        }
        NavBar::end();
        ?>
    </header>

    <main id="main" class="flex-shrink-0" role="main">
        <div class="container">
            <?php if (!empty($this->params['breadcrumbs']) && !$isGuest) : ?>
                <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
            <?php endif ?>
            <?= Alert::widget() ?>
            <?php
            Modal::begin([
                'id' => 'modal',
                'size' => 'modal-md',
                'title' => '',
            ]);
            echo "<div id='modalContent'></div>";
            Modal::end();

            echo $content;
            ?>
        </div>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; Fath Project <?= date('Y') ?></div>
                <!-- <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div> -->
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>