<?php

require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');

include(__DIR__ . '/view_init.php');

global $SESSION;

echo $OUTPUT->heading('Demand Planning');

// Implement form for user
// v_demandreg.php gets fdemandreg class which extends moodleform

    require_once(__DIR__ . '/forms/f_demandreg.php');

    $mform = new fdemandreg();

    $mform->render();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    //Handle form successful operation, if button is present on form
    $SESSION->formdata = $fromform;

    $record = new stdClass();
    $record->company      = $USER->institution;
    $record->year         = $fromform->year;
    $record->wi_se        = $fromform->wi_se;
    $record->wi_sc        = $fromform->wi_sc;
    $record->wi_am        = $fromform->wi_am;
    $record->wi_ds        = $fromform->wi_ds;
    $record->wi_eg        = $fromform->wi_eg;
    $record->wi_eh        = $fromform->wi_eh;
    $record->wi_imbit     = $fromform->wi_imbit;

    $lastinsertid = $DB->insert_record('studentregistration_demand', $record, false);

    $returnurl = new moodle_url('/mod/studentregistration/v_demandreg_resultpage.php', array('id' => $cm->id));
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

// navigate back to detail view (v_demandreg_resultpage.php)
echo $OUTPUT->single_button(new moodle_url('/mod/studentregistration/v_demandreg_resultpage.php', array('id' => $cm->id)),
    'To Details', $attributes = null);

// Finish the page.
echo $OUTPUT->footer();
