<div class="wrap">
    <h1 id="add-new-user">Edit Meeting</h1>
    <!--<p>Create a meeting.</p>-->
    <form id="editmeetingform" action="" class="form-horizontal" method="post" enctype="multipart/form-data">
        <table class="form-table">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('editmeeting'); ?>">
            <input type="hidden" name="action" value="editmeeting">
            <input type="hidden" name="meet_id" value="<?php echo $_GET['meeting']; ?>">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="meet_title" class="dashicons-before dashicons-admin-post"> Title <span class="description">(required)</span></label></th>
                    <td><input name="meet_title" type="text" id="meet_title" maxlength="60" value="<?php echo $meeting->meet_title; ?>" data-bvalidator="required"></td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="bio" class="dashicons-before dashicons-admin-page"> Description <span class="description">(required)</span></label></th>
                    <td>
                        <?php
                        $editor_id = 'meet_description';
                        $settings = array('media_buttons' => false, 'textarea_rows' => 10, 'editor_class' => 'meeting_editor');
                        wp_editor($meeting->meet_description, $editor_id, $settings);
                        ?>
                        <!--<textarea name="meet_description" id="meet_description" rows="10" cols="30" data-bvalidator="required, maxlength[800]"><?php echo $meeting->meet_description; ?></textarea>-->
                    </td>
                </tr> 
                <tr class="form-field form-required">
                    <th scope="row"><label for="meet_start_date" class="dashicons-before dashicons-calendar-alt"> Start Date <span class="description">(required)</span></label></th>
                    <td><input name="meet_start_date" type="text" id="meet_start_date" value="<?php echo Date('Y-m-d', strtotime($meeting->meet_start_date)); ?>" data-bvalidator="required"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="meet_start_time" class="dashicons-before dashicons-clock"> Start Time <span class="description">(required)</span></label></th>
                    <td><input name="meet_start_time" type="text" id="meet_start_time" value="<?php echo $meeting->meet_start_time; ?>" data-bvalidator="required"></td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="meet_end_date" class="dashicons-before dashicons-calendar-alt"> End Date <span class="description">(required)</span></label></th>
                    <td><input name="meet_end_date" type="text" id="meet_end_date" value="<?php echo Date('Y-m-d', strtotime($meeting->meet_end_date)); ?>" data-bvalidator="required"></td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="meet_end_time" class="dashicons-before dashicons-clock"> End Time <span class="description">(required)</span></label></th>
                    <td><input name="meet_end_time" type="text" id="meet_end_time" value="<?php echo $meeting->meet_end_time; ?>" data-bvalidator="required"></td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="meet_locations" class="dashicons-before dashicons-location"> Location <span class="description">(required)</span></label></th>
                    <td>
                        <input name="meet_location" type="text" id="meet_locations" value="<?php echo $meeting->meet_location; ?>" data-bvalidator="required">
                        <input name="meet_lat" type="hidden" id="meet_lat" value="<?php echo $latlong->lat; ?>">
                        <input name="meet_long" type="hidden" id="meet_long" value="<?php echo $latlong->long; ?>">
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="meet_venue" class="dashicons-before dashicons-admin-post"> Venue <span class="description">(required)</span></label></th>
                    <td><input name="meet_venue" type="text" id="meet_venue" maxlength="60" data-bvalidator="required" value="<?php echo $meeting->meet_venue; ?>"></td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="meet_image" class="dashicons-before dashicons-format-image"> Image <span class="description">(required)</span></label></th>
                    <td>
                        <input name="meet_image" type="file" id="meet_image" value="<?php echo $meeting->meet_image; ?>">
                        <?php if ($meeting->meet_image) { ?>
                            <img src="<?php echo content_url() . '/uploads/' . $meeting->meet_image; ?>" style="width:100px;">
                        <?php } ?>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="meet_category" class="dashicons-before dashicons-admin-generic">Category <span class="description">(required)</span></label></th>
                    <td>
                        <select name="meet_category[]" class="chosen-select" id="meet_category" data-bvalidator="required" multiple>
                            <?php
                            foreach ($meeting_categoriesArray as $key => $value) {
                                $selected = in_array($key, $meetingSelectedCategoriesArray) ? 'selected ="selected"' : '';
                                ?>
                                <option value ="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value; ?></option>
                            <?php }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="meet_deleted" class="dashicons-before dashicons-admin-post"> Status <span class="description">(required)</span></label></th>
                    <td>
                        <input class='meet_deleted' type="radio" name="meet_deleted" value="0" <?php echo ($meeting->meet_deleted == 0) ? 'checked' : ''; ?>> Published
                        <input class='meet_deleted' type="radio" name="meet_deleted" value="1" <?php echo ($meeting->meet_deleted == 1) ? 'checked' : ''; ?>> Deleted
                    </td>
                </tr>
            </tbody></table>
        <p class="submit"><input type="submit" id="editmeeting" class="button button-primary" value="Update Meeting"></p>
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            //chosen
            jQuery("select").chosen({
                'width': '95%',
                'white-space': 'nowrap',
                disable_search_threshold: 6
            });

            $('#meet_start_time, #meet_end_time').timepicker({
                timeFormat: "hh:mm tt"
            });

            var dateFormat = "yy-mm-dd",
                    from = $("#meet_start_date")
                    .datepicker({
                        defaultDate: "+1w",
                        changeMonth: true,
                        numberOfMonths: 1,
                        dateFormat: dateFormat
                    })
                    .on("change", function () {
                        to.datepicker("option", "minDate", getDate(this));
                    }),
                    to = $("#meet_end_date").datepicker({
                defaultDate: "+1w",
                changeMonth: true,
                numberOfMonths: 1,
                dateFormat: dateFormat
            })
                    .on("change", function () {
                        from.datepicker("option", "maxDate", getDate(this));
                    });

            function getDate(element) {
                var date;
                try {
                    date = $.datepicker.parseDate(dateFormat, element.value);
                } catch (error) {
                    date = null;
                }

                return date;
            }

            jQuery('#editmeetingform').bValidator({
                validateOn: "keyup",
                position: {x: 'left', y: 'top'},
                validateTillInvalid: true,
                offset: {x: 0, y: 0}
            });

            jQuery('#editmeetingform').on('submit', function (e) {
                e.preventDefault();
                if (jQuery('#editmeetingform').data('bValidator').validate()) {

                    var data = new FormData(jQuery('#editmeetingform')[0]);

                    $.ajax({
                        type: "post",
                        contentType: false,
                        processData: false,
                        url: ajaxurl,
                        dataType: "json",
                        data: data,
                        success: function (r) {
                            // This outputs the result of the ajax request
//                                console.log(r);

                            if (r[0] === 'true') {
                                var notifyclass = 'growl-black';
                                $.gritter.add({
                                    title: 'Success',
                                    text: r[1],
                                    class_name: notifyclass,
                                    image: '<?php echo plugins_url() . "/iccbod/images/alert.png"; ?>',
                                    sticky: false,
                                    fade_in_speed: 'fast',
                                    fade_out_speed: 100,
                                    time: 100,
                                    after_close: function (e, manual_close) {
                                        window.location.href = '<?php menu_page_url('meeting_tt_list', true); ?>';
                                    }
                                });

                            } else {

                                console.log(r);

                                var notifyclass = 'growl-black';
                                //Gritter in action
                                if (r[1].constructor === Array) {
                                    for (p = 0; p < r[1].length; p++) {
                                        $.gritter.add({
                                            title: 'There is a problem!',
                                            text: r[1][p][0],
                                            class_name: notifyclass,
                                            image: '<?php echo plugins_url() . "/iccbod/images/alert.png"; ?>',
                                            sticky: false,
                                            time: ''
                                        });

                                        $('#editmeetingform').data('bValidators').first.showMsg(jQuery('#' + r[1][p][1]), r[1][p][0]);
                                    }
                                } else {

                                    $.gritter.add({
                                        title: 'There is a problem!',
                                        text: r[1],
                                        class_name: notifyclass,
                                        image: '<?php echo plugins_url() . "/iccbod/images/alert.png"; ?>',
                                        sticky: false,
                                        time: ''
                                    });
                                }
                            }
                        },
                        error: function (errorThrown) {
                            console.log(errorThrown);
                        }
                    });
                } else {

                }
            });


        });
    </script>
</div>