<?php

/**
 +-------------------------------------------------------------------------+
 | RubioDMARC  - An OpenDMARC Webapp                                       |
 | Version 1.0.0                                                           |
 |                                                                         |
 | This program is free software: you can redistribute it and/or modify    |
 | it under the terms of the GNU General Public License as published by    |
 | the Free Software Foundation.                                           |
 |                                                                         |
 | This file forms part of the RubioDMARC software.                        |
 |                                                                         |
 | If you wish to use this file in another project or create a modified    |
 | version that will not be part of the RubioDMARC Software, you           |
 | may remove the exception above and use this source code under the       |
 | original version of the license.                                        |
 |                                                                         |
 | This program is distributed in the hope that it will be useful,         |
 | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the            |
 | GNU General Public License for more details.                            |
 |                                                                         |
 | You should have received a copy of the GNU General Public License       |
 | along with this program.  If not, see http://www.gnu.org/licenses/.     |
 |                                                                         |
 +-------------------------------------------------------------------------+
 | Author: Jaime Rubio <jaime@rubiogafsi.com>                              |
 +-------------------------------------------------------------------------+
 */

namespace RubioDMARC\Framework;

defined('_ODEXEC') or die;

use RubioDMARC\Framework\Language\Text;

class Helpers
{

    /**
     * Provides a name of the DMARC Policy depending on the value 'policy' of the recorded message
     * DMARC Policy: 14=Unknown , 15=Pass , 16=Reject , 17=Quarantine , 18=None
     * This is the parameter 'p' in the TXT record of the DMARC DNS
     */

    public static function getPolicy(&$item)
    {
        if (empty($item->policy))
            return;

        switch ($item->policy) {
            case POLICY_PASS:
                $item->policy_txt = Text::_('POLICY_PASS');
                $item->color = 'green';
                break;
            case POLICY_UNKNOWN:
                $item->policy_txt = Text::_('POLICY_UNKNOWN');
                $item->color = 'yellow';
                break;                
            case POLICY_NONE:
                $item->policy_txt = Text::_('POLICY_NONE');
                $item->color = 'blue';
                break;                
            case POLICY_REJECT:
                $item->policy_txt = Text::_('POLICY_REJECT');
                $item->color = 'red';
                break;
            case POLICY_QUARANTINE:
                $item->policy_txt = Text::_('POLICY_QUARANTINE');
                $item->color = 'orange';
                break;
            default:
                $item->policy_txt = Text::_('UNVALID');
                $item->color = 'darkgray';
        }
        return $item;
    }

    /**
     *
     */
    public static function getDisposition(&$item)
    {
        if (empty($item->disp))
            return;

        switch ($item->disp) {
            case 0:
            case 1:
                $item->disp_txt = Text::_('SPF_POLICY_REJECT');
                break;
            case 2:
                $item->disp_txt = Text::_('SPF_POLICY_NONE');
                break;
            case 3:
                $item->disp_txt = Text::_('SPF_POLICY_QUARANTINE');
                break;
            default:
                $item->disp_txt = Text::_('UNVALID');
        }
        return $item;
    }

    /**
     *
     */
    public static function getAlignmentSet(&$item)
    {
        if (empty($item->align_spf) || empty($item->align_dkim))
            return;

        switch ($item->align_spf) {
            case ALIGNMENT_SET:
                $item->align_spf_txt = Text::_('ALIGNMENT_SET_YES');
                break;
            default:
                $item->align_spf_txt = Text::_('ALIGNMENT_SET_NO');
        }

        switch ($item->align_dkim) {
            case ALIGNMENT_SET:
                $item->align_dkim_txt = Text::_('ALIGNMENT_SET_YES');
                break;
            default:
                $item->align_dkim_txt = Text::_('ALIGNMENT_SET_NO');
        }
        return $item;
    }


    /**
     *
     */
    public static function getEvaluation(&$item)
    {
        switch ($item->spf) {
            case EVAL_PASS:
                $item->spf_txt = Text::_('EVALUATION_PASS');
                break;
            case EVAL_FAIL:
                $item->spf_txt = Text::_('EVALUATION_FAIL');
                break;
            case EVAL_NONE:
                $item->spf_txt = Text::_('EVALUATION_NONE');
                break;
            default:
                $item->spf_txt = Text::_('EVALUATION_NOT_EVALUATED');
        }

        switch ($item->dkim) {
            case EVAL_PASS:
                $item->dkim_txt = Text::_('EVALUATION_PASS');
                break;
            case EVAL_FAIL:
                $item->dkim_txt = Text::_('EVALUATION_FAIL');
                break;
            case EVAL_NONE:
                $item->dkim_txt = Text::_('EVALUATION_NONE');
                break;
            default:
                $item->dkim_txt = Text::_('EVALUATION_NOT_EVALUATED');
        }
        return $item;
    }

