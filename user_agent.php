<?php
function get_user_info() {
  $ip = $_SERVER['REMOTE_ADDR'];
  $agent = $_SERVER['HTTP_USER_AGENT'];

  if (preg_match('/linux/i', $agent)) $os = 'Linux';
  elseif (preg_match('/mac/i', $agent)) $os = 'Mac';
  elseif (preg_match('/win/i', $agent)) $os = 'Windows';
  else $os = 'Desconocido';

  if (preg_match('/Chrome/i', $agent)) $browser = 'Chrome';
  elseif (preg_match('/Firefox/i', $agent)) $browser = 'Firefox';
  elseif (preg_match('/Safari/i', $agent)) $browser = 'Safari';
  else $browser = 'Otro';

  return [
    'ip' => $ip,
    'os' => $os,
    'browser' => $browser
  ];
}
