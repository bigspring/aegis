<?php
function ui_set_message ($content) {
    $ci =& get_instance();
    $ci->session->set_flashdata("UI-MSG", $content);
}

function ui_set_error ($content) {
    $ci =& get_instance();
    $ci->session->set_flashdata("UI-ERR", $content);
}

function ui_set_notice ($content) {
    $ci =& get_instance();
    $ci->session->set_flashdata("UI-NTC", $content);
}

function ui_render ($type = "UI-ALL") {
    $ci =& get_instance();
    $message = $ci->session->flashdata('UI-MSG');
    $error = $ci->session->flashdata('UI-ERR');
    $notice = $ci->session->flashdata('UI-NTC');

    if ($type == "UI-MSG" and !empty($message)) {
    echo '<div id="notification-wrapper"><div id="notification" class="success">'.$message.'</div></div>';
    } elseif ($type == "UI-ERR" and !empty($error)) {
    echo '<div id="notification-wrapper"><div id="notification" class="error">'.$error.'</div></div>';
    } elseif ($type == "UI-NTC" and !empty($notice)) {
    echo '<div id="notification-wrapper" class="span-24"><div id="notification" class="notice">'.$notice.'</div></div>';
    } else {
    if (!empty($message)) {
        echo '<div id="notification-wrapper"><div id="notification" class="success">'.$message.'</div></div>';
    } elseif (!empty($error)) {
        echo '<div id="notification-wrapper"><div id="notification" class="error">'.$error.'</div></div>';
    } elseif (!empty($notice)) {
        echo '<div id="notification-wrapper"><div id="notification" class="notice">'.$notice.'</div></div>';
    }
    }
}

?>
