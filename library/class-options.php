<?php
/**
 * @package WordPress
 * @subpackage Cartalog
 *
 * Copyright (c) 2012 Bruce Findleton
 *
 * This file is part of Cartalog Wordpress Ecommerce Plugin
 */

/**
 * A basic options management class
 */
class Cartalog_Options
{
    /**
     * Options array
     * @var array
     */
    protected $_options = array();
    
    /**
     * Defines options record in the wp_options table
     * @var string
     */
    protected $_optionKey = null;
    
    /**
     * Performs initializion of the options structure
     *
     * @param string $optionKey The options key name
     */
    public function __construct($optionKey)
    {
        $options = get_option($optionKey);
        
        if (false === $options)
        {
            $options = array();
        }
        
        $this->_options = $options;
        $this->_optionKey = $optionKey;
    }
    
    /**
     * Updates the option identified by $name with the value provided in $value
     *
     * @param string $name The option name
     * @param mixed  $value The option value
     * @return Cartalog_Options
     */
    public function __set($name, $value)
    {
        $this->_options[$name] = $value;
        return $this;
    }
    
    /**
     * Updates the option identified by $name with the value provided in $value
     *
     * This is an alias for {@link __set()}
     *
     * @param string $name The option name
     * @param mixed  $value The option value
     * @return Cartalog_Options
     */
    public function setOption($name, $value)
    {
        return $this->__set($name, $value);
    }

    public function setOptions($options)
    {
        foreach ( $options as $name => $value ) {
            $this->_options[$name] = $value;
        }
        $this->save();
    }
    
    /**
     * Returns a value of the option identified by $name
     *
     * @param string $name The option name
     * @return mixed|null
     */
    public function __get($name)
    {
        $value = array_key_exists($name, $this->_options) ? $this->_options[$name] : null;
        return $value;
    }
    
    /**
     * Returns a value of the option identified by $name
     *
     * This is an alias for {@link __get()}
     *
     * @param string $name The option name
     * @return mixed|null
     */
    public function getOption($name)
    {
        return $this->__get($name);
    }

    public function getOptions()
    {
        return $this->_options;
    }
    
    /**
     * Saves the internal options data to the wp_options table using the stored $optionKey value as the key
     *
     * @return boolean
     */
    public function save()
    {
        return update_option($this->_optionKey, $this->_options);
    }
    
    /**
     * Deletes the internal options data from the wp_options table.
     * This method is intended to be used as part of the uninstall process.
     *
     * @return boolean
     */
    public function delete()
    {
        return delete_option($this->_optionKey);
    }
}
