<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/grid-view.css" media="screen, projection" />                
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css?v6" />        
        <link rel="icon" type="image/png" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.png" />

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <script type="text/javascript">
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', 'UA-12713891-6']);
            _gaq.push(['_trackPageview']);
            (function() {
                var ga = document.createElement('script');
                ga.type = 'text/javascript';
                ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0];
                s.parentNode.insertBefore(ga, s);
            })();
        </script>
        <style>
            body{
                
                background: rgb(6,6,7); /* Old browsers */
                background: -moz-linear-gradient(top, rgba(6,6,7,1) 0%, rgba(14,47,81,1) 87%); /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(6,6,7,1)), color-stop(87%,rgba(14,47,81,1))); /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top, rgba(6,6,7,1) 0%,rgba(14,47,81,1) 87%); /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top, rgba(6,6,7,1) 0%,rgba(14,47,81,1) 87%); /* Opera 11.10+ */
                background: -ms-linear-gradient(top, rgba(6,6,7,1) 0%,rgba(14,47,81,1) 87%); /* IE10+ */
                background: linear-gradient(to bottom, rgba(6,6,7,1) 0%,rgba(14,47,81,1) 87%); /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#060607', endColorstr='#0e2f51',GradientType=0 ); /* IE6-9 */
                background-repeat: no-repeat;
                background-color: rgba(14,47,81,1); 
            }
        </style>
    </head>

    <body>
        <div class="page_content">
            <?php echo $content; ?>
        </div>

    </body>
</html>
