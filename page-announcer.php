<?php
/*
Plugin Name: Page announcer
Plugin URI:  http://github.com/limansky/page-announcer
Description: A widget to show a short description for a page, e.g. about yourself box.
Version:     0.2
Author:      Mike Limansky
Author URI:  http://www.limansky.me
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
*/

class PageAnnouncer extends WP_Widget {

    const IMAGE_STYLES = array(
        'circle'            => array('Circle',              'widget_page_announcer_image_circle'),
        'round-corners'     => array('Rounded corners',     'widget_page_announcer_image_round_corners'),
        'none'              => array('No effect',           'widget_page_announcer_image')
    );

    function __construct() {
        parent::__construct(
            'page_announcer_widget',
            __('Page Announcer', 'page_announcer'),
            array(
                'description' => __('Shows announce for a page as a text and image. E.g. to about page.', 'page_announcer')
            )
        );

        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_style('page_announcer_widget_css', plugins_url('page-announcer.css', __FILE__));
        });

        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_script('media-upload');
            wp_enqueue_media();
            wp_enqueue_script('page_announcer_widget_admin_js', plugins_url('page-announcer-admin.js', __FILE__));
        });
    }

    function form($instance) {
        if ($instance) {
            $text = esc_textarea($instance['text']);
            $link = esc_attr($instance['link']);
            $title = esc_attr($instance['title']);
            $image = esc_attr($instance['image']);
            $image_style = esc_attr($instance['image_style']);
        } else {
            $title = '';
            $link = '';
            $image = '';
            $text = '';
            $image_style = 'circle';
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
            <button class="button button-primary page-announcer-load-image-button">Upload</button>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('image_style'); ?>"><?php _e('Image style:', page_announcer);?></label>
            <select class="widefat" id="<?php echo $this->get_field_id('image_style'); ?>" name=<?php echo $this->get_field_name('image_style'); ?>" >
                <?php
                    foreach (self::IMAGE_STYLES as $style => $meta) {
                        echo '<option value="' . $style . '"';
                        if ($image_style == $style) echo ' selected ';
                        echo '>' . __($meta[0], page_announcer) . '</option>';
                    }
                ?>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Target page:', page_announcer);?></label>
            <?php
                wp_dropdown_pages(array(
                    'id' => $this->get_field_id('link'),
                    'name' => $this->get_field_name('link'),
                    'selected' => $link,
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
        $instance['image_style'] = $new_instance['image_style'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args);
        echo $before_widget;
        $title = apply_filters('widget_title', $instance['title']);
        $text = $instance['text'];
        $image = $instance['image'];
        $image_style = $instance['image_style'];
        $link = get_page_link($instance['link']);

        if ($title) {
            echo $before_title . $title . $after_title;
        }

        if ($link) echo '<a href="' . esc_url($link) .'" class="widget_page_announcer_link">';
        if ($image) {
            $style = 'widget_page_announcer_image';
            if ($image_style && self::IMAGE_STYLES[$image_style]) {
                $style = self::IMAGE_STYLES[$image_style][1];
            }
            echo '<img src="' . esc_url($image) . '" class="' . $style .'">';
        }
        if ($text) echo '<p class="widget_page_announcer_text">' . $text . '</p>';
        if ($link) echo '</a>';

        ?>
        <?php
        echo $after_widget;
    }
}


add_action('widgets_init', function() {
    register_widget('PageAnnouncer');
});

?>
