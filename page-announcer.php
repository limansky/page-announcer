<?php
/*
Plugin Name: Page announcer
Plugin URI:  http://github.com/limansky/page-announcer
Description: A widget to show a short description for a page, e.g. about yourself box.
Version:     0.1
Author:      Mike Limansky
Author URI:  http://www.limansky.me
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

class PageAnnouncer extends WP_Widget {
    function __construct() {
        parent::__construct(
            'page_announcer_widget',
            __('Page Announcer', 'page_announcer'),
            array(
                'description' => __('Shows announce for a page as a text and image. E.g. to about page.', 'page_announcer')
            )
        );
    }

    function form($instance) {
        $defaults = array(
            'text' => ''
        );

        $text = esc_textarea($instance['text']);
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', page_announcer);?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $textarea;?></textarea>
        </p>
        <?php
    }

    function update( $new_instance, $old_instance ) {       
    }

    function widget( $args, $instance ) {
    }
}

function page_announcer_register() {
    register_widget('PageAnnouncer');
}

add_action('widgets_init', 'page_announcer_register');

?>
