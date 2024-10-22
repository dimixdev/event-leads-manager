document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('event-leads-manager-calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: my_ajax_obj.ajaxurl + '?action=get_events',
        eventClick: function(info) {
            jQuery.post(my_ajax_obj.ajaxurl , {
                action: 'get_event_details',
                event_id: info.event.id
            }, function(response) {
                jQuery('#wmc-modal .content').html(response);
                jQuery('#wmc-modal').addClass('open');

                jQuery('#lead-form').on('submit', function(e) {
                    e.preventDefault();
                    var formData = jQuery(this).serialize();
                    jQuery.post(my_ajax_obj.ajaxurl, {
                        action: 'submit_lead',
                        data: formData
                    }, function(response) {
                        jQuery('#wmc-modal').removeClass('open');
                        alert('Application sent successfully!');
                    });
                });


            });
        }
    });
    calendar.render();


});