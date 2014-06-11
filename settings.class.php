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

    public function create_settings_page() {
        ?>
        <div class="wrap">
        <h2>GIF Animation Preview</h2>
        <form method="post" action="options.php">
        <?php settings_fields( 'type-group' ); ?>
        <?php do_settings_sections( 'gap-setting' ); ?>
        <?php submit_button(); ?>
        </form>
        </div>
        <?php
    }
}
?>