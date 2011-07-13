<?php
/**
 * Debug_HackerConsole_Main: write debug messages into hidden console.
 * (C) 2005 Dmitry Koterov, http://forum.dklab.ru/users/DmitryKoterov/
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 * See http://www.gnu.org/copyleft/lesser.html

 * Console may be toggled using Shift+Ctrl+` (tilde) combination.
 *
 * @version 1.05
 */

class Debug_HackerConsole_Main
{
    var $_hc_height = "400"; // height of the console (pixels)
    var $_hc_entries = array();
    var $TAB_SIZE = 4;


    /**
     * constructor($autoAttachToHtmlOutput=true)
     * Create new console. If $autoAttachToHtmlOutput, output buffering
     * handler is set to automatically attach JavaScript showing code to
     * HTML page.
     */
    function Debug_HackerConsole_Main($autoAttach=false)
    {
        if ($autoAttach) ob_start(array(&$this, '_obHandler'));
        $GLOBALS['Debug_HackerConsole_Main_LAST'] =& $this;
    }


    /**
     * string attachToHtml(string $pageHtml)
     * Attach the console to given HTML page.
     */
    function attachToHtml($page)
    {
        $js = file_get_contents(dirname(__FILE__).'/Js.js');
        if (get_magic_quotes_runtime()) $js = stripslashes($js);
        $js = str_replace('{HEIGHT}', $this->_hc_height, $js);
        $code = "var _console = new Debug_HackerConsole_Js();\n";
        foreach ($this->_hc_entries as $gid=>$elements) {
            foreach ($elements as $e) {
                if ($e['tip'] === null) {
                    $dr = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
                    $file = str_replace('\\', '/', $e['file']);
                    $file = preg_replace('{^'.preg_quote($dr,'{}').'}is', '~', $file);
                    $title = "at {$file} line {$e['line']}".
                        (!empty($e['function'])? ", {$e['function']}" : "");
                } else {
                    $title = $e['tip'];
                }
                $text = $this->toPre($e['text']);
                if (!empty($e['color'])) $text = "<div style=\"color:{$e['color']}\">$text</div>";
                $code .= "_console.out(".$this->_toJs($text).", ".$this->_toJs($title).", ".$this->_toJs($gid).");\n";
            }
        }
        $html = '';
        $html .= "</pre>";
        $html .= "\n";
        $html .= "<!-- ##################### -->\n";
        $html .= "<!-- ### HackerConsole ### -->\n";
        $html .= "<!-- ##################### -->\n";
        $html .= "<script type=\"text/javascript\" language=\"JavaScript\"><!--\n{$js}\n{$code}//--></script>\n";
        $page = $page . $html;
        return $page;
    }


    /**
     * void out(string $msg, string $group="message", $color=null, $tip=null)
     * Add new message to the console.
     * Messages may be grouped together using $group parameters for better view.
     * By default messages are tipped with caller context (file, line).
     * Contexts generated by call_user_func() are skipped!
     */
    function out($v, $group="message", $color=null, $tip=null)
    {
        // Work only with $obj, NOT $this!
        if (empty($this) || !is_a($this, __CLASS__)) {
            $obj =& $GLOBALS['Debug_HackerConsole_Main_LAST'];
        } else {
            $obj =& $this;
        }

        $stack = call_user_func(array(__CLASS__, 'debug_backtrace_smart'));
        $s = array_shift($stack);
        while (!empty($s['over_call_user_func'])) $s = array_shift($stack);

        if (is_scalar($v)) $text = "$v\n";
        else $text = Debug_HackerConsole_Main::print_r($v, true);
        $obj->_hc_entries[$group][] = array(
            'file'     => @$s['file'],
            'line'     => @$s['line'],
            'function' => @$s['function'],
            'text'     => $text,
            'color'    => $color,
            'tip'      => $tip,
        );
    }


