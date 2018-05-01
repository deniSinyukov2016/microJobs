<?php
/**
 * @package     testers
 * @copyright   2017 A&M Digital Technologies
 * @author      John Muchiri
 * @link        https://amdtllc.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!function_exists('str_clean')) {
    function str_clean($string)
    {
        $string = str_replace(array('[\',\']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = preg_replace(array('/[^A-Za-z0-9-.]/i', '/[-]+/'), '-', $string);
        $string = strip_tags($string);
        return strtolower(trim($string, '-'));
    }
}
if (!function_exists('notices')) {
    function notices($type = 'info', $notice)
    {
        //todo pass many messages
        session()->flash('message', $notice);
        session()->flash('notice-type', $type);
    }
}
//TODO this code was added
/**
 * @param string|\Illuminate\Database\Eloquent\Model $class
 * @param null $count
 * @param array $overrides
 *
 * @return mixed
 */
function create($class, array $overrides = [], $count = null)
{
    return factory($class, $count)->create($overrides);
}
/**
 * @param string|\Illuminate\Database\Eloquent\Model $class
 * @param null|int $count
 * @param array $overrides
 *
 * @return mixed
 */
function make($class, array $overrides = [], $count = null)
{
    return factory($class, $count)->make($overrides);
}
?>