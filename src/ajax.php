<?php
namespace plgELM;


class Ajax
{
    
    static public function Initialization(){
        add_action('wp_ajax_submit_lead', [__CLASS__, 'submit_lead']);
        add_action('wp_ajax_nopriv_submit_lead', [__CLASS__, 'submit_lead']);
        add_action('wp_ajax_get_events', [__CLASS__, 'get_events']);
        add_action('wp_ajax_nopriv_get_events', [__CLASS__, 'get_events']);
        add_action('wp_ajax_get_event_details', [__CLASS__, 'get_event_details']);
        add_action('wp_ajax_nopriv_get_event_details', [__CLASS__, 'get_event_details']);
    }

    static public function submit_lead() {
        $data = array();
        parse_str($_POST['data'], $data);

        $lead_id = wp_insert_post(array(
            'post_type' => 'lead',
            'post_title' => sanitize_text_field($data['name']),
            'post_status' => 'publish',
            'meta_input' => array(
                'event_id' => intval($data['event_id']),
                'phone' => sanitize_text_field($data['phone']),
                'email' => sanitize_email($data['email']),
            ),
        ));

        wp_send_json_success($lead_id);
    }

    static public function get_events() {
        $events = array();
        $query = new \WP_Query(array('post_type' => 'event'));
        
        while ($query->have_posts()) {
            $query->the_post();
            $events[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'start' => get_post_meta(get_the_ID(), 'start_date', true),
                'end' => get_post_meta(get_the_ID(), 'end_date', true),
            );
        }
        
        wp_send_json($events);
    }
    

    static public function get_event_details() {
        $event_id = intval($_POST['event_id']);
        $event = get_post($event_id);

        ob_start();
        $image=get_the_post_thumbnail_url($event_id);
        ?>
        <h2><?php echo esc_html($event->post_title); ?></h2>
        <p><strong>Date:</strong> <?php echo get_post_meta($event_id, 'start_date', true); ?> - <?php echo get_post_meta($event_id, 'end_date', true); ?></p>

        <?php if($image){ ?>
        <img src="<?php echo $image; ?>" alt="<?php echo esc_attr($event->post_title); ?>">
        <?php } ?>
        <form id="lead-form">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <p><input type="text" name="name" placeholder="Name"></p>
            <p><input type="text" name="phone" placeholder="Phone"></p>
            <p><input type="email" name="email" placeholder="Email"></p>
            <p><button type="submit">Sign up</button></p>
        </form>
        <?php
        echo ob_get_clean();
        wp_die();
    }
    


}