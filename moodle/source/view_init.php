<?php

// DO NOT TOUCH THIS FILE

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n = optional_param('n', 0, PARAM_INT);  // ... ausleihverwaltung instance ID - it should be named as the first character of the module.

if ($id) {
    $cm = get_coursemodule_from_id('ausleihverwaltung', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $ausleihverwaltung = $DB->get_record('ausleihverwaltung', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $ausleihverwaltung = $DB->get_record('ausleihverwaltung', array('id' => $n), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $ausleihverwaltung->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('ausleihverwaltung', $ausleihverwaltung->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$usergroup = array();

$cm->groups = $DB->get_records('groups', array('courseid' => $course->id));
foreach ($cm->groups as $group){
    $group->participants = $DB->get_records('groups_members', array('groupid' => $group->id));
    foreach ($group->participants as $participant){
        if($participant->userid == $USER->id){
            $usergroup[$group->idnumber] = $group;
        }
    }
}

const AUTH_LABORATORY_ENGINEER = 101;
const AUTH_DHBW_AUTHORITY = 102;

$event = \mod_ausleihverwaltung\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $ausleihverwaltung);
$event->trigger();

// Print the page header.
function do_header($url){

    global $PAGE, $OUTPUT, $ausleihverwaltung, $cm, $course;
    $PAGE->set_url($url, array('id' => $cm->id));
    $PAGE->set_title(format_string($ausleihverwaltung->name));
    $PAGE->set_heading(format_string($course->fullname));

    // Output starts here.
    echo $OUTPUT->header();

    // Conditions to show the intro can change to look for own settings or whatever.
    if ($ausleihverwaltung->intro) {
        echo $OUTPUT->box(format_module_intro('ausleihverwaltung', $ausleihverwaltung, $cm->id), 'generalbox mod_introbox', 'ausleihverwaltungintro');
    }
}