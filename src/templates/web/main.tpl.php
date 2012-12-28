<html lang="en">
    <head>
        <title><?= "$banner - $title" ?></title>
        <style>
            body{
                background-color:#f0f0f0;
                font-family: sans;
            }
            
            #widgets_wrapper{
                padding:10px;
            }
            
            #wrapper{
                width:800px;
                margin-left:auto;
                margin-right:auto;
                margin-top:2%;
                background-color: #fff;
                min-height: 90%;
                box-shadow: 0px 0px 10px rgba(0,0,0,0.2)
                    
                
            }
            
            h1{
                padding:10px;
                background: #6b9fb8;
                color:#fff;
            }
            
            h2{
                color: #24b9ff;
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <h1><?= $banner ?></h1>
            <div id="widgets_wrapper">
                <h2><?= $title ?></h2>
                <?php foreach($widgets as $widget): ?>
                <div class="widget_wrapper">
                    <?= $widget ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </body>
</html>