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
 * Class YasrRankings
 */
class YasrRankings extends YasrShortcode {

    protected $query_highest_rated_overall;
    protected $query_result_most_rated_visitor;
    protected $query_result_highest_rated_visitor;
    protected $vv_highest_rated_table;
    protected $vv_most_rated_table;

    /**
     * Returns the shortcode for yasr_top_ten_highest_rated
     * */
    public function returnHighestRatedOverall () {
        $this->shortcode_html = '<!-- Yasr Most Or Highest Rated Shortcode-->';
        $this->query_highest_rated_overall = YasrRankingData::rankingOverallGetResults();
        $this->returnTableOverall();
        $this->shortcode_html .= '<!--End Yasr Top 10 highest Rated Shortcode-->';

        return $this->shortcode_html;
    }

    /**
     * @author Dario Curvino <@dudo>
     *
     * @param false $text_position
     * @param false $text
     */
    protected function returnTableOverall($text_position=false, $text=false) {
        if ($this->query_highest_rated_overall) {

            $this->shortcode_html .= "<table class='yasr-table-chart'>";

            $array_with_title = self::rankingData($this->query_highest_rated_overall);

            foreach ($array_with_title as $result) {
                $yasr_top_ten_html_id = 'yasr-highest_rated-' . str_shuffle(uniqid());

                $this->returnTableRows(
                    $result['post_id'],
                    $result['rating'],
                    null,
                    $result['post_title'],
                    $result['link'],
                    $yasr_top_ten_html_id
                );

            } //End foreach
            $this->shortcode_html .= "</table>";
        }
        else {
            _e("No data found", 'yet-another-stars-rating');
        }

    }

    /**
     * Create the queries for the rankings
     *
     * Return the full html for the shortcode
     *
     * @return string $this->shortcode_html;
     */
    public function vvReturnMostHighestRatedPost() {
        $this->shortcode_html = '<!-- Yasr Most Or Highest Rated Shortcode-->';

        $this->query_result_most_rated_visitor    = YasrRankingData::rankingVVGetResults(false, 'most');
        $this->query_result_highest_rated_visitor = YasrRankingData::rankingVVGetResults(false, 'highest');

        $this->vv_most_rated_table = "<table class='yasr-table-chart' id='yasr-most-rated-posts'>
                                          <tr class='yasr-visitor-votes-title'>
                                              <th>" . __('Post / Page', 'yet-another-stars-rating') . " </th>
                                              <th>" . __('Order By', 'yet-another-stars-rating') . ":&nbsp;&nbsp;
                                                  <span id='yasr_multi_chart_link_to_nothing'>"
                                                      . __('Most Rated', 'yet-another-stars-rating') .
                                                 "</span> | 
                                                  <span id='link-yasr-highest-rated-posts'>
                                                     <a href='#'>"
                                                         . __('Highest Rated', 'yet-another-stars-rating') .
                                                     "</a>
                                                  </span>
                                              </th>
                                          </tr>";

        $this->vv_highest_rated_table = "<table class='yasr-table-chart' id='yasr-highest-rated-posts'>
                                             <tr class='yasr-visitor-votes-title'>
                                             <th>" . __('Post / Page', 'yet-another-stars-rating') . "</th>
                                             <th>" . __('Order By', 'yet-another-stars-rating') . ":&nbsp;&nbsp; 
                                                 <span id='link-yasr-most-rated-posts'>
                                                     <a href='#'>"
                                                         . __("Most Rated", 'yet-another-stars-rating') .
                                                     "</a>
                                                     |
                                                 </span> 
                                                 <span id='yasr_multi_chart_link_to_nothing'>"
                                                     . __("Highest Rated", 'yet-another-stars-rating') .
                                                 "</span>
                                             </th>
                                          </tr>";

        $this->vvMostRated();
        $this->vvHighestRated();

        $this->shortcode_html .= '<!-- End Yasr Most Or Highest Rated Shortcode-->';

        return $this->shortcode_html;

    }

