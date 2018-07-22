<?php
class GAP_Settings_Page
{
    protected $options;
    private static $plugin;

    static function load() {
        $class = __CLASS__;
        return ( self::$plugin ? self::$plugin : ( self::$plugin = new $class() ) );
    }

    protected function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'settings_init' ) );
    }

    public function add_settings_page() {
        add_options_page(
            'GIF Animation Preview - Settings Page',
            'GIF Animation Preview',
            'manage_options',
            'gap-setting',
            array( $this, 'create_settings_page' )
        );
    }

    public function settings_init() {
        register_setting( 'type-group', GAP_TYPE_OPTION_NAME );
        add_settings_section(
            'type-section',
            'Way of Working',
            array( $this, 'type_section_callback' ),
            'gap-setting'
        );
        add_settings_field(
            'type-field',
            'Please Choose a Method',
            array( $this, 'type_field_callback' ),
            'gap-setting',
            'type-section'
        );


        register_setting( 'type-group', GAP_MOBILE_OPTION_NAME );
        add_settings_section(
            'mobile-section',
            null,
            null,
            'gap-setting'
        );
        add_settings_field(
            'mobile-field',
            'Please Choose Mobile View',
            array( $this, 'mobile_field_callback' ),
            'gap-setting',
            'mobile-section'
        );

        register_setting( 'type-group', GAP_EFFECT_OPTION_NAME );
        add_settings_section(
            'effect-section',
            null,
            null,
            'gap-setting'
        );
        add_settings_field(
            'effect-field',
            'Please choose fade intensity',
            array( $this, 'effect_field_callback' ),
            'gap-setting',
            'effect-section'
        );

        register_setting( 'type-group', GAP_HOVER_OPTION_NAME );
        add_settings_section(
            'hover-section',
            null,
            null,
            'gap-setting'
        );
        add_settings_field(
            'hover-field',
            'Please Choose start event',
            array( $this, 'hover_field_callback' ),
            'gap-setting',
            'hover-section'
        );

        register_setting( 'type-group', GAP_METADATA_OPTION_NAME );
        add_settings_section(
            'metadata-section',
            null,
            null,
            'gap-setting'
        );
        add_settings_field(
            'metadata-field',
            'Please Choose metadata action',
            array( $this, 'metadata_field_callback' ),
            'gap-setting',
            'metadata-section'
        );

        register_setting( 'type-group', GAP_THUMBNAIL_OPTION_NAME );
        add_settings_section(
            'thumbnail-section',
            null,
            null,
            'gap-setting'
        );
        add_settings_field(
            'thumbnail-field',
            'Please Choose post thumbnail action',
            array( $this, 'thumbnail_field_callback' ),
            'gap-setting',
            'thumbnail-section'
        );
    }

    public function type_section_callback() {
        ?>
        <p>This plugin update GIF animations to a static preview image with a button on the top in your posts. Click on the image to play the animation, click again to stop it</p>
        <?php
    }

    public function type_field_callback() {
        $type = get_option( GAP_TYPE_OPTION_NAME, GAP_TYPE_ALWAYS_PREVIEW );
        ?>
        <label>
            <input type="radio" name="<?php echo GAP_TYPE_OPTION_NAME; ?>" value="<?php
                echo GAP_TYPE_ALWAYS_PREVIEW; ?>"<?php
                echo GAP_TYPE_ALWAYS_PREVIEW == $type ? ' checked="checked"' : ''; ?> />
            Always Preview<br />
            <p class="description">Update each GIF animation to a static preview image everywhere on your entire site</p><br />
        </label>
        <label>
            <input type="radio" name="<?php echo GAP_TYPE_OPTION_NAME; ?>" value="<?php
                echo GAP_TYPE_LOOP_PREVIEW; ?>"<?php
                echo GAP_TYPE_LOOP_PREVIEW == $type ? ' checked="checked"' : ''; ?> />
            Loop Preview<br />
            <p class="description">Update each GIF animation to a static preview image on loop pages, do lazy load on single post pages</p><br />
        </label>
        <label>
            <input type="radio" name="<?php echo GAP_TYPE_OPTION_NAME; ?>" value="<?php
                echo GAP_TYPE_NEVER_PREVIEW; ?>"<?php
                echo GAP_TYPE_NEVER_PREVIEW == $type ? ' checked="checked"' : ''; ?> />
            Never Preview<br />
            <p class="description">Don't stop animations, lazy load only without play button. Your site's page load is still become faster</p>
        </label>
        <?php
    }

    public function mobile_field_callback() {
        $mobile = get_option( GAP_MOBILE_OPTION_NAME );
        ?>
        <label>
            <input type="checkbox" name="<?php echo GAP_MOBILE_OPTION_NAME; ?>" value="1"<?php
                echo $mobile == 1 ? ' checked="checked"' : ''; ?> />
            Always Preview on mobile browsers<br />
            <p class="description">Tick for prevent auto start on mobile browsers, whatever is your method setting</p><br />
        </label>
        <?php
    }

    public function effect_field_callback() {
        $mobile = get_option( GAP_EFFECT_OPTION_NAME );
        ?>
        <label>
            <input type="checkbox" name="<?php echo GAP_EFFECT_OPTION_NAME; ?>" value="1"<?php
                echo $mobile == 1 ? ' checked="checked"' : ''; ?> />
            Tick for a smooth switch<br />
            <p class="description">Unticked setting give you a quick image replace</p><br />
        </label>
        <?php
    }

    public function hover_field_callback() {
        $mobile = get_option( GAP_HOVER_OPTION_NAME );
        ?>
        <label>
            <input type="checkbox" name="<?php echo GAP_HOVER_OPTION_NAME; ?>" value="1"<?php
                echo $mobile == 1 ? ' checked="checked"' : ''; ?> />
            Start animation on mouse hover<br />
            <p class="description">Move your mouse over an image to starts switch event, like a click (only for desktop)</p><br />
        </label>
        <?php
    }

    public function metadata_field_callback() {
        $mobile = get_option( GAP_METADATA_OPTION_NAME );
        ?>
        <label>
            <input type="checkbox" name="<?php echo GAP_METADATA_OPTION_NAME; ?>" value="1"<?php
                echo $mobile == 1 ? ' checked="checked"' : ''; ?> />
            Do preview images from metadata<br />
            <p class="description">Try to find all gifs in post attached metadata and do the preview with them</p><br />
        </label>
        <?php
    }

    public function thumbnail_field_callback() {
        $mobile = get_option( GAP_THUMBNAIL_OPTION_NAME );
        ?>
        <label>
            <input type="checkbox" name="<?php echo GAP_THUMBNAIL_OPTION_NAME; ?>" value="1"<?php
                echo $mobile == 1 ? ' checked="checked"' : ''; ?> />
            Do preview images for post thumbnail<br />
            <p class="description">If post thumbnail meta-box contains gif preview it</p><br />
        </label>
        <?php
    }

    public function create_settings_page() {
        ?>
        <div class="wrap">
        <h2>GIF Animation Preview</h2>
        <form method="post" action="options.php">
        <?php settings_fields( 'type-group' ); ?>
        <?php do_settings_sections( 'gap-setting' ); ?>
        <?php submit_button(); ?>
        </form>
        <p><strong>Note:</strong> You are able to start this plugin for unhandled images with <strong><i>gapStart();</i></strong> command, useful for infinite scroll plugin or similars</p>
        </div>
        <?php
    }
}
?>