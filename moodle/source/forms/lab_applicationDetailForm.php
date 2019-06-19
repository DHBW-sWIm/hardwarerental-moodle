<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class labApplicationDetailForm extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** ID *************/
        $mform->addElement('static', 'type', 'Typ:');
        $mform->setType('type', PARAM_NOTAGS);
        $mform->setDefault('type', 'Ausleihantrag');

        /* ****************** NAME *************/
        $mform->addElement('static', 'name', 'Student:');
        $mform->setType('name', PARAM_NOTAGS);

        /* ****************** MATRNR *************/
        $mform->addElement('static', 'matr', 'Matrikelnummer:');
        $mform->setType('matr', PARAM_NOTAGS);

        /* ****************** MATRNR *************/
        $mform->addElement('static', 'email', 'Email:');
        $mform->setType('email', PARAM_NOTAGS);

        /* ****************** TYPE *************/
        $mform->addElement('static', 'status', 'Status:');
        $mform->setType('status', PARAM_NOTAGS);
        $mform->setDefault('status', 'Angefragt');

        /* ****************** STATUS *************/
        $mform->addElement('static', 'resource', 'Ressource:');
        $mform->setType('resource', PARAM_NOTAGS);

        /* ****************** DATE *************/
        $mform->addElement('static', 'date', 'Rückgabedatum:');
        $mform->setType('date', PARAM_NOTAGS);
        $mform->setDefault('date', '21.05.2019');

        /* ****************** QUANTITY *************/
        $mform->addElement('static', 'quantity', 'Anzahl');
        $mform->setType('quantity', PARAM_INT);
        $mform->setDefault('quantity', '1');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'taskid', 'Task ID:');
        $mform->setType('taskid', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Akzeptieren');
        $mform->addElement('cancel', 'cancelBtn', 'Ablehnen');

    }
    //Custom validation should be added here
    function validation($data, $files) {

        return array();
    }
}