    public static function getTopItems($items = [], $limit = 5)
    {
        $ret = [];
        $i = $topfive = $total = 0;

        if (is_array($items)) {
            foreach ($items as $item) {
                if ($i < $limit) {
                    $topfive += $item->cnt;
                    $ret[] = $item;
                }
                $total += $item->cnt;
                $i++;
            }
        }

        //Rest of World
        $row = new \stdClass();
        $row->name = Text::_('OTHERS');
        $row->cnt = $total - $topfive;
        $ret[] = $row;

        return $ret;
    }

    /**
     * Published policy's alignment rule for DKIM and SPF
     */
    public static function getAlignmentMode(&$item)
    {

        if (empty($item->aspf)) return $item;

        switch ($item->aspf) {
            case ALIGNMENT_RULE_RELAXED:
                $item->aspf_txt = Text::_('ALIGNMENT_MODE_RELAXED');
                break;
            case ALIGNMENT_RULE_STRICT:
                $item->aspf_txt = Text::_('ALIGNMENT_MODE_STRICT');
                break;
            default:
                $item->aspf_txt = Text::_('ALIGNMENT_MODE_UNKNOWN');
        }

        switch ($item->adkim) {
            case ALIGNMENT_RULE_RELAXED:
                $item->adkim_txt = Text::_('ALIGNMENT_MODE_RELAXED');
                break;
            case ALIGNMENT_RULE_STRICT:
                $item->adkim_txt = Text::_('ALIGNMENT_MODE_STRICT');
                break;
            default:
                $item->adkim_txt = Text::_('ALIGNMENT_MODE_UNKNOWN');
        }

        return $item;
    }


    /**
     * Domain policy
     */
    public static function getDomainPolicy(&$item)
    {
        if (empty($item->policy)) return $item;

        switch ($item->policy) {
            case 0:
                $item->policy_txt = Text::_('POLICY_UNKNOWN');
                break;
            case 114:
                $item->policy_txt = Text::_('POLICY_NONE');
                break;
            case 113:
                $item->policy_txt = Text::_('POLICY_QUARANTINE');
                break;
            case 110:
                $item->policy_txt = Text::_('POLICY_REJECT');
                break;
            default:
                $item->policy_txt = Text::_('UNVALID');
        }
        return $item;
    }

    /**
     * Subdomain policy
     */
    public static function getSubdomainPolicy(&$item)
    {
        if (empty($item->policy)) return $item;

        switch ($item->spolicy) {
            case 0:
                $item->spolicy_txt = Text::_('POLICY_UNKNOWN');
                break;
            case 114:
                $item->spolicy_txt = Text::_('POLICY_NONE');
                break;
            case 113:
                $item->spolicy_txt = Text::_('POLICY_QUARANTINE');
                break;
            case 110:
                $item->spolicy_txt = Text::_('POLICY_REJECT');
                break;
            default:
                $item->spolicy_txt = Text::_('UNVALID');
        }
        return $item;
    }

    /**
     * ARC evaluation (0 = pass, 2 = fail)
     */
    public static function getARCEvaluation(&$item)
    {
        if (empty($item->arc)) return $item;

        switch ($item->arc) {
            case 0:
                $item->arc_txt = Text::_('ARC_PASS');
                break;
            case 2:
                $item->arc_txt = Text::_('ARC_SOFTFAIL');
                break;
            case 3:
                $item->arc_txt = Text::_('ARC_NEUTRAL');
                break;
            case 4:
                $item->arc_txt = Text::_('ARC_TEMPERROR');
                break;
            case 5:
                $item->arc_txt = Text::_('ARC_PERMERROR');
                break;
            case 6:
                $item->arc_txt = Text::_('ARC_NONE');
                break;
            case 7:
                $item->arc_txt = Text::_('ARC_FAIL');
                break;
            case 8:
                $item->arc_txt = Text::_('ARC_POLICY');
                break;
            case 9:
                $item->arc_txt = Text::_('ARC_NXDOMAIN');
                break;
            case 10:
                $item->arc_txt = Text::_('ARC_SIGNED');
                break;
            case 11:
                $item->arc_txt = Text::_('ARC_UNKNOWN');
                break;
            case 12:
                $item->arc_txt = Text::_('ARC_DISCARD');
                break;
            default:
                $item->arc_txt = Text::_('UNVALID');
        }

        switch ($item->arc_policy) {
            case 0:
                $item->arc_policy_txt = Text::_('ARC_PASS');
                break;
            case 2:
                $item->arc_policy_txt = Text::_('ARC_FAIL');
                break;
            default:
                $item->arc_policy_txt = Text::_('UNVALID');
        }
        return $item;
    }

