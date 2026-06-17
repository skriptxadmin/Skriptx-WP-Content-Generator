<?php

class SkriptxConGenAjax
{

    public function init()
    {
        if (wp_doing_ajax()) {
            
            require_once SKRIPTX_CONGEN_PLUGIN_DIR . 'ajax/index.php';

        }

    }
}
