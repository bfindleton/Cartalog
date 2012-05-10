<?php
class Shortcode_Mgr
{

    protected $_options;
    protected $_cart = null;
    protected $_item_modal = null;

    public function __construct()
    {
        $this->_options = new Cartalog_Options(CARTALOG_OPTIONS_KEY);
		$this->_cart = $this->_options->getOption('cart_vendor');
		$this->_item_modal = $this->_options->getOption('item_modal');
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
            'item_modal' => $this->_item_modal,
        );
        
        $args = shortcode_atts($defaults, $attr);
        
        $view = Cartalog::getView('views/item.phtml', $args);
        
        return $view;
    }

    public function showTax($attr, $content)
    {
        $defaults = array
        (
            'title' => null,
            'style' => null,
            'class' => null,
        );
        
        $args = shortcode_atts($defaults, $attr);
        
        $view = Cartalog::getView('views/showtax.phtml', $args, $content);
        
        return $view;

    }

}
