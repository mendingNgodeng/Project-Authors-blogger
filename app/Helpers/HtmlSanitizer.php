<?php
namespace App\Helpers;

use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlSanitizer
{
    public static function clean($dirtyHtml)
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set('HTML.Allowed', 'p,b,strong,i,em,u,ul,ol,li,br,hr,h1,h2,h3,h4,h5,h6,img[src|alt],span');

        $purifier = new HTMLPurifier($config);

        return $purifier->purify($dirtyHtml);
    }
}
