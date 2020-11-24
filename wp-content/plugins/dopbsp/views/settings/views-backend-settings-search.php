<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : views/settings/views-backend-settings-search.php
* File Version            : 1.0.5
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search settings views class.
*/

    if (!class_exists('DOPBSPViewsBackEndSettingsSearch')){
        class DOPBSPViewsBackEndSettingsSearch extends DOPBSPViewsBackEndSettings{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search settings template.
             * 
             * @param args (array): function arguments
             *                      * id (integer): search ID
             * 
             * @return search settings HTML
             */
            function template($args = array()){
                global $DOPBSP;
                
                $id = $args['id'];
                
                $settings_search = $DOPBSP->classes->backend_settings->values($id,  
                                                                              'search');
                
                $this->templateGeneral($id,
                                       $settings_search);
                $this->templateViews($id,
                                     $settings_search);
                $this->templateCurrency($id,
                                        $settings_search);
                $this->templateDays($id,
                                    $settings_search);
                $this->templateHours($id,
                                     $settings_search);
                $this->templateAvailability($id,
                                            $settings_search);
            }
            
            /*
             * Returns search general settings template.
             * 
             * @param id (integer): search ID
             * @param settings_search (object): search settings data
             * 
             * @return search general settings HTML
             */
            function templateGeneral($id,
                                     $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_SEARCH_GENERAL_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-general-settings')" id="DOPBSP-inputs-button-search-general-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-general-settings" class="dopbsp-inputs-wrapper">
<?php   
                /*
                 * Select date type.
                 */
                $this->displaySelectInput(array('id' => 'date_type',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_DATE_TYPE'),
                                                'value' => $settings_search->date_type,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_DATE_TYPE_HELP'),
                                                'options' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_DATE_TYPE_AMERICAN').';;'.$DOPBSP->text('SETTINGS_SEARCH_GENERAL_DATE_TYPE_EUROPEAN'),
                                                'options_values' => '1;;2'));
                /*
                 * Select search template.
                 */
                $this->displaySelectInput(array('id' => 'template',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_TEMPLATE'),
                                                'value' => $settings_search->template,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_TEMPLATE_HELP'),
                                                'options' => $this->listTemplates(),
                                                'options_values' => $this->listTemplates()));
                /*
                 * Enable search input.
                 */
                if (DOPBSP_DEVELOPMENT_MODE){
                    $this->displaySwitchInput(array('id' => 'search_enabled',
                                                    'label' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_SEARCH_ENABLED'),
                                                    'value' => $settings_search->search_enabled,
                                                    'settings_id' => $id,
                                                    'settings_type' => 'search',
                                                    'help' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_SEARCH_ENABLED_HELP')));
                }
                
                /*
                 * Enable price.
                 */
                $this->displaySwitchInput(array('id' => 'price_enabled',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_PRICE_ENABLED'),
                                                'value' => $settings_search->price_enabled,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_GENERAL_PRICE_ENABLED_HELP'),
                                                'container_class' => 'dopbsp-last'));
?>
                </div>
<?php                
            }
            
            /*
             * Returns search views settings template.
             * 
             * @param id (integer): search ID
             * @param settings_search (object): search settings data
             * 
             * @return search views settings HTML
             */
            function templateViews($id,
                                   $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_SEARCH_VIEW_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-view-settings')" id="DOPBSP-inputs-button-search-view-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-view-settings" class="dopbsp-inputs-wrapper">
<?php                                              
                /*
                 * Select default view.
                 */
                $this->displaySelectInput(array('id' => 'view_default',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_DEFAULT'),
                                                'value' => $settings_search->view_default,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_DEFAULT_HELP'),
                                                'options' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_DEFAULT_LIST').';;'.$DOPBSP->text('SETTINGS_SEARCH_VIEW_DEFAULT_GRID').';;'.$DOPBSP->text('SETTINGS_SEARCH_VIEW_DEFAULT_MAP'),
                                                'options_values' => 'list;;grid;;map'));
                /*
                 * Enable list.
                 */
                $this->displaySwitchInput(array('id' => 'view_list_enabled',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_LIST_VIEW_ENABLED'),
                                                'value' => $settings_search->view_list_enabled,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_LIST_VIEW_ENABLED_HELP')));
                /*
                 * Enable grid.
                 */
                $this->displaySwitchInput(array('id' => 'view_grid_enabled',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_GRID_VIEW_ENABLED'),
                                                'value' => $settings_search->view_grid_enabled,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_GRID_VIEW_ENABLED_HELP')));
                /*
                 * Enable map.
                 */
                $this->displaySwitchInput(array('id' => 'view_map_enabled',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_MAP_VIEW_ENABLED'),
                                                'value' => $settings_search->view_map_enabled,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_MAP_VIEW_ENABLED_HELP')));
                /*
                 * Enter the number of results that will be displayed on a page.
                 */
                $this->displayTextInput(array('id' => 'view_results_page',
                                              'label' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_RESULTS_PAGE'),
                                              'value' => $settings_search->view_results_page,
                                              'settings_id' => $id,
                                              'settings_type' => 'search',
                                              'help' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_RESULTS_PAGE_HELP'),
                                              'container_class' => '',
                                              'input_class' => 'dopbsp-small'));
                /*
                 * Select sidebar position.
                 */
                $this->displaySelectInput(array('id' => 'view_sidebar_position',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_SIDEBAR_POSITION'),
                                                'value' => $settings_search->view_sidebar_position,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_SIDEBAR_POSITION_HELP'),
                                                'options' => $DOPBSP->text('SETTINGS_SEARCH_VIEW_SIDEBAR_POSITION_LEFT').';;'.$DOPBSP->text('SETTINGS_SEARCH_VIEW_SIDEBAR_POSITION_RIGHT').(DOPBSP_DEVELOPMENT_MODE ? ';;'.$DOPBSP->text('SETTINGS_SEARCH_VIEW_SIDEBAR_POSITION_TOP'):''),
                                                'options_values' => 'left;;right'.(DOPBSP_DEVELOPMENT_MODE ? ';;top':''),
                                                'container_class' => 'dopbsp-last'));
?>
                </div>
<?php                
            }
            
            /*
             * Returns search currency settings template.
             * 
             * @param id (integer): search ID
             * @param settings_search (object): search settings data
             * 
             * @return search currency settings HTML
             */
            function templateCurrency($id,
                                      $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_SEARCH_CURRENCY_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-currency-settings')" id="DOPBSP-inputs-button-search-currency-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-currency-settings" class="dopbsp-inputs-wrapper">
<?php
                /*
                 * Select currency.
                 */
                $this->displaySelectInput(array('id' => 'currency',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_CURRENCY'),
                                                'value' => $settings_search->currency,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_CURRENCY_HELP'),
                                                'options' => $this->listCurrencies('labels'),
                                                'options_values' => $this->listCurrencies('ids')));
                /*
                 * Select currency position.
                 */
                $this->displaySelectInput(array('id' => 'currency_position',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_CURRENCY_POSITION'),
                                                'value' => $settings_search->currency_position,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_CURRENCY_POSITION_HELP'),
                                                'options' => $DOPBSP->text('SETTINGS_SEARCH_CURRENCY_POSITION_BEFORE').';;'.$DOPBSP->text('SETTINGS_SEARCH_CURRENCY_POSITION_BEFORE_WITH_SPACE').';;'.$DOPBSP->text('SETTINGS_SEARCH_CURRENCY_POSITION_AFTER').';;'.$DOPBSP->text('SETTINGS_SEARCH_CURRENCY_POSITION_AFTER_WITH_SPACE'),
                                                'options_values' => 'before;;before_with_space;;after;;after_with_space',
                                                'container_class' => 'dopbsp-last'));
?>
                </div>
<?php
            }
            
            /*
             * Returns search days settings template.
             * 
             * @param id (integer): search ID
             * @param settings_search (object): search settings data
             * 
             * @return search days settings HTML
             */
            function templateDays($id,
                                  $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_SEARCH_DAYS_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-days-settings')" id="DOPBSP-inputs-button-search-days-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-days-settings" class="dopbsp-inputs-wrapper">
<?php                        
                /*
                 * Select calendar first week day.
                 */
                $this->displaySelectInput(array('id' => 'days_first',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_DAYS_FIRST'),
                                                'value' => $settings_search->days_first,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_DAYS_FIRST_HELP'),
                                                'options' => $DOPBSP->text('DAY_MONDAY').';;'.$DOPBSP->text('DAY_TUESDAY').';;'.$DOPBSP->text('DAY_WEDNESDAY').';;'.$DOPBSP->text('DAY_THURSDAY').';;'.$DOPBSP->text('DAY_FRIDAY').';;'.$DOPBSP->text('DAY_SATURDAY').';;'.$DOPBSP->text('DAY_SUNDAY'),
                                                'options_values' => '1;;2;;3;;4;;5;;6;;7'));
                /*
                 * Enable multiple days select.
                 */
                $this->displaySwitchInput(array('id' => 'days_multiple_select',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_DAYS_MULTIPLE_SELECT'),
                                                'value' => $settings_search->days_multiple_select,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_DAYS_MULTIPLE_SELECT_HELP'),
                                                'container_class' => 'dopbsp-last'));
?>
                </div>
<?php
            }
            
            /*
             * Returns search hours settings template.
             * 
             * @param id (integer): search ID
             * @param settings_search (object): search settings data
             * 
             * @return search hours settings HTML
             */
            function templateHours($id,
                                   $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_SEARCH_HOURS_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-hours-settings')" id="DOPBSP-inputs-button-search-hours-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-hours-settings" class="dopbsp-inputs-wrapper">
<?php
                /*
                 * Hours enabled.
                 */
                $this->displaySwitchInput(array('id' => 'hours_enabled',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_ENABLED'),
                                                'value' => $settings_search->hours_enabled,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_ENABLED_HELP')));
                /*
                 * Hours definitions.
                 */
                $hours_html = array();
                $hours = json_decode($settings_search->hours_definitions);

                foreach ($hours as $hour){
                    array_push($hours_html, $hour->value);
                }
                
                $this->displayTextarea(array('id' => 'hours_definitions',
                                             'label' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_DEFINITIONS'),
                                             'value' => implode("\n", $hours_html),
                                             'settings_id' => $id,
                                             'settings_type' => 'search',
                                             'help' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_DEFINITIONS_HELP')));
                /*
                 * Enable multiple hours select.
                 */
                $this->displaySwitchInput(array('id' => 'hours_multiple_select',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_MULTIPLE_SELECT'),
                                                'value' => $settings_search->hours_multiple_select,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_MULTIPLE_SELECT_HELP')));
                /*
                 * Set hours AM/PM.
                 */
                $this->displaySwitchInput(array('id' => 'hours_ampm',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_AMPM'),
                                                'value' => $settings_search->hours_ampm,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_HOURS_AMPM_HELP'),
                                                'container_class' => 'dopbsp-last'));
?>
                </div>
<?php       
            }
            
            /*
             * Returns search availability settings template.
             * 
             * @param id (integer): search ID
             * @param settings_search (object): search settings data
             * 
             * @return search availability settings HTML
             */
            function templateAvailability($id,
                                          $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-last dopbsp-hide">
                    <h3><?php echo $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_SETTINGS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-availability-settings')" id="DOPBSP-inputs-button-search-availability-settings" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-availability-settings" class="dopbsp-inputs-wrapper dopbsp-last">
<?php                            
                /*
                 * Enable availability filter.
                 */
                $this->displaySwitchInput(array('id' => 'availability_enabled',
                                                'label' => $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_ENABLED'),
                                                'value' => $settings_search->availability_enabled,
                                                'settings_id' => $id,
                                                'settings_type' => 'search',
                                                'help' => $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_ENABLED_HELP')));         
                /*
                 * Minimum availability value.
                 */
                $this->displayTextInput(array('id' => 'availability_min',
                                              'label' => $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_MIN'),
                                              'value' => $settings_search->availability_min,
                                              'settings_id' => $id,
                                              'settings_type' => 'search',
                                              'help' => $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_MIN_HELP'),
                                              'container_class' => '',
                                              'input_class' => 'dopbsp-small'));
                /*
                 * Maximum availability value.
                 */
                $this->displayTextInput(array('id' => 'availability_max',
                                              'label' => $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_MAX'),
                                              'value' => $settings_search->availability_max,
                                              'settings_id' => $id,
                                              'settings_type' => 'search',
                                              'help' => $DOPBSP->text('SETTINGS_SEARCH_AVAILABILITY_MAX_HELP'),
                                              'container_class' => 'dopbsp-last',
                                              'input_class' => 'dopbsp-small'));
?>
                </div>
<?php                
            }
        }
    }