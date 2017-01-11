<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
<div class="clearfix"></div>
<div class="col-md-12">
    <div class="row section-bg">
        <div class="container paddingtop80-desktop">
            <h1 class="green"><strong>ICC Board of Directors Panel</strong></h1>
            <div class="bod-login-warpper">
                <h2>Log in using your My ICC username and password</h2>
                <div class="hr-line"></div>
                <form id="loginform" name="loginform" action="<?php echo esc_url( get_site_url()."/userlogin" ); ?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="username-wrapper">
                            <div class="col-md-4 col-xs-4 col-lg-4 col-sm-4 col-500-lft">
                                <span class="fa fa-user green"></span>
                                <span class="usermobile">Username</span>
                            </div>
                            <div class="col-md-8 col-xs-8 col-lg-8 col-sm-8 col-500-rht">
                                <input type="text" id="username" name="username" placeholder="Enter Your Username" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="usermobile" style="color:red;" id="error_username"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="username-wrapper">
                            <div class="col-md-4 col-xs-4 col-lg-4 col-sm-4 col-500-lft">
                                <span class="fa fa-lock green"></span>
                                <span class="usermobile">Password</span>
                            </div>
                            <div class="col-md-8 col-xs-8 col-lg-8 col-sm-8 col-500-rht">
                                <input type="Password" id="user_password" name="user_password" placeholder="Enter Your Password" />
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="usermobile" style="color:red;" id="error_userpassword"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <p class="text-center green font14 margintop5 marginbot20"><a href="javascript:void(0);" class="common">Forgot Your Username or Password?</a></p>
                            <button type="submit" class="btn btn-primary btn-lg btn-block login">Sign in ></button>
                        <p class="text-center margintop10 font14"><label><span class="pull-left marginrht10 normal">Remember me</span> <span class="pull-left"><input type="checkbox" /></span></label></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
