<?php

function get_showusers()
{
    global $DB;
    $record = $DB->get_records('user', array());
    return $record;
}

function get_sentmailusers()
{
    global $DB;
    $record = $DB->get_records('local_usermail', array('mailstatus' => 1));
    return $record;
}
