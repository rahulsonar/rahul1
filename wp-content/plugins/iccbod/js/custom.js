/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

(function ($) {

    /* *
     * Google Map Places API 
     * Limit and Usages Documentaion: https://developers.google.com/places/web-service/usage
     * */
    google.maps.event.addDomListener(window, 'load', function (e) {
        //Check if DOM load event element exist in DOM or not
        if (document.getElementById('meet_locations') != null) {
            var places = new google.maps.places.Autocomplete(document.getElementById('meet_locations'));
            google.maps.event.addListener(places, 'place_changed', function () {
                var place = places.getPlace();
                var address = place.formatted_address;
                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                //Update lat long fileds in backend
                $('#meet_lat').val(latitude);
                $('#meet_long').val(longitude);
                e.preventDefault();
            });
        }

    });

    //chosen
    jQuery(document).ready(function () {

        //Localized Admin constatnts object
//        console.log(wp_constants);

        jQuery("#meeting_category_filter").chosen({
            'width': '95%',
            'white-space': 'nowrap',
            disable_search_threshold: 6
        });

        //Export Meeting
        $('.export_meeting_button').click(function (e) {
            e.preventDefault();
            var meet_id = $(this).attr('data-meet-id');
            if (meet_id) {

//                alert(meet_id);
                $.ajax({
                    type: "post",
                    url: ajaxurl,
                    dataType: "json",
                    data: {
                        meet_id: meet_id,
                        _wpnonce: wp_constants._wpnonce,
                        action: 'exportmeetingics'
                    },
                    success: function (r) {
                        if (r[0] == 'true') {
                            console.log(r);
                            //Create ics instance add meeting object and trigger download
                            var cal = ics();
                            cal.addEvent(r[1].subject, r[1].description, r[1].location, r[1].begin, r[1].end);
                            cal.download(r[1].subject + '_' + r[1].begin);

                        } else {
                            $.gritter.add({
                                title: 'Error',
                                text: r[1],
                                class_name: 'growl-black',
                                image: wp_constants.bod_plugin_path + '/images/alert.png',
                                sticky: false,
                                fade_in_speed: 'fast',
                                fade_out_speed: 100,
                                time: 100
                            });
                        }
                    },
                    error: function (errorThrown) {
                        console.log(errorThrown);
                    }
                });

            } else {
                $.gritter.add({
                    title: 'Error',
                    text: 'Error! Invalid Meeting ID',
                    class_name: 'growl-black',
                    image: wp_constants.bod_plugin_path + '/images/alert.png',
                    sticky: false,
                    fade_in_speed: 'fast',
                    fade_out_speed: 100,
                    time: 100
                });
            }
        });
    });

}(jQuery));