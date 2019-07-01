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

global $SESSION;

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... checkdeadline instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('ausleihverwaltung', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihverwaltung  = $DB->get_record('ausleihverwaltung', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $ausleihverwaltung->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('ausleihverwaltung', $ausleihverwaltung->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihverwaltung);
$event->trigger();


/* PAGE belegen*/
$PAGE->set_url('/mod/ausleihverwaltung/lab_new_resource_view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($ausleihverwaltung->name));
$PAGE->set_heading(format_string($course->fullname));

// Hier beginnt die Ausgabe
echo $OUTPUT->header();

$strName = "New Resource:";
echo $OUTPUT->heading($strName);

require_once(__DIR__ . '/forms/lab_newResourceForm.php');
$mform = new labNewResourceForm();

$mform->render();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {

    unset($SESSION->formData1);
    //Handle form cancel operation, if cancel button is present on form
    redirect(new moodle_url('../ausleihverwaltung/lab_resourcelist_view.php', array('id' => $cm->id)));

 } else if ($fromform = $mform->get_data()) {

    $SESSION->formData1 = $fromform;
    if($fromform->resourcetype == 1) {
        $categories = array('Smartphone', 'Tablet', 'Laptop', 'Computer','Software', 'Printer');
        $types = array('Apple', 'Samsung', 'Huawei', 'Xiaomi', 'Dell', 'Lenovo', 'Asus');
        $material = array('Piece Material', 'Bulk Material');

        $category = $categories[$fromform->category];
        $type = $types[$fromform->type];
        $resourcetype = $material[$fromform->resourcetype];

        if(array_search($fromform->name, array_column($SESSION->resourceList, 'name')) == 1){
            foreach($SESSION->resourceList as $resource) {
                if ($fromform->name == $resource->name) {
                    $resource->quantity += $fromform->quantity;
                    $resource->available += $fromform->available;
                    break;
                }
            }
        } else {
            $SESSION->resourceList[] = (object) array('name' => $fromform->name, 'comment' => $fromform->comment, 'description' => $fromform->description, 'category' => $category, 'resourcetype' => $resourcetype, 'type' => $type, 'quantity' => $fromform->quantity, 'available' => $fromform->quantity, 'status' => 'Available');
        }

        redirect(new moodle_url('../ausleihverwaltung/lab_resourcelist_view.php', array('id' => $cm->id)));
    } else {
        redirect(new moodle_url('../ausleihverwaltung/lab_pieceMaterial_view.php', array('id' => $cm->id)));
    }

 
 } else {
    $formdata = array('id' => $id);
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
 }

echo $OUTPUT->single_button(new moodle_url('../ausleihverwaltung/main_lab_view.php', array('id' => $cm->id)), 'Home');


// Finish the page.
echo $OUTPUT->footer();
