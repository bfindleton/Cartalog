<?php
class Cartalog {

    protected $_options;
    protected $_cart = null;

    public function __construct()
    {
        $this->_options = new Cartalog_Options(CARTALOG_OPTIONS_KEY);
    }

    public function install()
    {
        $this->initOptions();
    }

    public function init()
    {
        $this->initOptions();

        $this->_cart = $this->_options->cart_vendor;

        // do something if cart not active
        $plugins = get_option('active_plugins');

        $this->initEnq();

        if(IN_ADMIN) {
            add_action('admin_menu', array($this, 'initAdmin'));
        } else {
            $this->initShortCodes();
        }

        add_action('wp_head', array($this,'cartalogStyles'));
        add_action('wp_ajax_ajax_display', array($this, 'ajax_display_modal'));
        add_action('wp_ajax_nopriv_ajax_display', array($this, 'ajax_display_modal'));
    }

    public function initOptions()
    {
        $version = $this->_options->getOption('version');
        if( null === $version ) {
            $defaults = array(
                'version' => CARTALOG_VERSION,
                'cart_vendor' => 'Cart66',
                'ui_theme'    => 'redmond',
                'category_width' => '710px',
                'category_background' => '#2f8aba',
                'category_color' => '#FFFFFF',
                'item_width' => '200px',
                'item_background' => null,
                'item_color' => '#000000',
                'item_border' => null,
                'item_modal' => null,
                'custom_styles' => null,
                );
        } elseif ( CARTALOG_VERSION !== $version ) {
            $defaults = array(
                'version'             => CARTALOG_VERSION,
                'cart_vendor'         => $this->_options->cart_vendor,
                'ui_theme'            => $this->_options->ui_theme,
                'category_width'      => $this->_options->category_width,
                'category_background' => $this->_options->category_background,
                'category_color'      => $this->_options->category_color,
                'item_width'          => $this->_options->item_width,
                'item_background'     => $this->_options->item_background,
                'item_color'          => $this->_options->item_color,
                'item_border'         => $this->_options->item_border,
                'item_modal'          => $this->_options->item_modal,
                'custom_styles'       => $this->_options->custom_styles,
                );
            $this->_options->delete();
        } else {
            $defaults = $this->_options->getOptions();
        }

        $this->_options->setOptions($defaults);
    }

    public function initEnq()
    {
        if ( '1' === $this->_options->getOption( 'item_modal' ) ) {

            wp_enqueue_script( 'jquery-ui-dialog' );

            wp_enqueue_script(
                    'display_script',
                    CARTALOG_URL . '/js/display_script.js',
                    array( 'jquery' ),
                    '7',
                    true
            );

            wp_localize_script(
                    'display_script',
                    'ajax_object',
                    array(
                        'ajaxurl' => admin_url( 'admin-ajax.php' ),
                        'displayNonce' => wp_create_nonce( 'my_display_nonce' )
                    )
            );

            $ui_theme = $this->_options->getOption('ui_theme');
            $ui_url = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/' . $ui_theme . '/jquery-ui.css';
            
            wp_enqueue_style( 'custom-jquery-ui-dialog', $ui_url );
        }

        $url = CARTALOG_URL . '/css/cartalog_css.css';
        wp_enqueue_style('cartalog-css', $url);
        // wp_enqueue_style('cartalog-css', $url, null, CARTALOG_VERSION);

        if($custom_styles = $this->_options->getOption('custom_styles')) {
            wp_enqueue_style('cartalog-custom-css', $custom_styles, 'cartalog-css');
        }
    }
    
    public function initAdmin()
    {
        add_menu_page('Cartalog', 'Cartalog', 'administrator',
                'cartalog_admin', null, '/wp-admin/images/generic.png');
        add_submenu_page('cartalog_admin', 'Options', 'Options', 'administrator',
                'cartalog_admin',  array('AdminMgr','adminPage'));
    }

    public function initShortCodes()
    {
        $scm = new Shortcode_Mgr();
        add_shortcode('cartalog_category', array($scm, 'addCategory'));
        add_shortcode('cartalog_item',     array($scm, 'addItem'));
        add_shortcode('cartalog_show_tax', array($scm, 'showTax'));
    }

    public static function getView($template, $args, $content = null)
    {
        $filename = CARTALOG_PATH . '/' . $template;

        ob_start();
        include $filename;
        $view = ob_get_contents();
        ob_end_clean();

        return $view;
    }

    public function cartalogStyles()
    {
?>
<style type="text/css">
.categoryWrap {
<?php if($this->_options->getOption('category_width') !== "") : ?>
  width: <?php echo $this->_options->getOption('category_width'); ?>;
<?php endif; ?>
}
.categoryTitle {
<?php if($this->_options->getOption('category_background') !== "") : ?>
  background-color: <?php echo $this->_options->getOption('category_background'); ?>;
<?php endif; ?>
<?php if($this->_options->getOption('category_color') !== "") : ?>
  color: <?php echo $this->_options->getOption('category_color'); ?>;
<?php endif; ?>
}
article.storeItem {
  width: <?php echo $this->_options->getOption('item_width'); ?>;
<?php if($this->_options->getOption('item_background') !== "") : ?>
  background-color:<?php echo $this->_options->getOption('item_background'); ?>;
<?php endif; ?>
<?php if($this->_options->getOption('item_color') !== "") : ?>
  color:<?php echo $this->_options->getOption('item_color'); ?>;
<?php endif; ?>
<?php if($this->_options->getOption('item_border') === "1") : ?>
  border: 1px solid #000;
<?php endif; ?>
}
</style>
<?php
    }

    function ajax_display_modal()
    {
        $nonce = $_POST['displayNonce'];

        if( ! wp_verify_nonce( $nonce, 'my_display_nonce' ) ) {
            die ( "Oh no you don't!" );
        }

        $postID = $_POST['postID'];

        // get page data
        $content = get_post($postID, ARRAY_A);

        // Can't use for cart button because of id duplication
        // Cart66::initShortcodes();
        // $content = do_shortcode($content);

        echo '<div class="entry" style="padding: 10px 10px 0;">';
        echo '<h2>' . $content['post_title'] . '</h2>';
        echo '<p></p>';
        echo $content['post_content'];
        echo '</div>';
        // var_dump($content);

        exit;
    }

    public function uninstall()
    {
        $this->_options->delete();
    }

}
