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
            add_action('wp_head', array($this,'cartalogStyles'));
        }
    }

    public function initOptions()
    {
        $version = $this->_options->getOption('version');
        if( null === $version ) {
            $defaults = array(
                'version' => CARTALOG_VERSION,
                'cart_vendor' => 'Cart66',
                'category_width' => '710px',
                'category_background' => '#2f8aba',
                'category_color' => '#FFFFFF',
                'item_width' => '200px',
                'item_background' => null,
                'item_color' => '#000000',
                'item_border' => null,
                'custom_styles' => null,
                'cart_vendors' => array(
                    'Cart66' => CART_CART66,
                    'WooCommerce' => CART_WOOCOM),
                );
        } elseif ( CARTALOG_VERSION !== $version ) {
            $defaults = array(
                'version'             => CARTALOG_VERSION,
                'cart_vendor'         => $this->_options->cart_vendor,
                'category_width'      => $this->_options->category_width,
                'category_background' => $this->_options->category_background,
                'category_color'      => $this->_options->category_color,
                'item_width'          => $this->_options->item_width,
                'item_background'     => $this->_options->item_background,
                'item_color'          => $this->_options->item_color,
                'item_border'         => $this->_options->item_border,
                'custom_styles'       => $this->_options->custom_styles,
                'cart_vendors'        => array(
                    'Cart66'              => CART_CART66,
                    'WooCommerce'         => CART_WOOCOM),
                );
            $this->_options->delete();
        } else {
            $defaults = $this->_options->getOptions();
        }

        $this->_options->setOptions($defaults);
    }

    public function initEnq()
    {
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

    public function uninstall()
    {
        $this->_options->delete();
    }

}
