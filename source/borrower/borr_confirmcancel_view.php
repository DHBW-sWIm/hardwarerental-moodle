<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Prints a particular instance of ausleihantrag
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_hardwarerental
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace ausleihantrag with the name of your module and remove this line.


require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
require_once(dirname(dirname(__FILE__)). '/lib.php');
require_once(dirname(dirname(__FILE__)) . '/locallib.php');
require_once(dirname(dirname(__FILE__))."/view_init.php");

do_header(substr(__FILE__, strpos(__FILE__,'/mod')));

$applicationid = optional_param('applicationid', 0, PARAM_INT);

$strName = "Your application is revoked";
echo $OUTPUT->heading($strName);

echo '<br>';

//data_read: hardwarerental_applications
$unfilteredList = $SESSION->applicationList;
$filteredList = array();
foreach ($unfilteredList as $application){
    if($application->id != $applicationid){
        echo $application->id . ' and ' . $applicationid;
        $filteredList[] = $application;
    }
}

//data_read: hardwarerental_applications
$SESSION->applicationList = $filteredList;

redirect(new moodle_url('./borr_applicationlist_view.php', array('id' => $cm->id)));

echo '<hr>';


echo $OUTPUT->single_button(new moodle_url('./main_borrower_view.php', array('id' => $cm->id)), 'Home');

echo $OUTPUT->footer();
