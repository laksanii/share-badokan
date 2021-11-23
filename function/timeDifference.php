<?php
function timeDiff($datetime){
    $now = strtotime(date('Y-m-d H:i:s'));
    $diff = $now - strtotime($datetime);

    if($diff < 60){
        return "a few seconds ago";
    } elseif ($diff >= 60 && $diff < 3600){
        return round($diff / 60) . " minutes ago";
    } elseif ($diff >=  3600 && $diff < 86400) {
        return round($diff / 3600) . " hours ago";
    } elseif ($diff >= 86400 && $diff < (86400 * 30)){
        return round($diff / 86400) . " days ago";
    } elseif ($diff >= (86400 * 30) && $diff < (86400 * 365)){
        return round($diff / (86400 * 30)) . " months ago";
    } elseif ($diff >= (86400 * 365)){
        return round($diff / (86400 * 365)) . " years ago";
    }
}