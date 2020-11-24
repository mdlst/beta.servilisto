<?php

/*
* Title                   : DOP Prototypes (PHP class)
* Version                 : 1.0.3
* File                    : dop-prototypes.php
* File Version            : 1.0.3
* Created / Last Modified : 19 January 2016
* Author                  : Dot on Paper
* Copyright               : © 2014 Dot on Paper
* Website                 : http://www.dotonpaper.net
* Description             : List of general functions that we use at Dot on Paper.
* Licence                 : MIT
*/

    if (!class_exists('DOPPrototypes')){
        class DOPPrototypes{
            /*
             * Constructor
             */
            function __construct(){
            }
            
/*
 * Date/time 
 */         
            /*
             * Converts time to AM/PM format.
             * 
             * @param $time (string): time that will returned in format HH:MM
             * 
             * @return time to AM/PM format
             */
            function getAMPM($time){
                $time_pieces = explode(':', $time);
                $hour = (int)$time_pieces[0];
                $minutes = $time_pieces[1];
                $result = '';

                if ($hour == 0){
                    $result = '12';
                }
                else if ($hour > 12){
                    $result = $this->getLeadingZero($hour-12);
                }
                else{
                    $result = $this->getLeadingZero($hour);
                }

                $result .= ':'.$minutes.' '.($hour < 12 ? 'AM':'PM');

                return $result;
            }
            
            /*
             * Get hours list.
             * 
             * @param start_hour (string): start hour
             * @param end_hour (integer): end hour
             * @param diff (integer): difference between hours in minutes
             * 
             * @return array with hours
             */
            function getHours($start_hour = '00:00',
                              $end_hour = '23:59',
                              $diff = 5){
                $hours = array();
                $hour = '';
                $curr_hour = 0;
                $curr_minute = 0;
                
                array_push($hours, $start_hour);
                
                while ($hour < $end_hour && $hour < '23:59'){
                    $curr_minute += $diff;
                    
                    if ($curr_minute >= 60){
                        $curr_hour++;
                        $curr_minute = $curr_minute-60;
                    }
                    
                    $hour = $this->getLeadingZero($curr_hour).':'.$this->getLeadingZero($curr_minute);
                    $hour = $hour == '24:00' ? '23:59':$hour;
                    $hour >= $start_hour ? array_push($hours, $hour):'';
                }
                
                return $hours;
            }
            
            /*
             * Returns date in requested format.
             * 
             * @param $date (string): date that will be returned in format YYYY-MM-DD
             * @param $type (string): '1' for american ([month name] DD, YYYY)
             *                      : '2' for european (DD [month name] YYYY)
             * 
             * @return date to format
             */
            function setDateToFormat($date, 
                                     $type, 
                                     $month_names = array('January',
                                                          'February',
                                                          'March',
                                                          'April',
                                                          'May',
                                                          'June',
                                                          'July',
                                                          'August',
                                                          'September',
                                                          'October',
                                                          'November',
                                                          'December')){
                $dayPieces = explode('-', $date);

                if ($type == '1'){
                    return $month_names[(int)$dayPieces[1]-1].' '.$dayPieces[2].', '.$dayPieces[0];
                }
                else{
                    return $dayPieces[2].' '.$month_names[(int)$dayPieces[1]-1].' '.$dayPieces[0];
                }
            }
            
/*
 * String & numbers            
 */ 
            /*
             * Changes all characters from a string that are not in english alphabet to english alphabet.
             * 
             * @param string (string): the string
             * 
             * @return the string with all non-english characters changed
             */
            function getEnglishCharacters($string){
                $characters = array('á' => 'a', 'Á' => 'A', 'à' => 'a', 'À' => 'A', 'ă' => 'a', 'Ă' => 'A', 'â' => 'a', 'Â' => 'A', 'å' => 'a', 'Å' => 'A', 'ã' => 'a', 'Ã' => 'A', 'ą' => 'a', 'Ą' => 'A', 'ā' => 'a', 'Ā' => 'A', 'ä' => 'ae', 'Ä' => 'AE', 'æ' => 'ae', 'Æ' => 'AE', 'ḃ' => 'b', 'Ḃ' => 'B', 'ć' => 'c', 'Ć' => 'C', 'ĉ' => 'c', 'Ĉ' => 'C', 'č' => 'c', 'Č' => 'C', 'ċ' => 'c', 'Ċ' => 'C', 'ç' => 'c', 'Ç' => 'C', 'ď' => 'd', 'Ď' => 'D', 'ḋ' => 'd', 'Ḋ' => 'D', 'đ' => 'd', 'Đ' => 'D', 'ð' => 'dh', 'Ð' => 'Dh', 'é' => 'e', 'É' => 'E', 'è' => 'e', 'È' => 'E', 'ĕ' => 'e', 'Ĕ' => 'E', 'ê' => 'e', 'Ê' => 'E', 'ě' => 'e', 'Ě' => 'E', 'ë' => 'e', 'Ë' => 'E', 'ė' => 'e', 'Ė' => 'E', 'ę' => 'e', 'Ę' => 'E', 'ē' => 'e', 'Ē' => 'E', 'ḟ' => 'f', 'Ḟ' => 'F', 'ƒ' => 'f', 'Ƒ' => 'F', 'ğ' => 'g', 'Ğ' => 'G', 'ĝ' => 'g', 'Ĝ' => 'G', 'ġ' => 'g', 'Ġ' => 'G', 'ģ' => 'g', 'Ģ' => 'G', 'ĥ' => 'h', 'Ĥ' => 'H', 'ħ' => 'h', 'Ħ' => 'H', 'í' => 'i', 'Í' => 'I', 'ì' => 'i', 'Ì' => 'I', 'î' => 'i', 'Î' => 'I', 'ï' => 'i', 'Ï' => 'I', 'ĩ' => 'i', 'Ĩ' => 'I', 'į' => 'i', 'Į' => 'I', 'ī' => 'i', 'Ī' => 'I', 'ĵ' => 'j', 'Ĵ' => 'J', 'ķ' => 'k', 'Ķ' => 'K', 'ĺ' => 'l', 'Ĺ' => 'L', 'ľ' => 'l', 'Ľ' => 'L', 'ļ' => 'l', 'Ļ' => 'L', 'ł' => 'l', 'Ł' => 'L', 'ṁ' => 'm', 'Ṁ' => 'M', 'ń' => 'n', 'Ń' => 'N', 'ň' => 'n', 'Ň' => 'N', 'ñ' => 'n', 'Ñ' => 'N', 'ņ' => 'n', 'Ņ' => 'N', 'ó' => 'o', 'Ó' => 'O', 'ò' => 'o', 'Ò' => 'O', 'ô' => 'o', 'Ô' => 'O', 'ő' => 'o', 'Ő' => 'O', 'õ' => 'o', 'Õ' => 'O', 'ø' => 'oe', 'Ø' => 'OE', 'ō' => 'o', 'Ō' => 'O', 'ơ' => 'o', 'Ơ' => 'O', 'ö' => 'oe', 'Ö' => 'OE', 'ṗ' => 'p', 'Ṗ' => 'P', 'ŕ' => 'r', 'Ŕ' => 'R', 'ř' => 'r', 'Ř' => 'R', 'ŗ' => 'r', 'Ŗ' => 'R', 'ś' => 's', 'Ś' => 'S', 'ŝ' => 's', 'Ŝ' => 'S', 'š' => 's', 'Š' => 'S', 'ṡ' => 's', 'Ṡ' => 'S', 'ş' => 's', 'Ş' => 'S', 'ș' => 's', 'Ș' => 'S', 'ß' => 'SS', 'ť' => 't', 'Ť' => 'T', 'ṫ' => 't', 'Ṫ' => 'T', 'ţ' => 't', 'Ţ' => 'T', 'ț' => 't', 'Ț' => 'T', 'ŧ' => 't', 'Ŧ' => 'T', 'ú' => 'u', 'Ú' => 'U', 'ù' => 'u', 'Ù' => 'U', 'ŭ' => 'u', 'Ŭ' => 'U', 'û' => 'u', 'Û' => 'U', 'ů' => 'u', 'Ů' => 'U', 'ű' => 'u', 'Ű' => 'U', 'ũ' => 'u', 'Ũ' => 'U', 'ų' => 'u', 'Ų' => 'U', 'ū' => 'u', 'Ū' => 'U', 'ư' => 'u', 'Ư' => 'U', 'ü' => 'ue', 'Ü' => 'UE', 'ẃ' => 'w', 'Ẃ' => 'W', 'ẁ' => 'w', 'Ẁ' => 'W', 'ŵ' => 'w', 'Ŵ' => 'W', 'ẅ' => 'w', 'Ẅ' => 'W', 'ý' => 'y', 'Ý' => 'Y', 'ỳ' => 'y', 'Ỳ' => 'Y', 'ŷ' => 'y', 'Ŷ' => 'Y', 'ÿ' => 'y', 'Ÿ' => 'Y', 'ź' => 'z', 'Ź' => 'Z', 'ž' => 'z', 'Ž' => 'Z', 'ż' => 'z', 'Ż' => 'Z', 'þ' => 'th', 'Þ' => 'Th', 'µ' => 'u', 'а' => 'a', 'А' => 'a', 'б' => 'b', 'Б' => 'b', 'в' => 'v', 'В' => 'v', 'г' => 'g', 'Г' => 'g', 'д' => 'd', 'Д' => 'd', 'е' => 'e', 'Е' => 'E', 'ё' => 'e', 'Ё' => 'E', 'ж' => 'zh', 'Ж' => 'zh', 'з' => 'z', 'З' => 'z', 'и' => 'i', 'И' => 'i', 'й' => 'j', 'Й' => 'j', 'к' => 'k', 'К' => 'k', 'л' => 'l', 'Л' => 'l', 'м' => 'm', 'М' => 'm', 'н' => 'n', 'Н' => 'n', 'о' => 'o', 'О' => 'o', 'п' => 'p', 'П' => 'p', 'р' => 'r', 'Р' => 'r', 'с' => 's', 'С' => 's', 'т' => 't', 'Т' => 't', 'у' => 'u', 'У' => 'u', 'ф' => 'f', 'Ф' => 'f', 'х' => 'h', 'Х' => 'h', 'ц' => 'c', 'Ц' => 'c', 'ч' => 'ch', 'Ч' => 'ch', 'ш' => 'sh', 'Ш' => 'sh', 'щ' => 'sch', 'Щ' => 'sch', 'ъ' => '', 'Ъ' => '', 'ы' => 'y', 'Ы' => 'y', 'ь' => '', 'Ь' => '', 'э' => 'e', 'Э' => 'e', 'ю' => 'ju', 'Ю' => 'ju', 'я' => 'ja', 'Я' => 'ja');
                
                return str_replace(array_keys($characters), array_values($characters), $string);
            }
            
            /*
             * Adds a leading 0 if number smaller than 10.
             * 
             * @param no (integer): the number
             * 
             * @return number with leading 0 if needed
             */
            function getLeadingZero($no){
                if ($no < 10){
                    return '0'.$no;
                }
                else{
                    return $no;
                }
            }
            
            /*
             * Creates a string with random characters.
             * 
             * @param string_length (integer): the length of the returned string
             * @param allowed_characters (string): the string of allowed characters
             * 
             * @return random string
             */
            function getRandomString($string_length,
                                     $allowed_characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz'){
                $random_string = '';

                for ($i=0; $i<$string_length; $i++){
                    $characters_position = mt_rand(1, strlen($allowed_characters))-1;
                    $random_string .= $allowed_characters[$characters_position];
                }
                
                return $random_string;
            }
            
            /*
             * Returns a number with a predefined number of decimals.
             * 
             * @param number (float): the number
             * @param no (integer): the number of decimals
             * 
             * @return string with number and decimals
             */
            function getWithDecimals($number, 
                                     $no = 2){
                return (int)$number == $number ? (string)$number:number_format($number, $no, '.', '');
            }
            
            /*
             * Email validation.
             * 
             * @param email (string): email to be checked
             * 
             * @return true/false
             */
            function validEmail($email){
                if (preg_match("/^[a-z0-9&\'\.\-_\+]+@[a-z0-9\-]+\.([a-z0-9\-]+\.)*+[a-z]{2}/is", $email)){
                    return true;
                }
                else{
                    return false;
                }
            }        
        }
    }