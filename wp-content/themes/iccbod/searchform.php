<?php
/**
 * Search Form Template
 */
?>
<form id="search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" style="background-image: url('<?php echo bloginfo('template_url') . "/images/searchicon.png"; ?>'); background-position: 10px 10px; ">
    <div class="input-group">
        <input type="text" class="form-control dashicons dashicons-search" placeholder="Search" value="" name="s">
<!--        <span class="input-group-btn">
            <button type="submit" class="btn btn-template-main"><i class="fa fa-search"></i></button>
        </span>-->
    </div>
</form>