    /**
     * Loop the query for the Most Rated chart
     */
    protected function vvMostRated() {
        if ($this->query_result_most_rated_visitor) {
            $this->shortcode_html .= $this->vv_most_rated_table;
            $array_with_title = self::rankingData($this->query_result_most_rated_visitor);

            foreach ($array_with_title as $result) {
                $yasr_top_ten_html_id = 'yasr-10-most-rated-' . str_shuffle(uniqid());
                //print the rows
                $this->returnTableRows(
                    $result['post_id'],
                    $result['rating'],
                    $result['number_of_votes'],
                    $result['post_title'],
                    $result['link'],
                    $yasr_top_ten_html_id
                );
            } //End foreach
            $this->shortcode_html .= "</table>" ;

        } //End if $query_result_most_rated)

        else {
            $this->shortcode_html = __("You've not enough data",'yet-another-stars-rating') . "<br />";
        }
    }

    /**
     * Loop the query for the Highest Rated chart
     */
    protected function vvHighestRated () {
        if ($this->query_result_highest_rated_visitor) {
            $this->shortcode_html .= $this->vv_highest_rated_table;
            $array_with_title = self::rankingData($this->query_result_highest_rated_visitor);

            foreach ($array_with_title as $result) {
                $yasr_top_ten_html_id = 'yasr-10-highest-rater-' . str_shuffle(uniqid());
                //print the rows
                $this->returnTableRows(
                    $result['post_id'],
                    $result['rating'],
                    $result['number_of_votes'],
                    $result['post_title'],
                    $result['link'],
                    $yasr_top_ten_html_id
                );
            } //End foreach

            $this->shortcode_html .= "</table>";

        } //end if $query_result

        else {
            $this->shortcode_html = __("You've not enough data",'yet-another-stars-rating') . "<br />";
        }
    }

    /**
     * @param $post_id
     * @param $rating
     * @param $number_of_votes
     * @param $post_title
     * @param $link
     * @param $yasr_top_ten_html_id
     */
    protected function returnTableRows ($post_id, $rating, $number_of_votes, $post_title, $link, $yasr_top_ten_html_id) {
        $star_size = $this->starSize();

        $html_stars = "<div 
                           class='yasr-rater-stars'
                           id='$yasr_top_ten_html_id'
                           data-rater-postid='$post_id'
                           data-rater-starsize=$star_size
                           data-rating='$rating'>
                       </div>";

        //if number of votes === null means that the caller is loopHighestRatedOverall
        if ($number_of_votes === null) {

            $div_html_stars=apply_filters('yasr_filter_highest_rated_stars', $html_stars, $rating);

            if ($div_html_stars === $html_stars) {
                $div_html_stars .= "<span class='yasr-highest-rated-text'>"
                                   . __('Rating:', 'yet-another-stars-rating') . " $rating
                                    </span>";
            }

            $this->shortcode_html .= "<tr>
                                          <td class='yasr-top-10-overall-left'>
                                              <a href='$link'>$post_title</a>
                                          </td>
                                          <td class='yasr-top-10-overall-right'>
                                              $div_html_stars
                                          </td>
                                      </tr>";

        }

        //otherwise is vvMostRated or vvHighestRated
        else {
            $this->shortcode_html .= "<tr>
                                          <td class='yasr-top-10-most-highest-left'>
                                              <a href='$link'>$post_title</a>
                                          </td>
                                          <td class='yasr-top-10-most-highest-right'>
                                              $html_stars
                                              <br /> 
                                              ["
                                     . __('Total:', 'yet-another-stars-rating') .
                                     "$number_of_votes &nbsp;&nbsp;&nbsp;" .
                                     __('Average', 'yet-another-stars-rating') .
                                     " $rating]
                                          </td>
                                       </tr>";

        }
    } //end function returnTableRows

    /**
     * Returns an array with post titles and links
     *
     * @author Dario Curvino <@dudo>
     * @since 2.5.2
     *
     * @param $query_result array to loop; MUST have:
     * post_id
     * rating
     * (optional) number_of_votes
     *
     * @return array
     */
    public static function rankingData($query_result) {
        $data_array = array();

        $i=0;
        foreach ($query_result as $result) {
            $data_array[$i]['post_id']        = (int)$result->post_id;
            $data_array[$i]['rating']         = round($result->rating,1);
            if(isset($result->number_of_votes)){
                $data_array[$i]['number_of_votes'] = (int)$result->number_of_votes;
            }
            $data_array[$i]['post_title']     = wp_strip_all_tags(get_the_title($result->post_id));
            $data_array[$i]['link']           = get_permalink($result->post_id); //Get permalink from post id
            $i++;
        } //End foreach

        return $data_array;
    }

}