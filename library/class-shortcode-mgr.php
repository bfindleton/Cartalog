<?php
class Shortcode_Mgr
{

    protected $_options;
    protected $_cart = null;

    public function __construct()
    {
        $this->_options = new Cartalog_Options(CARTALOG_OPTIONS_KEY);
		$this->_cart = $this->_options->getOption('cart_vendor');
    }

    public function addCategory($attr, $content)
    {
        $defaults = array
        (
            'title' => null,
            'style' => null,
            'class' => null,
        );
        
        $args = shortcode_atts($defaults, $attr);
        
        $view = Cartalog::getView('views/category.phtml', $args, $content);
        
        return $view;

    }

    public function addItem($attr, $content)
    {
        $defaults = array
        (
            'title' => 'Product Name',
            'class' => null,
            'item_style' => null,
            'desc' => 'Description',
            'picture' => null,
            'detail' => null,
            'item' => null,
            'style' => null,
            'quantity' => "1",
            'ajax' => 'no',
            'showprice' => 'yes',
            'img' => null,
			'cart' => $this->_cart,
        );
        
        $args = shortcode_atts($defaults, $attr);
        
        $view = Cartalog::getView('views/item.phtml', $args);
        
        return $view;
    }

}
