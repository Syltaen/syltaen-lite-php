<?php


// ==================================================
// > SEND JSON
// ==================================================
function wp_send_json($data) {
    header("Content-Type: application/json");
    die(json_encode($data));
}