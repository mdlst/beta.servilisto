<?php

/*
* Title                   : Pinpoint Booking System WordPress Plugin (PRO)
* Version                 : 2.1.2
* File                    : views/search/views-backend-search.php
* File Version            : 1.0.9
* Created / Last Modified : 11 October 2015
* Author                  : Dot on Paper
* Copyright               : © 2012 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : Back end search views class.
*/

    if (!class_exists('DOPBSPViewsBackEndSearch')){
        class DOPBSPViewsBackEndSearch extends DOPBSPViewsBackEndSearches{
            /*
             * Constructor
             */
            function __construct(){
            }
            
            /*
             * Returns search.
             * 
             * @param args (array): function arguments
             *                      * id (integer): search ID
             *                      * language (string): search language
             * 
             * @return search HTML
             */
            function template($args = array()){
                global $wpdb;
                global $DOPBSP;
                
                $id = $args['id'];
                $language = isset($args['language']) && $args['language'] != '' ? $args['language']:$DOPBSP->classes->backend_language->get();
                
                $search = $wpdb->get_row($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->searches.' WHERE id=%d',
                                                        $id));
                $settings_search = $DOPBSP->classes->backend_settings->values($id,  
                                                                              'search');
?>
                <div class="dopbsp-inputs-wrapper">
<?php                    
                /*
                 * Name
                 */
                $this->displayTextInput(array('id' => 'name',
                                              'label' => $DOPBSP->text('SEARCHES_SEARCH_NAME'),
                                              'value' => $search->name,
                                              'search_id' => $search->id,
                                              'help' => $DOPBSP->text('SEARCHES_SEARCH_NAME_HELP'),
                                              'container_class' => 'dopbsp-last'));
?>
                </div>
<?php           
                $this->templateExcludedCalendars($search,
                                                 $settings_search);
            }
            
            /*
             * Returns search excluded calendars template.
             * 
             * @param search (object): search data
             * @param settings_search (object): search settings data
             * 
             * @return search excluded calendars HTML
             */
            function templateExcludedCalendars($search,
                                               $settings_search){
                global $DOPBSP;
?>
                <div class="dopbsp-inputs-header dopbsp-last dopbsp-hide">
                    <h3><?php echo $settings_search->hours_enabled == 'false' ? $DOPBSP->text('SEARCHES_EDIT_SEARCH_EXCLUDED_CALENDARS_DAYS'):$DOPBSP->text('SEARCHES_EDIT_SEARCH_EXCLUDED_CALENDARS_HOURS'); ?></h3>
                    <a href="javascript:DOPBSPBackEnd.toggleInputs('search-excluded-calendars')" id="DOPBSP-inputs-button-search-excluded-calendars" class="dopbsp-button"></a>
                </div>
                <div id="DOPBSP-inputs-search-excluded-calendars" class="dopbsp-inputs-wrapper dopbsp-last">
                    <div class="dopbsp-input-wrapper dopbsp-last">
                        <ul id="DOPBSP-search-excluded-calendars" class="dopbsp-input-list">
<?php           
                /*
                 * Calendars list.
                 */
                echo $this->listCalendars($search,
                                          $settings_search);
?>
                        </ul>
                    </div>
                </div>
<?php       
            }

/*
 * Inputs.
 */
            /*
             * Create a text input for searches.
             * 
             * @param args (array): function arguments
             *                      * id (integer): search field ID
             *                      * label (string): search label
             *                      * value (string): search current value
             *                      * search_id (integer): search ID
             *                      * help (string): search help
             *                      * container_class (string): container class
             * 
             * @return text input HTML
             */
            function displayTextInput($args = array()){
                global $DOPBSP;
                
                $id = $args['id'];
                $label = $args['label'];
                $value = $args['value'];
                $search_id = $args['search_id'];
                $help = $args['help'];
                $container_class = isset($args['container_class']) ? $args['container_class']:'';
                $input_class = isset($args['input_class']) ? $args['input_class']:'';
                    
                $html = array();

                array_push($html, ' <div class="dopbsp-input-wrapper '.$container_class.'">');
                array_push($html, '     <label for="DOPBSP-search-'.$id.'">'.$label.'</label>');
                array_push($html, '     <input type="text" name="DOPBSP-search-'.$id.'" id="DOPBSP-search-'.$id.'" class="'.$input_class.'" value="'.$value.'" onkeyup="if ((event.keyCode||event.which) !== 9){DOPBSPBackEndSearch.edit('.$search_id.', \'text\', \''.$id.'\', this.value);}" onpaste="DOPBSPBackEndSearch.edit('.$search_id.', \'text\', \''.$id.'\', this.value)" onblur="DOPBSPBackEndSearch.edit('.$search_id.', \'text\', \''.$id.'\', this.value, true)" />');
                array_push($html, '     <a href="'.DOPBSP_CONFIG_HELP_DOCUMENTATION_URL.'" target="_blank" class="dopbsp-button dopbsp-help"><span class="dopbsp-info dopbsp-help">'.$help.'<br /><br />'.$DOPBSP->text('HELP_VIEW_DOCUMENTATION').'</span></a>');                        
                array_push($html, ' </div>');

                echo implode('', $html);
            }
            
/*
 * Lists
 */       
            /*
             * Get calendars with days or hours availability.
             * 
             * @param search (object): search data
             * @param $settings_search (object): search_settings data
             * 
             * @return HTML with the calendars
             */
            function listCalendars($search,
                                   $settings_search){
                global $wpdb;
                global $DOPBSP;
                 
                $calendars_excluded = ','.$search->calendars_excluded.',';
                
                if ($DOPBSP->classes->backend_settings_users->permission(wp_get_current_user()->ID, 'view-all-calendars')){
                    $calendars = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE hours_enabled="%s" AND post_id<>0 ORDER BY id ASC',
                                                                   $settings_search->hours_enabled));
                }
                elseif ($DOPBSP->classes->backend_settings_users->permission(wp_get_current_user()->ID, 'use-booking-system')){
                    $calendars = $wpdb->get_results($wpdb->prepare('SELECT * FROM '.$DOPBSP->tables->calendars.' WHERE hours_enabled="%s" AND (user_id=%d OR user_id=0) AND post_id<>0 ORDER BY id ASC',
                                                                   $settings_search->hours_enabled, wp_get_current_user()->ID));
                }
                
                if ($wpdb->num_rows != 0){
                    foreach ($calendars as $calendar){
?>                          
                            <li<?php echo strrpos($calendars_excluded, ','.$calendar->id.',') === false ? '':' class="dopbsp-selected"'; ?>>
                                <label for="DOPBSP-search-calendar<?php echo $calendar->id; ?>">
                                    <span class="dopbsp-id">ID: <?php echo $calendar->id; ?></span>
                                    <?php echo $calendar->name; ?>
                                </label>
                                <input type="checkbox" name="DOPBSP-search-calendar<?php echo $calendar->id; ?>" id="DOPBSP-search-calendar<?php echo $calendar->id; ?>"<?php echo strrpos($calendars_excluded, ','.$calendar->id.',') === false ? '':' checked="checked"'; ?> onclick="DOPBSPBackEndSearch.edit('<?php echo $search->id; ?>', 'checkbox', 'calendars_excluded')"  />
                            </li>
<?php
                    }
                }
                else{
?>
                            <li class="dopbsp-no-data">            
                                <?php printf($DOPBSP->text('SEARCHES_EDIT_SEARCH_NO_CALENDARS'), admin_url('admin.php?page=dopbsp-calendars')); ?>
                            </li>
<?php
                }
            }
        }
    }