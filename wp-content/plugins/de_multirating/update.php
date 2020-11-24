<?php
if (!class_exists('DE_MultiRating_Update') && class_exists('AE_Plugin_Updater')){
    class DE_MultiRating_Update extends AE_Plugin_Updater{
        const VERSION = '1.4.2';

        // setting up updater
        public function __construct(){
            $this->product_slug     = plugin_basename( dirname(__FILE__) . '/de_multirating.php' );
            $this->slug             = 'de_multirating';
            $this->license_key      = get_option('et_license_key', '');
            $this->current_version  = self::VERSION;
            $this->update_path      = 'http://forum.enginethemes.com/?do=product-update&product=de_multirating&type=plugin';

            parent::__construct();
        }
    }
    new DE_MultiRating_Update();
}
