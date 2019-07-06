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
 * Prints a particular instance of checkdeadline
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_ausleihverwaltung
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace checkdeadline with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
include(__DIR__ . '/view_init.php');

do_header("/mod/ausleihverwaltung/lab_pieceMaterial_view.php");

if(!isset($usergroup[AUTH_LABORATORY_ENGINEER])) die("403 Unauthorized");

global $SESSION;

$strName = "Add new Resource";
echo $OUTPUT->heading($strName);

require_once(__DIR__ . '/forms/lab_newResourceForm.php');
$categories = $DB->get_records('hardware_rental_category',array());
$manufacturers = $DB->get_records('hardware_rental_manufacturer',array());
$tags = $DB->get_records('hardware_rental_tags',array());

$resourceform = new labNewResourceForm(
    array_map(function($value){return $value->category;},$categories),
    array_map(function($value){return $value->manufacturer;},$manufacturers),
    array_map(function($value){return $value->name;},$tags)
);

$resourceform ->render();

//Form processing and displaying is done here
if ($resourceform ->is_cancelled()) {

    unset($SESSION->formData1);
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url('./lab_resourcelist_view.php', array('id' => $cm->id)));

 } else if ($fromform = $resourceform ->get_data()) {
    if(!array_key_exists($fromform->category, $categories)){
        $record = new stdClass();
        $record->category = $fromform->category;
        $lastinsertid = $DB->insert_record('hardware_rental_category', $record, true);
        $fromform->category = $lastinsertid;
    }
    if(!array_key_exists($fromform->manufacturer, $manufacturers)){
        $record = new stdClass();
        $record->manufacturer = $fromform->manufacturer;
        $lastinsertid = $DB->insert_record('hardware_rental_manufacturer', $record, true);
        $fromform->manufacturer = $lastinsertid;
    }
    $normalized_tags = array();
    if(!empty($fromform->tags))
    foreach ($fromform->tags as $tag){
        if(array_key_exists($tag,$tags)){
            array_push($normalized_tags,array_map(function($value){return $value->name;},$tags)[$tag]);
        }else{
            $record = new stdClass();
            $record->name = $tag;
            $DB->insert_record('hardware_rental_tags', $record, false);
            array_push($normalized_tags, $tag);
        }
    }
    $fromform->tags = $normalized_tags;
    $SESSION->formData1 = $fromform;
    redirect(new moodle_url('./lab_pieceMaterial_view.php', array('id' => $cm->id)));

 
 } else {
    $formdata = array('id' => $id);
    $resourceform ->set_data($formdata);
    //displays the form
    $resourceform ->display();
 }

// Finish the page.
echo $OUTPUT->footer();
