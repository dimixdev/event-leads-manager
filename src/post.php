<?php
namespace plgELM;


class Post
{


    
    static public function Initialization(){
        add_action('init', [__CLASS__, 'register_event_post_type']);
        add_action('init', [__CLASS__, 'register_lead_post_type']);
        add_shortcode('event_calendar', [__CLASS__, 'view']);
        add_action('add_meta_boxes', [__CLASS__, 'lead_event_metabox']);
        add_filter('manage_lead_posts_columns', [__CLASS__, 'set_custom_columns']);
        add_action('manage_lead_posts_custom_column', [__CLASS__, 'custom_column_content'], 10, 2);

    }

    static public function register_event_post_type()
    {
        register_post_type('event', [
            'labels'      => array(
                'name'          => __('Events'),
                'singular_name' => __('Event'),
            ),
            'public'      => true,
            'has_archive' => true,
            'supports'    => ['title', 'editor', 'thumbnail', 'custom-fields'],
        ]);
    }

    static public function register_lead_post_type() {
        register_post_type('lead', [
            'labels'      => [
                'name'          => __('Leads'),
                'singular_name' => __('Lead'),
            ],
            'public'      => true,
            'supports'    => ['title', 'custom-fields'],
            'show_in_menu' => true,
        ]);
    }

    static public function lead_event_metabox() {
        add_meta_box(
            'lead_event_metabox',
            'Information about the event',
            [__CLASS__, 'lead_event_metabox_content'],
            'lead'
        );
    }
    

    static public function lead_event_metabox_content($post) {
        $event_id = get_post_meta($post->ID, 'event_id', true);
        $event = get_post($event_id);
        
        if ($event) {
            echo '<p><strong>Event:</strong> ' . esc_html($event->post_title) . '</p>';
        }
    }


    static public function set_custom_columns($columns) {
        $columns['lead_phone'] = 'Phone';
        $columns['lead_email'] = 'Email';
        return $columns;
    }
    

    static public function custom_column_content($column, $post_id) {
        if ($column == 'lead_phone') {
            echo get_post_meta($post_id, 'phone', true);
        }
        if ($column == 'lead_email') {
            echo get_post_meta($post_id, 'email', true);
        }
    }
    

    static public function view(){
        return '<div id="event-leads-manager-calendar"></div><div id="wmc-modal"><div class="content"></div></div>';
    }
    
}


