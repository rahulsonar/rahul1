<div class="wrap">
    <h1 id="add-new-user">Add New Meeting Category</h1>
    <!--<p>Create a meeting.</p>-->
    <form id="createmeetingcategoryform" action="" class="form-horizontal" method="post" enctype="multipart/form-data">
        <table class="form-table">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('addmeetingcategory'); ?>">
            <input type="hidden" name="action" value="addmeetingcategory">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="meca_name" class="dashicons-before dashicons-admin-post"> Name <span class="description">(required)</span></label></th>
                    <td><input name="meca_name" type="text" id="meca_name" maxlength="60" data-bvalidator="required"></td>
                </tr>
            </tbody></table>
        <p class="submit"><input type="submit" id="createmeetingcategory" class="button button-primary" value="Add New Meeting Category"></p>
    </form>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            jQuery('#createmeetingcategoryform').bValidator({
                validateOn: "keyup",
                position: {x: 'left', y: 'top'},
                validateTillInvalid: true,
                offset: {x: 0, y: 0}
            });

            jQuery('#createmeetingcategoryform').on('submit', function (e) {
                e.preventDefault();
                if (jQuery('#createmeetingcategoryform').data('bValidator').validate()) {

                    var data = new FormData(jQuery('#createmeetingcategoryform')[0]);

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
                                        window.location.href = '<?php menu_page_url('meeting-category', true); ?>';
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

                                        $('#createmeetingcategoryform').data('bValidators').first.showMsg(jQuery('#' + r[1][p][1]), r[1][p][0]);
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
