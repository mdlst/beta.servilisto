<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : views/settings/views-backend-settings-licences.php
* File Version            : 1.0
* Created / Last Modified : 06 December 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end licences settings views class.
*/

    if (!class_exists('DOPBSPViewsBackEndSettingsLicences')){
        class DOPBSPViewsBackEndSettingsLicences extends DOPBSPViewsBackEndSettings{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns licences settings template.
             * 
             * @param args (array): function arguments
             * 
             * @return licences settings HTML
             */
            function template($args = array()){
                global $DOPBSP;
                
                $settings_general = $DOPBSP->classes->backend_settings->values(0,  
                                                                              'general');
?>
                <div class="dopbsp-inputs-wrapper">
                    <em><?php echo $DOPBSP->text('SETTINGS_LICENCES_HELP'); ?></em>
                </div>

                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_LICENCES_TITLE_PRO'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('dopbsp')" id="DOPBSP-inputs-button-dopbsp" class="dopbsp-button"></a>
                    <a href="javascript:DOPBSPBackEndSettingsLicences.set('dopbsp', 'deactivate')" id="DOPBSP-inputs-button-dopbsp-deactivate" class="dopbsp-button-text" style="display: <?php echo $settings_general->dopbsp_licence_status == 'activated' ? 'block':'none'; ?>"><?php echo $DOPBSP->text('SETTINGS_LICENCES_STATUS_DEACTIVATE'); ?></a>
                    <a href="javascript:DOPBSPBackEndSettingsLicences.set('dopbsp', 'activate')" id="DOPBSP-inputs-button-dopbsp-activate" class="dopbsp-button-text" style="display: <?php echo $settings_general->dopbsp_licence_status == 'activated' ? 'none':'block'; ?>"><?php echo $DOPBSP->text('SETTINGS_LICENCES_STATUS_ACTIVATE'); ?></a>
                </div>
                <div id="DOPBSP-inputs-dopbsp" class="dopbsp-inputs-wrapper dopbsp-displayed">
                    <!--
                        Pinpoint Booking System PRO licence status.
                    -->
                    <div class="dopbsp-input-wrapper">
                        <label><?php echo $DOPBSP->text('SETTINGS_LICENCES_STATUS'); ?></label>
                        <em id="DOPBSP-settings-dopbsp-licence-status" class="dopbsp-licence-status<?php echo $settings_general->dopbsp_licence_status == 'activated' ? ' dopbsp-activated':' dopbsp-deactivated'; ?>">
                            <?php echo $settings_general->dopbsp_licence_status == 'activated' ? $DOPBSP->text('SETTINGS_LICENCES_STATUS_ACTIVATED'):$DOPBSP->text('SETTINGS_LICENCES_STATUS_DEACTIVATED'); ?>
                        </em>
                    </div>
                    
                    <!--
                        Pinpoint Booking System PRO licence key.
                    -->
                    <div class="dopbsp-input-wrapper">
                        <label for="DOPBSP-settings-dopbsp-licence-key"><?php echo $DOPBSP->text('SETTINGS_LICENCES_KEY'); ?></label>
                        <input type="text" name="DOPBSP-settings-dopbsp-licence-key" id="DOPBSP-settings-dopbsp-licence-key" value="<?php echo $settings_general->dopbsp_licence_key; ?>"<?php echo $settings_general->dopbsp_licence_status == 'activated' ? ' disabled="disabled"':''; ?> />
                        <a href="<?php echo DOPBSP_CONFIG_HELP_DOCUMENTATION_URL; ?>" target="_blank" class="dopbsp-button dopbsp-help"><span class="dopbsp-info dopbsp-help"><?php printf($DOPBSP->text('SETTINGS_LICENCES_KEY_HELP'), DOPBSP_CONFIG_SHOP_URL.'my-account'); ?><br /><br /><?php echo $DOPBSP->text('HELP_VIEW_DOCUMENTATION'); ?></span></a>
                    </div>
                    
                    <!--
                        Pinpoint Booking System PRO licence email.
                    -->
                    <div class="dopbsp-input-wrapper dopbsp-last">
                        <label for="DOPBSP-settings-dopbsp-licence-email"><?php echo $DOPBSP->text('SETTINGS_LICENCES_EMAIL'); ?></label>
                        <input type="text" name="DOPBSP-settings-dopbsp-licence-email" id="DOPBSP-settings-dopbsp-licence-email" value="<?php echo $settings_general->dopbsp_licence_email; ?>"<?php echo $settings_general->dopbsp_licence_status == 'activated' ? ' disabled="disabled"':''; ?> />
                        <a href="<?php echo DOPBSP_CONFIG_HELP_DOCUMENTATION_URL; ?>" target="_blank" class="dopbsp-button dopbsp-help"><span class="dopbsp-info dopbsp-help"><?php printf($DOPBSP->text('SETTINGS_LICENCES_EMAIL_HELP'), DOPBSP_CONFIG_SHOP_URL); ?><br /><br /><?php echo $DOPBSP->text('HELP_VIEW_DOCUMENTATION'); ?></span></a>
                    </div>
                </div>
<?php
    
/*
 * ACTION HOOK (dopbsp_action_views_settings_licences) ***************** Add licences settings.
 */
                do_action('dopbsp_action_views_settings_licences', array('settings_general' => $settings_general));
            }
        }
    }