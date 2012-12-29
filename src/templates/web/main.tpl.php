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
                min-height: 350px;
            }
            
            #wrapper{
                width:800px;
                margin-left:auto;
                margin-right:auto;
                margin-top:2%;
                background-color: #fff;
                min-height: 500px;
                box-shadow: 0px 0px 10px rgba(0,0,0,0.2)
                    
                
            }
            
            #controls_wrapper{
                padding:10px;
            }
            
            #controls_wrapper a{
                display:inline-block;
                padding:10px;
            }
            
            h1{
                padding:10px;
                background: #6b9fb8;
                color:#fff;
                margin:0px;
            }
            
            h2{
                color: #24b9ff;
            }
            
            input[type=text]{
                width:100%;
                padding:1%;
                border:2px solid #f0f0f0;
            }
            
            span{
                color:red;
                font-size:smaller;
            }
            
            input.error{
                border-color:pink;
            }
            
            #message_box{
                padding:10px;
                background-color:#fff98f;
                color:#d58001;
                margin-bottom: 25px;
            }
        </style>
        <script type="text/javascript">
            function validate()
            {
                var validated = true;
                
                <?php foreach($widgets as $widget): ?>
                <?= $widget['validation'] ?>
                <?php endforeach; ?>
                
                return validated;
            }
            
            function goNext()
            {
                if(validate())
                {
                    var data = '';
                    
                    <?php foreach($widgets as $widget): ?>
                    <?= $widget['get_data'] ?>
                    <?php endforeach; ?>  
                    document.location = "?p=<?= $page_number ?>&h=<?= $hash ?>&a=n&d=" + escape(data);
                }
            }
        </script>
    </head>
    <body>
        <div id="wrapper">
            <h1><?= $banner ?></h1>
            <div id="widgets_wrapper">
                <h2><?= $title ?></h2>
                <?php if($message != ''): ?>
                <div id="message_box">
                    <?= $message ?>
                </div>
                <?php endif; ?>
                <?php foreach($widgets as $widget): ?>
                <div class="widget_wrapper">
                    <?= $widget['html'] ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div id="controls_wrapper">
                <?php if($show_next): ?>
                <a href="#" onclick="goNext()">  Next &gt;  </a>
                <?php endif; ?>
                <?php if($show_back): ?>
                <a href="?p=<?= $prev_page_number ?>&h=<?= $prev_hash ?>&a=n">  Next &gt; </a>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
