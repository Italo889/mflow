<?php
function getCache($key, $expiration = 60) {
    $cacheFile = "cache/" . md5($key) . ".json";
    if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < $expiration) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return false;
}

function setCache($key, $data) {
    $cacheFile = "cache/" . md5($key) . ".json";
    file_put_contents($cacheFile, json_encode($data));
}
?>
