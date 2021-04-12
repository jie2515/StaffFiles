<?php

/* @var $this \yii\web\View */
/* @var $content string */


use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use frontend\components\StaffFiles;

AppAsset::register($this);
isset($_SESSION['username']) ? $username = $_SESSION['username'] : $username = null;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Tree', 'url' => ['/staff-files/index']],
    ];

    $menuItems2 = [
        ['label' => 'Manage', 'url' => ['/staff-files/manage']],
    ];


    if(StaffFiles::is_superuser($username) || StaffFiles::is_leader($username)){
        //组长，管理员可以看到Manage页面
            $menuItems = array_merge($menuItems, $menuItems2);
        }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/staff-files/login']];
        $menuItems[] = ['label' => 'Signup', 'url' => ['/staff-files/signup']];
        $menuItems[] = ['label' => 'About', 'url' => ['/staff-files/about']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/staff-files/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="main-footer " style="text-align:center">
    <strong>Copyright &copy; 2019-<?= date('Y') ?> <a href="https://www.dyxnet.com">DYXNET</a>.</strong> All rights
    reserved.
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
