<?php if (__FILE__ == $_SERVER['SCRIPT_FILENAME']) exit('No direct access allowed.');

/**
 * Load a config file
 */
function config_load($name) {

        $configuration = array();

        if (!file_exists(dirname(__FILE__) . '/config/' . $name . '.php'))
                die('The file ' . dirname(__FILE__) . '/config/' . $name . '.php does not exist.');

        require(dirname(__FILE__) . '/config/' . $name . '.php');

        if (!isset($config) OR !is_array($config))
                die('The file ' . dirname(__FILE__) . '/config/' . $name . '.php file does not appear to be formatted correctly.');

        if (isset($config) AND is_array($config))
                $configuration = array_merge($configuration, $config);

        return $configuration;

}

/**
 * Load a config item
 */
function config_item($name, $item) {

        static $config_item = array();

        if (!isset($config_item[$item])) {

                $config = config_load($name);

                if (!isset($config[$item]))
                        return FALSE;

                $config_item[$item] = $config[$item];

        }

        return $config_item[$item];

}

/**
 * Autoloading classes
 */
function __autoload($class_name) {

        require_once('libraries/' . $class_name . '.php');

}

/**
 * Get categories
 */
function get_categories($category_id, $current_category = '') {

        global $cart, $list_id, $last_id;

        if (isset($list_id))
                $selected_id = array_shift($list_id);
        else
                $selected_id = array();

        $categories = $cart->get_categories($category_id);

        $output  = '';

        if ($categories)
                $output .= '<ul>';

        foreach ($categories as $row) {

                if (!$current_category)
                        $url = $row['category_id'];
                else
                        $url = $current_category . '|' . $row['category_id'];

                $output .= '<li>';

                $children = '';

                if ($selected_id == $row['category_id'])
                        $children = get_categories($row['category_id'], $url);

                if ($last_id == $row['category_id'])
                        $output .= '<a href="category.php?category_id=' . $url . '"><strong>' . $row['category_name'] . '</strong></a>';
                else
                        $output .= '<a href="category.php?category_id=' . $url . '">' . $row['category_name'] . '</a>';

                $output .= $children;
                $output .= '</li>';

        }

        if ($categories)
                $output .= '</ul>';

        return $output;

}

function dateDiff($dformat, $endDate, $beginDate)
{
$date_parts1=explode($dformat, $beginDate);
$date_parts2=explode($dformat, $endDate);
$start_date=gregoriantojd($date_parts1[1], $date_parts1[2], $date_parts1[0]);
$end_date=gregoriantojd($date_parts2[1], $date_parts2[2], $date_parts2[0]);

return $end_date - $start_date;
}

