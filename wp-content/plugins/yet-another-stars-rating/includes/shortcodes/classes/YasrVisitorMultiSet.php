<?php
/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/**
 * Class YasrVisitorMultiSet
 */
class YasrVisitorMultiSet extends YasrMultiSet {

    protected $loader_html;
    protected $button_html;
    protected $button_html_disabled;
    protected $button;
    protected $star_readonly;
    protected $span_message_content;


    /**
     * Print Yasr Visitor MultiSet
     *
     * @param void
     * @return string
     */
    public function printVisitorMultiSet () {

        $ajax_nonce_visitor_multiset = wp_create_nonce("yasr_nonce_insert_visitor_rating_multiset");

        $this->shortcode_html = '<!-- Yasr Visitor Multi Set Shortcode-->';

        $post_set_id = $this->post_id.'-'.$this->set_id;

        $image = YASR_IMG_DIR . "/loader.gif";
        $this->loader_html = "<span class='yasr-loader-multiset-visitor' 
                                  id='yasr-loader-multiset-visitor-$post_set_id'>
                                  &nbsp;<img src='$image' title='yasr-loader' alt='yasr-loader'>
                              </span>";

        $this->button_html = "<input type='submit'
                                  name='submit'
                                  id='yasr-send-visitor-multiset-$post_set_id'
                                  class='button button-primary yasr-send-visitor-multiset'
                                  data-postid='$this->post_id'
                                  data-setid='$this->set_id'
                                  data-nonce='$ajax_nonce_visitor_multiset'
                                  value='" . __('Submit!', 'yet-another-stars-rating') . "' 
                              />";

        $this->button_html_disabled = "<input type='submit'
                                           disabled='disabled'
                                           class='button button-primary' 
                                           id='yasr-send-visitor-multiset-disabled'
                                           disabled='disabled' 
                                           value='" . __('Submit!', 'yet-another-stars-rating') . "'
                                        />";

        //check cookie and assign default values
        $this->multisetAttributes();

        $set_name_content = YasrMultiSetData::returnVisitorMultiSetContent($this->post_id, $this->set_id);

        if (!$set_name_content) {
            $this->shortcode_html .= __('No MultiSet found with this ID', 'yet-another-stars-rating');
            return $this->shortcode_html;
        }

        $this->shortcode_html .= "<table class='yasr_table_multi_set_shortcode'>";

        //print the single rows
        $this->printMultisetRows($set_name_content, true);

        //Submit row and button
        $this->shortcode_html .="<tr>
                                    <td colspan='2'>
                                        $this->button
                                        $this->loader_html
                                        <span class='yasr-visitor-multiset-message'>$this->span_message_content</span>
                                    </td>
                                </tr>
                                ";

        $this->shortcode_html .= "</table>";
        $this->shortcode_html .= '<!-- End Yasr Multi Set Visitor Shortcode-->';

        return $this->shortcode_html;
    }

    public function checkCookie() {
        //custommize cookie name for yasr_multi_visitor_cookie
        $yasr_cookiename = apply_filters('yasr_mv_cookie', 'yasr_multi_visitor_cookie');

        //Check cookie and if voting is allowed only to logged in users
        if (isset($_COOKIE[$yasr_cookiename])) {
            $cookie_data = stripslashes($_COOKIE[ $yasr_cookiename ]);

            //By default, json_decode return an object, true to return an array
            $cookie_data = json_decode($cookie_data, true);

            if (is_array($cookie_data)) {
                foreach ($cookie_data as $value) {
                    $cookie_post_id = (int)$value['post_id'];
                    $cookie_set_id =  (int)$value['set_id'];

                    if ($cookie_post_id === $this->post_id && $cookie_set_id === $this->set_id) {
                        return true;
                    }
                }
                //if foreach ends, return false
                return false;
            }
            //if cookie is not an array, should never happens
            return false;
        }
        return false;
    }

    /**
     * This function first check if a cookie is set,
     * Then who can rate and set attributes to:
     * $this->button
     * $this->star_readonly
     * $this->span_message_content
     *
     * @param void
     * @return void
     *
     */
    protected function multisetAttributes() {

        $this->checkCookie();

        $set_enabled = YasrShortcode::starsEnalbed( $this->checkCookie() );

        if($set_enabled === 'true_logged' || $set_enabled === 'true_not_logged') {
            $this->button               = $this->button_html;
            $this->star_readonly        = 'false';
            $this->span_message_content = "";
        }
        else if( $set_enabled === 'false_already_voted') {
            $this->button = "";
            $this->star_readonly = 'true';
            $this->span_message_content = __('Thank you for voting!', 'yet-another-stars-rating');
        }
        elseif ($set_enabled === 'false_not_logged') {
            $this->button = $this->button_html_disabled;
            $this->star_readonly = 'true';
            $this->span_message_content = '<span class="yasr-visitor-votes-must-sign-in">';

            if (defined('YASR_CUSTOM_TEXT_MUST_SIGN_IN') && YASR_CUSTOM_TEXT_MUST_SIGN_IN !== '') {
                $this->span_message_content .= YASR_CUSTOM_TEXT_MUST_SIGN_IN;
            } else {
                $this->span_message_content .= __('You must sign in to vote', 'yet-another-stars-rating');
            }
            $this->span_message_content .= '</span>';
        }
    }
}