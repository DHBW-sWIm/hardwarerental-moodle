<?php
//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class labResourceEditForm extends moodleform {

    public function definition() {
        global $CFG;
        $mform = $this->_form; // Don't forget the underscore!

        /* ****************** ID *************/
        $mform->addElement('static', 'ident', 'ID:');
        $mform->setType('ident', PARAM_INT);
        $mform->setDefault('ident', '-');

        /* ****************** SERIAL *************/
        $mform->addElement('static', 'serial', 'Serial Number:');
        $mform->setType('serial', PARAM_INT);
        $mform->setDefault('serial', '-');

        /* ****************** NAME *************/
        $mform->addElement('text', 'name', 'Name:');
        $mform->setType('name', PARAM_NOTAGS);
        $mform->setDefault('name', '-');

        /* ****************** TYPE *************/
        $mform->addElement('static', 'category', 'Category:');
        $mform->setType('category', PARAM_NOTAGS);
        $mform->setDefault('category', '-');

        /* ****************** TYPE *************/
        $mform->addElement('static', 'type', 'Manufacturer:');
        $mform->setType('type', PARAM_NOTAGS);
        $mform->setDefault('type', '-');

        /* ****************** DESCRIPTION *************/
        $mform->addElement('text', 'description', 'Description:');
        $mform->setType('description', PARAM_NOTAGS);
        $mform->setDefault('description', '-');

        /* ****************** STATUS *************/
        $mform->addElement('static', 'status', 'Status:');
        $mform->setType('status', PARAM_NOTAGS);
        $mform->setDefault('status', '-');

        /* ****************** DATE *************/
        $mform->addElement('static', 'date', 'Return Date:');
        $mform->setType('date', PARAM_NOTAGS);
        $mform->setDefault('date', '-');

        /* ****************** QUANTITY *************/
        $mform->addElement('text', 'quantity', 'Quantity:');
        $mform->setType('quantity', PARAM_INT);
        $mform->setDefault('quantity', '1');

        /* ****************** COMMENT *************/
        $mform->addElement('text', 'comment', 'Comment:');
        $mform->setType('comment', PARAM_NOTAGS);
        $mform->setDefault('comment', '-');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'resourceid');
        $mform->setType('resourceid', PARAM_INT);

        $mform->addElement('submit', 'btnSubmit', 'Save Changes');
        $mform->addElement('cancel', 'cancelBtn', 'Cancel');

    }
    //Custom validation should be added here
    function validation($data, $files) {

        return array();
    }
}

