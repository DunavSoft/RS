<?php

defined('BASEPATH') OR exit('No direct script access allowed');

function theme_js($uri, $tag=false)
{
    if($tag)
    {
        return '<script type="text/javascript" src="'.base_url($uri).'"></script>';
    }
    else
    {
        return base_url('js/'.$uri);
    }
}

//you can fill the tag field in to spit out a link tag, setting tag to a string will fill in the media attribute
function theme_css($uri, $tag=false)
{
    if($tag)
    {
        $media=false;
        if(is_string($tag))
        {
            $media = 'media="'.$tag.'"';
        }
        return '<link href="'.base_url($uri).'" type="text/css" rel="stylesheet" '.$media.'/>';
    }
    
    return base_url('css/'.$uri);
} 