<?php
require_once("$CFG->libdir/formslib.php");
require_once("$CFG->dirroot/lib/csvlib.class.php");
require_once("$CFG->dirroot/admin/tool/uploaduser/locallib.php");

class local_uploaduserform extends moodleform{
    public function definition(){
        global $CFG;
        $mform = $this->_form;
        $mform->addElement('header', 'uploadsetting', get_string('upload','local_demo'));

        $mform->addElement('filepicker', 'userfile', get_string('localfile','local_demo'));
        $mform->addRule('userfile', null, 'required');

        $mform->addElement('header', 'addonuploadsetting', get_string('uploadadditionalsettting','local_demo'));

        $choices = csv_import_reader::get_delimiter_list();
        $mform->addElement('select', 'delimiter_name', get_string('localcsvdelimiter', 'local_demo'), $choices);
        if (array_key_exists('cfg', $choices)) {
            $mform->setDefault('delimiter_name', 'cfg');
        } else if (get_string('listsep', 'langconfig') == ';') {
            $mform->setDefault('delimiter_name', 'semicolon');
        } else {
            $mform->setDefault('delimiter_name', 'comma');
        }

        $choices = core_text::get_encodings();
        $mform->addElement('select', 'encoding', get_string('localencoding', 'local_demo'), $choices);
        $mform->setDefault('encoding', 'UTF-8');

        $choices = array('10'=>10, '20'=>20, '100'=>100, '1000'=>1000, '100000'=>100000);
        $mform->addElement('select', 'previewrows', get_string('localrowpreviewnum', 'local_demo'), $choices);
        $mform->setType('previewrows', PARAM_INT);

        $this->add_action_buttons($cancel = true, $submitlabel = 'SUBMIT');
    }
}