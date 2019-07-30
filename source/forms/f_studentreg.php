<?php
require_once("$CFG->libdir/formslib.php");


class fstudentreg extends moodleform {
    //Add elements to form

/*public function getcourseacronyms ()
    {
        global $DB;
        $courseacronym = $DB->get_records_sql('SELECT courseacronym FROM {studentregistration_course}');
        return ($courseacronym);
    }*/

    public function definition() {
        //$courseacronyms = getcourseacronyms();


        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('header', 'header', 'Enter a new student:');

        $mform->addElement('text', 'firstname', 'First name:');
        $mform->setType('firstname', PARAM_TEXT);
        $mform->addRule('firstname', 'Please enter a first name', 'required');

        $mform->addElement('text', 'surname', 'Surname:');
        $mform->setType('surname', PARAM_TEXT);
        $mform->addRule('surname', 'Please enter a surname', 'required');

        $mform->addElement('text', 'emailaddress', 'Email address:');
        $mform->setType('emailaddress', PARAM_TEXT);
        $mform->addRule('emailaddress', 'Please enter a valid email address', 'email', 'required');

        $mform->addElement('date_selector', 'birthdate', 'Birth date:', array(
            'startyear' => 1990,
            'stopyear'  => 2002,
            'optional'  => false
        ));
        $mform->setType('birthdate', PARAM_TEXT);
        $mform->addRule('birthdate', 'Please enter a valid birthdate', 'rule type', 'nonzero','client', false, false);

        $mform->addElement('text', 'course', 'Course:');
        $mform->setType('course', PARAM_TEXT);
        $mform->addRule('course', 'Please select a course', 'required');


        $mform->addElement('text', 'company', 'Company:');
        $mform->setType('company', PARAM_TEXT);
        $mform->addRule('company', 'Please enter a company', 'required');


        $mform->addElement('submit', 'btnSubmit', 'Submit');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_TEXT);

    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}