    /**
     * string toPre($text)
     * Format plaintext like <pre> tag does, but with <br> at the line tails
     * and &nbsp; in line prefixes.
     */
    function toPre($text, $tabSize=null)
    {
        $text = htmlspecialchars($text);
        // Expand tabulators.
        if ($tabSize === null) {
            if (isset($GLOBALS['Debug_HackerConsole_Main_LAST']))
                $tabSize = $GLOBALS['Debug_HackerConsole_Main_LAST']->TAB_SIZE;
            else
                $tabSize = 4;
        }
        $text = Debug_HackerConsole_Main::expandTabs($text, $tabSize);
        $text = str_replace(' ', '&nbsp;', $text);
        $text = preg_replace('{\r?\n}sx', ' <br />', $text);
        return $text;
    }


    /**
     *  We need manual custom print_r() to use it in OB handlers
     * (original print_r() cannot work inside OB handler).
     */
    function print_r($obj, $no_print=0, $level=0)
    {
        if ($level < 7) {
            if (is_array($obj)) {
                $type = "Array[".count($obj)."]";
            } elseif (is_object($obj)) {
                $type = "Object";
            } elseif (gettype($obj) == "boolean") {
                $type = $obj? "TRUE" : "FALSE";
            } elseif ($obj == null) {
                $type = "NULL";
            } else {
                $type = preg_replace("/\r?\n/", "\\n", $obj);
            }
            $buf = $type;
            if (is_array($obj) || is_object($obj)) {
                $leftSp = str_repeat("    ", $level+1);
                for (reset($obj); list($k, $v) = each($obj); ) {
                    if ($k === "GLOBALS") continue;
                    $buf .= "\n{$leftSp}[$k] => ".Debug_HackerConsole_Main::print_r($v, $no_print, $level+1);
                }
            }
        } else {
            $buf = "*RECURSION*";
        }
        if ($no_print) return $buf;
        else echo $buf;
    }


    /**
     * string expandTabs($text, $tabSize=4)
     * Correctly convert tabulators to spaces.
     */
    function expandTabs($text, $tabSize=4)
    {
        $GLOBALS['expandTabs_tabSize'] = $tabSize;
        while (1) {
            $old = $text;
            $text = preg_replace_callback('/^(.*?)\t(\t*)(.*)/m', array(__CLASS__, 'expandTabs_callback'), $text);
            if ($old === $text) return $text;
        }
    }

    function expandTabs_callback($m)
    {
        $tabSize = $GLOBALS['expandTabs_tabSize'];
        $n =
            intval((strlen($m[1]) + $tabSize) / $tabSize) * $tabSize - strlen($m[1])
            + strlen($m[2]) * $tabSize;
        return $m[1] . str_repeat(' ', $n)  . $m[3];
    }


    /**
     * Internal methods.
     */

    function _obHandler($s)
    {
        return $this->attachToHtml($s);
    }


    function _toJs($a)
    {
        $a = addslashes($a);
        $a = str_replace("\n", '\n', $a);
        $a = str_replace("\r", '\r', $a);
        return "'$a'";
    }


    /**
     * array debug_backtrace_smart()
     * Wrapper around debug_backtrace(). Correctly work with call_user_func*
     * (totally skip them correcting caller references).
     * @version 1.00
     */
    function debug_backtrace_smart()
    {
        if (!is_callable('debug_backtrace')) return array();
        $trace = debug_backtrace();
        for ($i=1; $i<count($trace); $i++) {
            $func = strtolower(isset($trace[$i]['function'])? $trace[$i]['function'] : '');
            if ($func == 'call_user_func' || $func == 'call_user_func_array') {
                $trace[$i-1] = array_merge($trace[$i], $trace[$i-1]);
                $trace[$i-1]['over_call_user_func'] = true;
                array_splice($trace, $i, 1);
                $i--;
            }
        }
        array_shift($trace);
        return $trace;
    }
}


/**
 * Last created console.
 */
$GLOBALS['Debug_HackerConsole_Main_LAST'] = null;
?>