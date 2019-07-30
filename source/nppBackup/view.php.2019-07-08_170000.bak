<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

include(__DIR__ . '/view_init.php');

global $SESSION;

echo $OUTPUT->heading('Student Registration Landing Page');

// Implement form for user
// view.php gets start_form class which extends moodleform

    require_once(__DIR__ . '/forms/start_form.php');

    echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/v_dashboard.php', array('id' => $cm->id)),
    'Dashboard', $attributes = null);

    echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/v_demandreg.php', array('id' => $cm->id)),
    'Demand Planning', $attributes = null);

    echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/v_studentreg.php', array('id' => $cm->id)),
    'Student Registration', $attributes = null);

    echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/v_lecturehours.php', array('id' => $cm->id)),
    'Lecture Hours', $attributes = null);

    $mform = new start_form();

    $mform->render();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    $returnurl = new moodle_url('/mod/studentregistration/view.php', array('id' => $cm->id));
    redirect($returnurl);
} else {
    // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
    // or on the first display of the form.

    // Set default data (if any)
    // Required for module not to crash as a course id is always needed
    $formdata = array('id' => $id);
    $mform->set_data($formdata);
    //displays the form
    $mform->display();
}

// Finish the page.
echo $OUTPUT->footer();
