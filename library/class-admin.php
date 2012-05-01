<?php
class AdminMgr
{

    public function adminPage()
    {
        $opts = new Cartalog_Options(CARTALOG_OPTIONS_KEY);

        $args = $opts->getOptions();

        $view = Cartalog::getView('views/admin.phtml', $args);

        echo $view;
    }

    public function updateOptions($options)
    {
        $opts = new Cartalog_Options(CARTALOG_OPTIONS_KEY);

        $opts->setOptions($options);

        $args = $opts->getOptions();

        return $args;

    }
}
