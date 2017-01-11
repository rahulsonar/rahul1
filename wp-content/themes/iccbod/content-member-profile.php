<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<section>
    <div class="header-bg"></div>
</section>
<section>
    <div class="container">
        <div class="margintop20">
            <div class="col-md-12 bredcrum-box marginbot20 boxshow-bot font12 gray888"><em>You are here</em>: <a href="javascript:void(0);" class="light-green">BOD Website</a> > <a href="javascript:void(0);" class="light-green">Member</a> > Profile</div>
        </div>
        <?php get_sidebar('frontenddashboard'); ?>
        <div class="col-md-9 col-lg-9 col-sm-9 col-xs-12 prf-padding-lft">
            <h1 class="col-md-12 prf-lft-title font18">PROFILE</h1>
            <div class="col-md-12 margintop30 prf-padding-lft">
                <div class="row mob-padding">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="prf-pic"><img src="<?php echo get_template_directory_uri(); ?>/images/profile-img.jpg"></div>
                            <div class="prf-pic-txt">
                                <p class="name-title marginbot0"><strong>John Doe</strong></p>
                                <p class="font18 gray666 marginbot0 lineheight18">Vice President</p>
                                <p class="font18 gray666 marginbot0 lineheight18">State Fire Marshal</p>
                                <p class="font18 gray666 marginbot0 lineheight18">State of Georgia</p>
                            </div>

                        </div>
                        <div class="row margintop40 font14 gray666 text-justify">
                            <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean com ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et ma dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultric pellentesque eu, pretium quis, sem. Nulla consequat massa quis enim. pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In enim just, vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincid Cras dapibus. Vivamus elemen semper nisi. Aenean vulputate eleifend. </p>
                            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quisante. Etiam sit amet orci egeterofaucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donecsodales sagittis magna.</p>

                        </div>
                    </div>
                    <div class="col-md-5 col-xs-12 col-sm-5 mob-remove-padding">
                        <div class="prf-reminder">
                            <h2 class="font16 remove-margin">Board Reminder</h2>
                            <p class="margintop20 text-justify">Lorem ipsum dolor sit amet, consecte adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis.</p>
                            <p><i class="fa fa-envelope-o marginrht10"></i><a href="mailto:jsmith@iccsafe.org" class="white">jsmith@iccsafe.org</a></p>
                            <p><i class="fa fa-phone marginrht10"></i><a href="tel:+1800229933" class="white">888-ICC-SAFE</a></p>
                        </div>
                        <div class="prf-my-meeting margintop30">
                            <h2 class="col-md-12 prf-meeting-title remove-padding font18">MY MEETINGS</h2>
                            <div class="prf-date">
                                <div class="prf-date-in text-center gray666">
                                    <p class="font16">OCT</p>
                                    <p class="font24"><strong>30</strong></p>
                                    <p class="font16">2016</p>
                                </div>
                            </div>
                            <div class="prf-meeting-rht">
                                <p class="text-justify gray666 font11 marginbot0"><em>Kansas City, Missouri</em></p>
                                <p class="font18 light-green marginbot0">Board Meeting</p>
                                <p class="font12 gray666">Board of Directors meeting at the Annual Conference and Public Comment Hearings will discuss the...</p>
                                <a href="javascript:void(0);" class="pull-left view-more font10">VIEW MORE DETAILS <i class="fa fa-long-arrow-right "></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h1 class="col-md-12 prf-lft-title font18">UPCOMING MEETINGS</h1>
            <div class="col-md-12 margintop30">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="prf-upcoming-block">
                                <div class="prf-date">
                                    <div class="prf-date-in text-center gray666">
                                        <p class="font16">OCT</p>
                                        <p class="font24"><strong>30</strong></p>
                                        <p class="font16">2016</p>
                                    </div>
                                </div>
                                <div class="prf-detail">
                                    <span class="prf-blue font16"><strong>Annual Board of Directors Meeting</strong></span>
                                    <span class="pull-right font14 gray666">LOS ANGELES, CA</span>
                                    <span class="pull-right marginrht10"><strong>9:45am</strong></span>
                                    <p class="font14 gray666">Etiam rhoncus. Maecenas tempus, tellus eget cond Vivamus elementum semper nisi.  Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae,  eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus .. </p>
                                    <a href="javascript:void(0);" class="pull-right view-detail font10">VIEW MORE DETAILS <i class="fa fa-long-arrow-right "></i></a>
                                </div>
                                <div class="prf-like text-center">
                                    <p class="font14 gray666">Would you like to attend this meeting?</p>
                                    <p>
                                        <button class="prf-yes-btn">Yes</button>
                                        <button class="prf-yes-btn">No</button>
                                    </p>
                                </div>

                            </div>
                            <div class="prf-upcoming-block">
                                <div class="prf-date">
                                    <div class="prf-date-in text-center gray666">
                                        <p class="font16">OCT</p>
                                        <p class="font24"><strong>30</strong></p>
                                        <p class="font16">2016</p>
                                    </div>
                                </div>
                                <div class="prf-detail">
                                    <span class="prf-blue font16"><strong>Annual Board of Directors Meeting</strong></span>
                                    <span class="pull-right font14 gray666">LOS ANGELES, CA</span>
                                    <span class="pull-right marginrht10"><strong>9:45am</strong></span>
                                    <p class="font14 gray666">Etiam rhoncus. Maecenas tempus, tellus eget cond Vivamus elementum semper nisi.  Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae,  eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus .. </p>
                                    <a href="javascript:void(0);" class="pull-right view-detail font10">VIEW MORE DETAILS <i class="fa fa-long-arrow-right "></i></a>
                                </div>
                                <div class="prf-like text-center">
                                    <p class="font14 gray666">Would you like to attend this meeting?</p>
                                    <p>
                                        <button class="prf-yes-btn">Yes</button>
                                        <button class="prf-yes-btn">No</button>
                                    </p>
                                </div>

                            </div>
                            <div class="prf-upcoming-block">
                                <div class="prf-date">
                                    <div class="prf-date-in text-center gray666">
                                        <p class="font16">OCT</p>
                                        <p class="font24"><strong>30</strong></p>
                                        <p class="font16">2016</p>
                                    </div>
                                </div>
                                <div class="prf-detail">
                                    <span class="prf-blue font16"><strong>Annual Board of Directors Meeting</strong></span>
                                    <span class="pull-right font14 gray666">LOS ANGELES, CA</span>
                                    <span class="pull-right marginrht10"><strong>9:45am</strong></span>
                                    <p class="font14 gray666">Etiam rhoncus. Maecenas tempus, tellus eget cond Vivamus elementum semper nisi.  Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae,  eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus .. </p>
                                    <a href="javascript:void(0);" class="pull-right view-detail font10">VIEW MORE DETAILS <i class="fa fa-long-arrow-right "></i></a>
                                </div>
                                <div class="prf-like text-center">
                                    <p class="font14 gray666">Would you like to attend this meeting?</p>
                                    <p>
                                        <button class="prf-yes-btn">Yes</button>
                                        <button class="prf-yes-btn">No</button>
                                    </p>
                                </div>

                            </div>
                            <div class="prf-upcoming-block">
                                <div class="prf-date">
                                    <div class="prf-date-in text-center gray666">
                                        <p class="font16">OCT</p>
                                        <p class="font24"><strong>30</strong></p>
                                        <p class="font16">2016</p>
                                    </div>
                                </div>
                                <div class="prf-detail">
                                    <span class="prf-blue font16"><strong>Annual Board of Directors Meeting</strong></span>
                                    <span class="pull-right font14 gray666">LOS ANGELES, CA</span>
                                    <span class="pull-right marginrht10"><strong>9:45am</strong></span>
                                    <p class="font14 gray666">Etiam rhoncus. Maecenas tempus, tellus eget cond Vivamus elementum semper nisi.  Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae,  eleifend ac, enim. Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus .. </p>
                                    <a href="javascript:void(0);" class="pull-right view-detail font10">VIEW MORE DETAILS <i class="fa fa-long-arrow-right "></i></a>
                                </div>
                                <div class="prf-like text-center">
                                    <p class="font14 gray666">Would you like to attend this meeting?</p>
                                    <p>
                                        <button class="prf-yes-btn">Yes</button>
                                        <button class="prf-yes-btn">No</button>
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