    static public function rebuildDNS(&$item)
    {
        $ret = [];
        $ret[] = "v=DMARC1";

        switch ($item->policy) {
            case 110:
                $ret[] = "p=none";
                break;
            case 113:
                $ret[] = "p=quarantine";
                break;
            case 114:
                $ret[] = "p=reject";
        }

        switch ($item->spolicy) {
            case 110:
                $ret[] = "p=none";
                break;
            case 113:
                $ret[] = "p=quarantine";
                break;
            case 114:
                $ret[] = "p=reject";
        }

        switch ($item->aspf) {
            case 114:
                $ret[] = "aspf=r";
                break;
            case 115:
                $ret[] = "aspf=s";
        }

        switch ($item->adkim) {
            case 114:
                $ret[] = "adkim=r";
                break;
            case 115:
                $ret[] = "adkim=s";
        }

        $item->dns = join(", ", $ret);
        return $item;
    }

    /**
     * Encode a string
     */
    public static function encode($string)
    {
        $encoding = mb_detect_encoding($string);
        $string = mb_convert_encoding($string, 'UTF-8', $encoding);

        $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
        $string = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $string);
        $string = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $string);
        $string = preg_replace('#&[^;]+;\.#', '', $string);
        $string = preg_replace('/[\s\%\'\"\,]+/', '-', $string);

        return strtolower($string);
    }

    /**
     * Decode a string
     */
    public static function decode($string)
    {
        $encoding = mb_detect_encoding($string);
        $string = mb_convert_encoding($string, 'UTF-8', $encoding);
        $array = preg_split('/[\s,\-]+/', $string);
        $array = array_map('ucfirst', $array);
        return join(' ', $array);
    }

    /**
     * Sanitize a filename, removing any character which is not a letter, number of space
     */
    public static function sanitize($string)
    {
        $string = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $string);
        $string = mb_ereg_replace("([\.]{2,})", '', $string);
        return $string;
    }

    public static function formatFileSize($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 

        return round($bytes, $precision) . $units[$pow];
    }


    /**
     * Transform duration obtained from MiniDLNA (e.g. 0:04:15.026) and convert it into milliseconds
     * 
     * @param string $string Formatted MiniDLNA track duration
     * 
     * @return integer Milliseconds
     * 
     */
    public static function durationToMilliseconds($string)
    {
        if (!strlen($string) || !strstr($string, ':'))
            return 0;

        $parts = explode(':', $string);
        $n = count($parts);
        $H = 0;

        $ms = 1000 * (float) $parts[$n - 1];
        $m = (int) $parts[$n - 2];
        if ($n > 2) $H = (int) $parts[$n - 3];

        $time = (3600 * $H + 60 * $m) * 1000 + $ms;
        return $time;
    }

    /**
     * Formats milliseconds into a format hours minutes seconds (e.g. 5h 36m 4s)
     * 
     * @param integer $milliseconds 
     * 
     * @return string Formatted string
     * 
     */
    public static function formatMilliseconds($milliseconds)
    {

        if (!is_numeric($milliseconds)) return '';

        $seconds = floor($milliseconds / 1000);
        $minutes = floor($seconds / 60);
        $hours = floor($minutes / 60);
        $milliseconds = $milliseconds % 1000;
        $seconds = $seconds % 60;
        $minutes = $minutes % 60;

        if (!$hours) {
            if (!$minutes) {
                $format = '%us';
                $time = sprintf($format, $seconds);
            } else {
                $format = '%um %02us';
                $time = sprintf($format, $minutes, $seconds);
            }
        } else {
            $format = '%uh %um %02us';
            $time = sprintf($format, $hours, $minutes, $seconds);
        }

        return rtrim($time, '0');
    }

    public static function createThumbnail($filename, $target = null, $size = 150, $quality = 90)
    {
        // Deals only with jpeg      
        if (exif_imagetype($filename) != IMAGETYPE_JPEG) {
            return false;
        }

        if (empty($target))
            $target = $filename;

        // Convert old file into img
        $orig   = imagecreatefromjpeg($filename);
        $w      = imageSX($orig);
        $h      = imageSY($orig);

        // Create new image
        $new    = imagecreatetruecolor($size, $size);

        // The image is square, just issue resampled image with adjusted square sides and image quality
        if ($w == $h) {
            imagecopyresampled($new, $orig, 0, 0, 0, 0, $size, $size, $w, $w);

            // The image is vertical, use x side as initial square side
        } elseif ($w < $h) {
            $x = 0;
            $y = round(($h - $w) / 2);
            imagecopyresampled($new, $orig, 0, 0, $x, $y, $size, $size, $w, $w);

            // The image is horizontal, use y side as initial square side
        } else {
            $x = round(($w - $h) / 2);
            $y = 0;
            imagecopyresampled($new, $orig, 0, 0, $x, $y, $size, $size, $h, $h);
        }

        // Save it to the filesystem
        imagewebp($new, $target, $quality);

        // Destroys the images
        imagedestroy($orig);
        imagedestroy($new);

        return $target;
    }

    public static function UUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),

            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,

            // 48 bits for "node"
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
