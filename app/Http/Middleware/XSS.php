<?php

namespace App\Http\Middleware;
use App\Libs\Lib;
use Closure;
use Illuminate\Http\Request;

class XSS
{
    protected $allow_tags = [
        '<a>','<h1>','<h2>','<h3>','<h4>','<h5>','<h6>',
        '<p>','<div>','<span>','<ul>','<li>','<ol>',
        '<strong>','<b>','<i>','<u>','<em>','<strike>','<code>','<mark>','<hr>','<br>','<img>'
    ];

    public function handle(Request $request, Closure $next)
    {
        $env = Lib::appEnv();
        $input = $request->all();
        if($env == 'admin') {
            array_walk_recursive($input, function (&$input, $key){
                $input = trim($input);
                $allowScript = $this->allowScriptForInputName();
                if(!in_array($key, $allowScript)){
                    $input = $this->strip_tags_attributes($input, $this->allow_tags);
                }
            });
        }else {

            array_walk_recursive($input, function (&$input) {
                $input = trim($input);
                $input = strip_tags($input);
                $input = str_replace("&#032;", " ", $input);
                $input = str_replace(chr(0xCA), "", $input);  //Remove sneaky spaces
                $input = str_replace(array("<!--", "-->", "/<script/i", ">", "<", '"', "/\\\$/", "/\r/", "!", "'", "=", "+"), array("", "", "&#60;script", "&gt;", "&lt;", "&quot;", "&#036;", "", "&#33;", "&#39;", "", " "), $input);
                $input = preg_replace("/\\\(?!&amp;#|\?#)/", "&#092;", $input);
            });
        }
        $request->merge($input);
        return $next($request);
    }

    function strip_tags_attributes($sSource, $aAllowedTags = array(), $aDisabledAttributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'))
    {
        if (empty($aDisabledAttributes)) return strip_tags($sSource, implode('', $aAllowedTags));
        $str = strip_tags($sSource, implode('', $aAllowedTags));

        Lib::set('aDisabledAttributes', $aDisabledAttributes);
        return preg_replace_callback('/<(.*?)>/i',
            function ($matches) {
                $aDisabledAttributes = Lib::get('aDisabledAttributes', []);
                if (!empty($aDisabledAttributes)) {
                    foreach ($matches as $match) {
                        return preg_replace(
                            [
                                //'/javascript:[^\"\']*/i',
                                '/(' . implode('|', $aDisabledAttributes) . ')[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i',
                                '/\s+/'
                            ],
                            [/*'',*/
                                '', ' '],
                            stripslashes($match)
                        );
                    }
                }
            }, $str);
    }

    private function allowScriptForInputName() {
        return ['ga', 'gtag_noscript', 'gtag'];
    }
}