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
 * All functions needed to work with MultiSet
 *
 * Class YasrMultiSetData
 */
class YasrMultiSetData {
    /**
     * @var array $array_to_return
     */
    public static $array_to_return = array();


    /****** Get multi set name ******/
    public static function returnMultiSetNames() {
        global $wpdb;

        $result = $wpdb->get_results("SELECT * FROM " . YASR_MULTI_SET_NAME_TABLE . " ORDER BY set_id");

        return $result;
    }

    /**
     * This function returns an multidimensional array of multiset ID and Fields
     *    array (
     *        array (
     *            'id' => '0',
     *            'name' => 'Field1',
     *        ),
     *        array (
     *            'id' => '1',
     *            'name' => 'Field2',
     *        ),
     *    )
     *
     * @param int $set_id
     * @return array|bool
     */

    public static function multisetFieldsAndID($set_id) {
        $set_id = (int)$set_id;

        global $wpdb;

        $result = $wpdb->get_results($wpdb->prepare(
            "SELECT f.field_id AS id, 
                    f.field_name AS name
                    FROM " . YASR_MULTI_SET_FIELDS_TABLE . " AS f
                    WHERE f.parent_set_id=%d
                    ORDER BY f.field_id
                    ", $set_id),
            ARRAY_A);

        if (empty($result)) {
            return false;
        }
        return $result;
    }

    /**
     * Get from the db all the values for VisitorMultiSet
     *
     * @param $post_id
     * @param $set_id
     *
     * @return array|bool
     */
    public static function returnMultisetContent($post_id, $set_id) {
        $set_id  = (int)$set_id;
        $post_id = (int)$post_id;

        if ($post_id === 0) {
            return false;
        }

        //set fields name and ids
        $set_fields = self::multisetFieldsAndID($set_id);

        if($set_fields === false) {
            return false;
        }

        return self::returnArrayFieldsRatings($set_id, $set_fields, $post_id);
    }

    /** This functions returns an array with all the value to print the multiset
     *
     * array (
     *     array (
     *         'value_id' => 0,
     *         'value_name' => 'Field 1',
     *         'value_rating' => 3.5,
     *     ),
     *     array (
     *         'value_id' => 1,
     *         'value_name' => 'Field 2',
     *         'value_rating' => 3,
     *     )
     *
     * @param integer $set_id the set id
     * @param array $set_fields an array with fields names and id
     * @param integer|bool $post_id the post_id
     *
     * @return bool | array
     */

    public static function returnArrayFieldsRatings($set_id, $set_fields, $post_id=false) {
        $set_id = (int)$set_id;

        if (!$set_fields) {
            return false;
        }

        if(!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        //get meta values (field id and rating)
        $set_post_meta_values = get_post_meta($post_id, 'yasr_multiset_author_votes', true);

        //index
        $i = 0;
        //always returns field id and name
        foreach ($set_fields as $fields_ids_and_names) {
            self::$array_to_return[$i]['id']     = (int) $fields_ids_and_names['id'];
            self::$array_to_return[$i]['name']   = $fields_ids_and_names['name'];
            self::$array_to_return[$i]['average_rating'] = 0;

            //if there is post meta
            if ($set_post_meta_values) {
                //first, loop saved fields and ratings
                foreach ($set_post_meta_values as $saved_set_id) {
                    //if the saved set is the same selected
                    if ($saved_set_id['set_id'] === $set_id) {
                        //loop the saved arrays
                        foreach ($saved_set_id['fields_and_ratings'] as $single_value) {
                            //if field id is the same, add the rating
                            if (self::$array_to_return[$i]['id'] === $single_value->field) {
                                //save the rating
                                self::$array_to_return[$i]['average_rating'] = $single_value->rating;
                            }
                        }
                    }
                }
            }
            //this is for list the set names
            $i ++;
        }
        return self::$array_to_return;
    }

    /**
     * Get from the db all the values for VisitorMultiSet
     *
     * @param $post_id
     * @param $set_type
     *
     * @return array|mixed|object|null
     */
    public static function returnVisitorMultiSetContent($post_id, $set_type) {
        $post_id = (int)$post_id;
        $set_type = (int)$set_type;

        global $wpdb;

        $result = $wpdb->get_results($wpdb->prepare("SELECT f.field_name AS name, 
                        f.field_id AS id, 
                        CAST((SUM(l.vote)/COUNT(l.vote)) AS DECIMAL(2,1)) AS average_rating,
                        COUNT(l.vote) AS number_of_votes
                        FROM " . YASR_MULTI_SET_FIELDS_TABLE . " AS f LEFT JOIN " . YASR_LOG_MULTI_SET . " AS l
                        ON l.post_id = %d
                        AND f.field_id = l.field_id
                        WHERE f.parent_set_id=%d
                        GROUP BY f.field_name, f.field_id
                        ORDER BY f.field_id", $post_id, $set_type), ARRAY_A);

        if (!empty($result)) {
            return $result;
        }
        return false;
    }


    /**
     * @param int $post_id
     * @param int $set_id
     * @param bool $visitor_multiset
     *
     * Get the post_id and the set id, return the average (float)
     *
     * @return float|int|false|
     */
    public static function returnMultiSetAverage($post_id, $set_id, $visitor_multiset) {
        $post_id = (int)$post_id;
        $set_id = (int)$set_id;

        if ($visitor_multiset === true) {
            $multiset_content = self::returnVisitorMultiSetContent($post_id, $set_id);
        } else {
            $multiset_content = self::returnMultisetContent($post_id, $set_id);
        }

        if (!is_array($multiset_content)) {
            return 0;
        }
        //default values
        $multiset_vote_sum = 0;
        $multiset_rows_number = 0;

        foreach ($multiset_content as $set_content) {
            $multiset_vote_sum = $multiset_vote_sum + $set_content['average_rating'];
            $multiset_rows_number = $multiset_rows_number+1;
        }

        return round( $multiset_vote_sum / $multiset_rows_number, 1);
    }

    /**
     * Returns *ALL* multiset votes in YASR_LOG_MULTI_SET
     * used in stats page
     *
     * @author Dario Curvino <@dudo>
     * @since 2.5.2
     *
     * @return array|object|null
     */
    public static function returnAllLogMulti() {
        global $wpdb;

        $query = 'SELECT * FROM ' .YASR_LOG_MULTI_SET. ' ORDER BY date, set_type, post_id DESC';

        return $wpdb->get_results($query, ARRAY_A);
    }

}
