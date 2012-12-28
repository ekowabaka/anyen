<html lang="en">
    <head>
        <title><?= $page_title ?></title>
    </head>
    <body>
        <h1><?= $application ?></h1>
        <div id="widgets_wrapper">
            <?php foreach($widgets as $widget): ?>
            <div class="widget_wrapper">
                <?= $widget ?>
            </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>