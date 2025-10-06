<?php
/**
 * Escape output for safe HTML.
 * @param string $v Raw value
 * @return string Escaped value
 */
function e($v) {
  return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}

/**
 * Post/Redirect/Get helper.
 * @param string $to URL or relative path to redirect to
 * @return void
 */
function redirect($to) {
  header('Location: ' . $to);
  exit;
}

/**
 * Read a JSON file into a PHP array.
 * @param string $file Absolute or relative path to the JSON file
 * @return array Decoded array (empty array if file missing or invalid JSON)
 */
function read_json($file) {
  if (!file_exists($file)) {
    return array();
  }
  $txt = file_get_contents($file);
  if ($txt === false || $txt === '') {
    return array();
  }
  $data = json_decode($txt, true); // <-- correct function name
  if (!is_array($data)) {
    return array();
  }
  return $data;
}

/**
 * Write a PHP array to a JSON file.
 * @param string $file Absolute or relative path to the JSON file
 * @param array  $data PHP array to write
 * @return void
 */
function write_json($file, $data) {
  // Use json_encode, not decode
  $json = json_encode($data, JSON_PRETTY_PRINT);
  file_put_contents($file, $json);
}
