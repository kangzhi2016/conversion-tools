<?php

date_default_timezone_set('Asia/Shanghai');

$result = array();

function set_result( $uid, $arg, $title, $sub, $icon)
{
    $item = array(
        'uid' => $uid,
        'arg' => $arg,
        'title' => $title,
        'subtitle' => $sub,
        'icon' => $icon
    );

    $GLOBALS['result']['items'][] = $item;
}

function echo_result()
{
    echo json_encode($GLOBALS['result']);
}
