<?php
/**
 * The sidebar containing the main widget area
 *
 * If no active widgets are in the sidebar, hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
    <div class="row accordion boxshow-bot">
        <div class="col-md-12 font18 gray888 navigation-block">NAVIGATION</div>
        <div class="clearfix"></div>
        <ul id="accordion">
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/calender-icon.png" />Calendar<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Photoshop</a></li>
                    <li><a href="javascript:void(0);">HTML</a></li>
                    <li><a href="javascript:void(0);">CSS</a></li>
                </ul>
            </li>
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/meeting-icon.png" />Meetings<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">My Meetings</a></li>
                    <li><a href="javascript:void(0);">Upcoming Meetings</a></li>
                    <li><a href="javascript:void(0);">Meeting Documents</a></li>
                    <li><a href="javascript:void(0);">Meeting Minutes</a></li>
                </ul>
            </li>
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/library-icon.png" />Library<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Meeting Documents</a></li>
                    <li><a href="javascript:void(0);">Manuals</a></li>
                    <li><a href="javascript:void(0);">Talking Points</a></li>
                    <li><a href="javascript:void(0);">Financial</a></li>
                    <li><a href="javascript:void(0);">Travel &amp; Expenses</a></li>
                    <li><a href="javascript:void(0);">Miscellaneous</a></li>
                </ul>
            </li>
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/bulletin-icon.png" />Bulletin<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Google</a></li>
                    <li><a href="javascript:void(0);">Bing</a></li>
                    <li><a href="javascript:void(0);">Yahoo</a></li>
                </ul>
            </li>
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/survey-icon.png" />Surveys<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Google</a></li>
                    <li><a href="javascript:void(0);">Bing</a></li>
                    <li><a href="javascript:void(0);">Yahoo</a></li>
                </ul>
            </li>
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/member-icon.png" />Members<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Profile</a></li>
                </ul>
            </li>
            <li>
                <div class="link"><img src="<?php echo get_template_directory_uri(); ?>/images/update-icon.png" />Updates<i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Google</a></li>
                    <li><a href="javascript:void(0);">Bing</a></li>
                    <li><a href="javascript:void(0);">Yahoo</a></li>
                </ul>
            </li>

        </ul>
    </div>
    <div class="row accordion boxshow-bot margintop50">
        <div class="col-md-12 font18 gray888 navigation-block">ADDITIONAL LINKS</div>
        <div class="clearfix"></div>
        <ul id="accordion">
            <li class="open">
                <div class="link font15">Chapter Visit Materials <i class="fa fa-angle-down"></i></div>
                <ul class="submenu">
                    <li><a href="javascript:void(0);">Google</a></li>
                    <li><a href="javascript:void(0);">Bing</a></li>
                    <li><a href="javascript:void(0);">Yahoo</a></li>
                </ul>
            </li>
            <li>
                <div class="link font15">Talking Points</div></li>
            <li>
                <div class="link font15">Help</div></li>
        </ul>
    </div>
</div>