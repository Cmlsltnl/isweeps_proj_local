<?php
$speed = '';
if ($tvScreenSetting->slide_speed)
    $speed = $tvScreenSetting->slide_speed;
else
    $speed = 35;

if ($tvScreenSetting->direction == null || $tvScreenSetting->direction == 1)
    $direction = 1;
else
    $direction = 0;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php
        $cs = Yii::app()->clientScript;
        $cs->registerCssFile('/core/webassets/css/preview/webticker.css');
        $cs->registerCoreScript('jquery', CClientScript::POS_HEAD);
        $cs->registerScriptFile('/core/webassets/js/preview/jquery.webticker.js', CClientScript::POS_HEAD);
        ?>
    <!--<script type="text/javascript" src="/core/webassets/js/preview/jquery.min.js"></script>
    <script type="text/javascript" src="/core/webassets/js/preview/jquery.webticker.js"></script>
    <link rel="stylesheet" href="/core/webassets/css/preview/webticker.css" type="text/css" media="screen"> -->


        <style>
            /*@import url(http://fonts.googleapis.com/earlyaccess/amiri.css);font-family: 'Amiri', serif*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/droidarabickufi.css);/*font-family: 'Droid Arabic Kufi', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/droidarabicnaskh.css);/*font-family: 'Droid Arabic Naskh', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/lateef.css);/*font-family: 'Lateef', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/notokufiarabic.css);/*font-family: 'Noto Kufi Arabic', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/notonaskharabic.css);/*font-family: 'Noto Naskh Arabic', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/notosanskufiarabic.css);/*font-family: 'Noto Sans Kufi Arabic', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/scheherazade.css);/*font-family: 'Scheherazade', serif;*/
            /*@import url(http://fonts.googleapis.com/earlyaccess/thabit.css);/*font-family: 'Thabit', serif;*/
            body {
                <?php
                switch ($tvScreenSetting->screen_type) {
                    case 'transparent':
                        echo 'background: rgba(0, 0, 0, 0.0);';
                        break;
                    case 'green':
                        echo 'background-color: #0DAC1A;';
                        // echo 'background: rgba(13, 172, 26, 1);';

                        break;
                    case 'background':
                        echo 'background-image:url("/userimages/tvscreensetting/' . $tvScreenSetting->filename . '") ;background-repeat:repeat-y;';
                        break;
                    default:
                        echo 'background: rgba(0, 0, 0, 0.0);';
                        break;
                }
                ?>
            }
            .dynamicFontStyle {
                font-family: <?php echo ("'".$tvScreenSetting->font_family."'"); ?>;
                font-size: <?php echo ($tvScreenSetting->font_size) ? $tvScreenSetting->font_size : '35'; ?>px;
                color: <?php echo ($tvScreenSetting->font_color) ? $tvScreenSetting->font_color : '#fff'; ?>;
                height: 70px;
                position:relative;
                bottom: <?php echo (empty($tvScreenSetting->offset_y_text)?'0px':$tvScreenSetting->offset_y_text."px"); ?>;
                font-weight: <?php echo (empty($tvScreenSetting->font_weight)?'normal':$tvScreenSetting->font_weight); ?>;
            }
            .avatar {
                position:relative;
                bottom: <?php echo (empty($tvScreenSetting->offset_y_text)?'0px':$tvScreenSetting->offset_y_text."px"); ?>;
            }

            <?php
            $gradientStartColor = ($tvScreenSetting->gradient_start_color) ? $tvScreenSetting->gradient_start_color : '#00c6ff';
            $gradientEndColor = ($tvScreenSetting->gradient_end_color) ? $tvScreenSetting->gradient_end_color : '#fa00ff';
            ?>
            .tickercontainer {
                <?php if ($tvScreenSetting->forebg_filename == ""): ?>
                    background: -webkit-linear-gradient(<?php echo $gradientStartColor . ',' . $gradientEndColor ?>); /* For Safari 5.1 to 6.0 */
                    background: -o-linear-gradient(<?php echo $gradientStartColor . ',' . $gradientEndColor ?>); /* For Opera 11.1 to 12.0 */
                    background: -moz-linear-gradient(<?php echo $gradientStartColor . ',' . $gradientEndColor ?>); /* For Firefox 3.6 to 15 */
                    background: linear-gradient(<?php echo $gradientStartColor . ',' . $gradientEndColor ?>); /* Standard syntax */
                    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $gradientStartColor ?>, endColorstr=<?php echo $gradientEndColor ?>);
                    /* For Internet Explorer 8 */
                    -ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=<?php echo $gradientStartColor ?>, endColorstr=<?php echo $gradientEndColor ?>)";
                <?php else : ?>
                    background-image:url("/userimages/tvscreensetting/<?php echo($tvScreenSetting->forebg_filename) ?>");
                    background-repeat:repeat-y;
                <?php endif; ?>
                bottom: <?php echo (empty($tvScreenSetting->offset_y)?'0px':$tvScreenSetting->offset_y."px"); ?>;
            }
            .avatar img {
                width: 50px;
                height: 50px;
                padding:2px;
            }
        </style>
    </head>
    <body style='margin:0px;'>
        <script type="text/javascript">
            $(function() {


                $("#webticker").webTicker({startEmpty: true, speed:<?php echo $speed ?>, hoverpause: false});
                $("#webticker2").webTicker({duplicate: true, speed: <?php echo $speed ?>, direction: 'right', startEmpty: true, hoverpause: false});

            });
        </script>
        <ul id="<?php echo ($direction == 1) ? 'webticker2' : 'webticker' ?>">
            <?php foreach ($tickers as $t) { ?>
                <li id="item<?php echo $t->id ?>">
                    <table>
                        <tr>
                            <td class="avatar"><img src="<?php echo TickerUtility::getAvatar($t); ?>" alt="avatar"></td>
                            <td class="dynamicFontStyle"><?php echo $t->ticker; ?></td>
                        </tr>
                    </table>
                </li>
            <?php
            }
            if (sizeof($tickers) == 0) {
                ?> <li>
                    <li id="item<?php echo $t->id ?>">
                        <table>
                            <tr>
                                <td class="dynamicFontStyle">There are no tickers approved for this question.</td>
                            </tr>
                        </table>
                    </li>
<?php } ?>
        </ul>
    </body></html>