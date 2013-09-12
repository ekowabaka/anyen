<html lang="en">
    <head>
        <title><?= "$banner - $title" ?></title>
        <style>
            body{
                background-color:#f0f0f0;
                font-family: sans;
            }
            
            #widgets_wrapper{
                padding:15px;
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
            
            label{
                color:#c0c0c0;
                font-weight:bold;
            }
            
            input[type=text], input[type=password]{
                width:100%;
                padding:1%;
                border:2px solid #f0f0f0;
                margin-bottom: 15px;
            }
            
            span{
                color:red;
                font-size:smaller;
                display:none;
            }
            
            input.error{
                border-color:pink;
            }
            
            #message_box{
                padding:10px;
                padding-left: 36px;
                margin-bottom: 25px;
                font-size: small;
                font-weight: bold;
                box-shadow: inset 2px 2px 5px rgba(0,0,0,0.2);
                background-repeat: no-repeat;
                background-position: 10px 50%;
            }
            
            
            
            ul.checklist{
                list-style: none;
                padding:10px;
                overflow: auto;
                box-shadow: inset 2px 2px 5px rgba(0,0,0,0.2);
            }
            
            li{
                padding-bottom:6px;
                padding-top:6px;
                padding-right:38px;
                background-repeat: no-repeat;
                background-position: 100% 50%;
                border-bottom:1px solid #f0f0f0;
                font-size:small;
            }
            
            .info{background-color:#fff98f}
            .error{background-color:#fedee4; color:brown}
            .success{background-color:#99ea57}
            
            .success_icon{background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3QkFERU3yla9TgAAAWdJREFUOMut00+LjlEYx/HPcxeyk6ZnyinNQglNWUzTvZDNKH9SkjfAOzhLC6UmkrI4K4Zk7RUofxajlFNjS3YP5SzMQkgaimzOo7t77pjktzrnOud7dV3n/K6RAYXU7sIJhBp6h4cl5o/9u6MeOItlnMf23t3vuIfLJeb1TQlCag/hAfb6s97iVIn51e8EIbUzWMOcv+tnTbJQYv7Q1OC1LcL3MY+duAqj+mDvB3ru6xHOlJg3Qmov4BbGDZa2AD/F2Qofxg3swFLTK/01Nnrwc5wuMX8NqZ3HY+yuZ3NN5yfWcQxH67/DC5wsMX8JqT2IJ5jp2qDBpG5WSsylxLyGBdzF8RLzp5Da/RUe96qbdB9xG67jUon5R8cf+7DaceVU3zBuqj1v11YuYjWkdk+FD+DZAAw3S8yfpz5Yxpu6PoIcUnuuOnN2AJ7gyn+x8rQCJeaXWMSdOjgGhmkFi1N40zT+yzj/AuSjeLbqW7Y6AAAAAElFTkSuQmCC)}
            .error_icon{background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3QkFERsOC9AYyAAAAVNJREFUOMul001qVFEQBeCvn9IRB+JAkIsgZnbBqWQDwR9aNGC7ABFch6ibEIWswFYxRJCYFThWahYCwgMHIqLEFiJOyuZxYwTJmd2qcw7Fqbojf0Ff62lcw7ksfcSbEvGl5Y4a4Vk8xB2MG+5PrON+ifh0wKCv9SJe47x/YxeTEvFhYdDXegbvcAEbOIZJI9zEL9zADi6ViM9dNh+l+AVu41ZOMxRPs/cSy3gAXQZ2N4kn0ZWI+cBkE9OsdcmBe32tpzqsDgK7imd9reOBybREzPtax5jhSnKXsHo8xxniOjb6WtdKxI/M6ARe4XLDXe4OSXrUrLh9L9BlokNsYa1E7PW1LvW1LpWIPdzE24a702E7j+SAGM8xa0y2kjvHdpfnuZ7Fb9gfiCeZySxr+/ie3Kcl4uuRD+nIp7zYQol4jxU8GWTSfqbHWPkjdthq/uc7/wbiqYbEWbV6xAAAAABJRU5ErkJggg==)}
            .info_icon{background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3QkGCSILdb482wAAAQxJREFUOMut070uBHEUBfDfTjYUGsWEQiLoJooNkXiAbYQn0MkUOr1OLG8gkVBM6wlIFPsECirb7SIaMhERzao0/5XNGJEdTnVz7vfNuTUliLPOJNYwE6hHXOZp8lqMrRUSp9HCFsYKsR/IsJenyfO3AnHWWcQFZgP1jo0Qc46JwN9jPU+T268CcdaJcYW5oY7XeZosB/8NGkO+HlbyNHmpB+KgkAxLcdY5Ck0aBd889rFTCwd7Ktn5AWfB3hxabYA+pupoliRDN0+T3bDCakmBcTSjME5VzEf+iChctCp6EdpBJKOij3YU5HlSocBxniZvgxu0cDfK6Dj8TcpdnAZ7Gws/SvlfnqnqO38C0QtSUrQ9/CYAAAAASUVORK5CYII=)}
            
            
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
                <div id="message_box" class='<?= $message_type ?> <?= $message_type ?>_icon'>
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
                <?php if($show_back): ?>
                <a href="?p=<?= $prev_page_number ?>&h=<?= $prev_hash ?>&a=n">  &lt; Back </a>
                <?php endif; ?>
                <?php if($show_next): ?>
                <a href="#" onclick="goNext()">  Next &gt;  </a>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>
