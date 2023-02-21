<?php

namespace Syltaen;

// ==================================================
// > SEND JSON
// ==================================================
function wp_send_json($data) {
    header("Content-Type: application/json");
    die(json_encode($data));
}

// ==================================================
// > CONFIG
// ==================================================
/**
 * Get a config item
 *
 * @return mixed
 */
function config($key)
{
    return Cache::value("global_config", function () {
        return set(include __DIR__ . "/config/config.php");
    })->get($key);
}

// =============================================================================
// > COMMON CLASSES
// =============================================================================

/**
 * Shortcut to create an new set instance from an array
 *
 * @param  array $array
 * @return Set
 */
function set($array = [])
{
    return new Set($array);
}