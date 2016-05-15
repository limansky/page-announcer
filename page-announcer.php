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
        if ($instance) {
            $text = esc_textarea($instance['text']);
            $link = esc_attr($instance['link']);
            $title = esc_attr($instance['title']);
            $image = esc_attr($instance['image']);
        } else {
            $title = '';
            $link = '';
            $image = '';
            $text = '';
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', page_announcer);?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title;?>" >
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', page_announcer);?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text;?></textarea>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:', page_announcer);?></label>
            <input class="widefat" type="text" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" value="<?php echo $image;?>" >
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Target page:', page_announcer);?></label>
            <?php
                wp_dropdown_pages(array(
                    'id' => $this->get_field_id('link'),
                    'name' => $this->get_field_name('link'),
                    'selected' => $instance['link'],
                    'class' => 'widefat'
                ));
            ?>
        </p>
        <?php
    }

    function update($new_instance, $old_instance) {       
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['text'] = strip_tags($new_instance['text']);
        $instance['link'] = $new_instance['link'];
        $instance['image'] = $new_instance['image'];
        return $instance;
    }

    function widget($args, $instance) {
    }
}

function page_announcer_register() {
    register_widget('PageAnnouncer');
}

add_action('widgets_init', 'page_announcer_register');

?>
