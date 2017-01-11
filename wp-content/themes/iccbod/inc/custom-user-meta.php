<?php

/**
 * Adds additional user fields
 */
function additional_user_fields($user) {

//    if ($user == 'add-new-user') {
//        $user = new stdClass();
//        $user->ID = 0;
//    }

//
//    echo get_user_meta($user->ID, 'user_profile_image');
//    echo get_user_meta($user->ID, 'user_address', true);
//    echo get_user_meta($user->ID, 'user_designation', true);
//    echo get_user_meta($user->ID, 'user_phone_number', true);
    ?>

    <h3><?php _e('Additional User Information', 'textdomain'); ?></h3>

    <table class="form-table">  
        <tr class="form-field form-required">
            <th scope="row"><label for="user_meta_image"><?php _e('Profile Image', 'textdomain'); ?> <span class="description">(required)</span></label></th>
            <td>
                <!-- Outputs the image after save -->
                <?php if (get_user_meta($user->ID, 'user_profile_image', true)) { ?>
                    <img src="<?php echo get_user_meta($user->ID, 'user_profile_image', true); ?>" style="width:150px;"><br />
                <?php } ?>
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="user_profile_image" id="user_profile_image" aria-required="true" value="<?php echo get_user_meta($user->ID, 'user_profile_image', true); ?>" class="regular-text" />
                <!-- Outputs the save button -->
            </td>
        </tr>
        <tr>
            <th scope="row"><label for="user_address"><?php _e('Address', 'textdomain'); ?></label></th>
            <td>
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <?php
                $settings = array('media_buttons' => false, 'textarea_rows' => 10, 'editor_class' => 'meeting_editor');
                $content = get_user_meta($user->ID, 'user_address', true);
                wp_editor($content, 'user_address', $settings);
                ?>
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="user_designation"><?php _e('Designation', 'textdomain'); ?><span class="description">(required)</span></label></th>
            <td>
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="user_designation" id="user_designation" aria-required="true" value="<?php echo get_user_meta($user->ID, 'user_designation', true); ?>" class="regular-text" />
            </td>
        </tr>
        <tr class="form-field form-required">
            <th scope="row"><label for="user_phone_number"><?php _e('Phone Number', 'textdomain'); ?><span class="description">(required)</span></label></th>
            <td>
                <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                <input type="text" name="user_phone_number" id="user_phone_number" aria-required="true" value="<?php echo get_user_meta($user->ID, 'user_phone_number', true); ?>" class="regular-text" />
            </td>
        </tr>
    </table><!-- end form-table -->
    <?php
}

function save_additional_user_meta($user_id) {
    add_user_meta($user_id, 'user_profile_image', $_REQUEST['user_profile_image']);
    add_user_meta($user_id, 'user_address', $_REQUEST['user_address']);
    add_user_meta($user_id, 'user_designation', $_REQUEST['user_designation']);
    add_user_meta($user_id, 'user_phone_number', $_REQUEST['user_phone_number']);
//    wp_redirect(admin_url('users.php'));
}

function update_additional_user_meta($user_id) {
    update_user_meta($user_id, 'user_profile_image', $_REQUEST['user_profile_image']);
    update_user_meta($user_id, 'user_address', $_REQUEST['user_address']);
    update_user_meta($user_id, 'user_designation', $_REQUEST['user_designation']);
    update_user_meta($user_id, 'user_phone_number', $_REQUEST['user_phone_number']);
    wp_redirect(admin_url('users.php'));
}

// show additional_user_fields
add_action('show_user_profile', 'additional_user_fields');
add_action('edit_user_profile', 'additional_user_fields');

//no user object in add new form
add_action('user_new_form', 'additional_user_fields');

//save fileds
//add_action('user_register', 'save_additional_user_meta', 10, 1);
//add_action('personal_options_update', 'update_additional_user_meta');
//add_action('edit_user_profile_update', 'update_additional_user_meta');
