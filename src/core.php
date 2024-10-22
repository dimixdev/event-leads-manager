<?php
namespace plgELM;


class Core
{
	static private string $assets_url=plgELM_URL . 'assets/';

	static public function Initialization(){
		self::Include();
        Ajax::Initialization();
        Post::Initialization();
        self::enqueue_css_js();
	}

    static public function Include(){
        include_once(__DIR__ . '/post.php');
        include_once(__DIR__ . '/ajax.php');
    }

    static public function enqueue_css_js(){

    	if(is_admin())return false;

		wp_enqueue_script('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js', array('jquery'), null, true);

    	wp_enqueue_style(
            'event-leads-manager-css',
            self::$assets_url . 'style.css',
            null,
            plgELM_VAERISON
        );

        wp_enqueue_script(
            'event-leads-manager-js',
            self::$assets_url. 'script.js',
            array( 'jquery', 'fullcalendar' ),
            plgELM_VAERISON,
            true
        );

        wp_localize_script('jquery', 'my_ajax_obj', [
            'ajaxurl' => admin_url('admin-ajax.php')
        ]);
    }


}

Core::Initialization();