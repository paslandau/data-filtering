<?php
namespace paslandau\DataFiltering\Util;

use paslandau\ArrayUtility\ArrayUtil;

class StringUtil
{
    /**
     * Replaces German Umlauts from the given string and replaces everything that doesnt match
     * [^a-zA-Z0-9] by "-". Multipe "-" will be trimmed to one "-".
     * @see http://stackoverflow.com/questions/1770250/how-to-remove-diacritics-from-text
     * @param string $data
     * @return string
     */
    public static function MakeStringUrlSave($data)
    {
        $umlauts = array("ä", "ö", "ü", "Ä", "Ö", "Ü", "ß");
        $replace = array("ae", "oe", "ue", "Ae", "Oe", "Ue", "ss");
        $data = str_replace($umlauts, $replace, $data);
        $data = iconv('UTF-8', 'ASCII//TRANSLIT', $data); //CAUTION: $data must be UTF-8 encoded!
        // replace everything NOT in the sets you specified with a hyphen
        $data = preg_replace("#[^a-zA-Z0-9]+#u", "-", $data);
        $data = preg_replace("#-+#u", "-", $data);
        return $data;
    }

    /**
     *
     * @see http://www.php.net/manual/en/function.gzdecode.php#82930
     * @param $data
     * @param string $filename
     * @param null $maxlength
     * @throws \Exception
     * @internal param string $zipped
     * @return string
     */
    public static function myGzipDecode($data, &$filename = '', $maxlength = null)
    {
// 		$offset = 0;
// 		if (substr($zipped,0,2) == "\x1f\x8b")
// 			$offset = 2;
// 		if (substr($zipped,$offset,1) == "\x08")  {
// 			# file_put_contents("tmp.gz", substr($zipped, $offset - 2));
// 			return gzinflate(substr($zipped, $offset + 8));
// 		}
        $len = strlen($data);
        if ($len < 18 || strcmp(substr($data, 0, 2), "\x1f\x8b")) {
            throw new \Exception("Not in GZIP format."); // Not GZIP format (See RFC 1952)
        }
        $method = ord(substr($data, 2, 1)); // Compression method
        $flags = ord(substr($data, 3, 1)); // Flags
        if ($flags & 31 != $flags) {
            throw new \Exception("Not in GZIP format.");
        }
        // NOTE: $mtime may be negative (PHP integer limitations)
        $mtime = unpack("V", substr($data, 4, 4));
        $mtime = $mtime[1];
        $xfl = substr($data, 8, 1);
        $os = substr($data, 8, 1);
        $headerlen = 10;
        $extralen = 0;
        $extra = "";
        if ($flags & 4) {
            // 2-byte length prefixed EXTRA data in header
            if ($len - $headerlen - 2 < 8) {
                throw new \Exception("Invalid gzip format");
            }
            $extralen = unpack("v", substr($data, 8, 2));
            $extralen = $extralen[1];
            if ($len - $headerlen - 2 - $extralen < 8) {
                throw new \Exception("Invalid gzip format");
            }
            $extra = substr($data, 10, $extralen);
            $headerlen += 2 + $extralen;
        }
        $filenamelen = 0;
        $filename = "";
        if ($flags & 8) {
            // C-style string
            if ($len - $headerlen - 1 < 8) {
                throw new \Exception("Invalid gzip format");
            }
            $filenamelen = strpos(substr($data, $headerlen), chr(0));
            if ($filenamelen === false || $len - $headerlen - $filenamelen - 1 < 8) {
                throw new \Exception("Invalid gzip format");
            }
            $filename = substr($data, $headerlen, $filenamelen);
            $headerlen += $filenamelen + 1;
        }
        $commentlen = 0;
        $comment = "";
        if ($flags & 16) {
            // C-style string COMMENT data in header
            if ($len - $headerlen - 1 < 8) {
                throw new \Exception("Invalid gzip format");
            }
            $commentlen = strpos(substr($data, $headerlen), chr(0));
            if ($commentlen === false || $len - $headerlen - $commentlen - 1 < 8) {
                throw new \Exception("Invalid gzip header format");
            }
            $comment = substr($data, $headerlen, $commentlen);
            $headerlen += $commentlen + 1;
        }
        $headercrc = "";
        if ($flags & 2) {
            // 2-bytes (lowest order) of CRC32 on header present
            if ($len - $headerlen - 2 < 8) {
                throw new \Exception("Invalid gzip format");
            }
            $calccrc = crc32(substr($data, 0, $headerlen)) & 0xffff;
            $headercrc = unpack("v", substr($data, $headerlen, 2));
            $headercrc = $headercrc[1];
            if ($headercrc != $calccrc) {
                $error = "";
                throw new \Exception("Gzip Header checksum failed."); // Bad header CRC
            }
            $headerlen += 2;
        }
        // GZIP FOOTER
        $datacrc = unpack("V", substr($data, -8, 4));
        $datacrc = sprintf('%u', $datacrc[1] & 0xFFFFFFFF);
        $isize = unpack("V", substr($data, -4));
        $isize = $isize[1];
        // decompression:
        $bodylen = $len - $headerlen - 8;
        if ($bodylen < 1) {
            // IMPLEMENTATION BUG!
            throw new \Exception("Invalid gzip format");
        }
        $body = substr($data, $headerlen, $bodylen);
        $data = "";
        if ($bodylen > 0) {
            switch ($method) {
                case 8:
                    // Currently the only supported compression method:
                    $data = gzinflate($body, $maxlength);
                    break;
                default:
                    throw new \Exception("Unknown compression method.");
            }
        } // zero-byte body content is allowed
        // Verifiy CRC32
        $crc = sprintf("%u", crc32($data));
        $crcOK = $crc == $datacrc;
        $lenOK = $isize == strlen($data);
        if (!$lenOK || !$crcOK) {
            $error = ($lenOK ? '' : 'Length check FAILED. ') . ($crcOK ? '' : 'Checksum FAILED.');
            throw new \Exception("CRC check failed.");
        }
        return $data;
    }

    /**
     *
     * @param mixed $s
     * @return string
     */
    public static function strval($s)
    {
        try {
            if (is_null($s)) {
                return "[null]";
            }
            if (is_bool($s)) {
                return $s ? "true" : "false";
            }
            if (is_array($s)) {
                return ArrayUtil::toString($s);
//                array_walk($s,function(&$val,$key){ $val = $key." - ".$val; });
//                return ArrayUtil::toString($s, " - ", "\n", true);
            }
            if (!self::CanBeString($s)) {
                return "[Object of " . get_class($s) . "]";
            }
            return strval($s);
        } catch (\Exception $e) {
            echo $e;
        }
        return "";
    }

    /**
     * Checks wether the given $item can be printed as String
     * @param mixed $item
     * @return boolean
     */
    public static function CanBeString($item)
    {
        return
            (
            !is_array($item)
            )
            &&
            (
                (!is_object($item)
                    &&
                    settype($item, 'string') !== false
                )
                ||
                (is_object($item)
                    &&
                    method_exists($item, '__toString')
                )
            );
    }

    /**
     *
     * @param $obj
     * @internal param mixed $s
     * @return string
     */
    public static function GetElementString($obj)
    {
        $s = array();
        $props = (new \ReflectionClass (get_class($obj)))->getProperties();
        foreach ($props as $prop) {
            $val = $obj->{$prop->name};
            if ($val instanceof \DateTime) {
                $s[] = "{$prop->name}=" . $val->format("Y-m-d H:i:s");
            } elseif (is_object($val)) {
                $s[] = "\n\t{$prop->name}=" . $val . "\n";
            } else {
                $s[] = "{$prop->name}=$val";
            }
        }
        $s = [];
        return "{[" . __CLASS__ . "]: " . implode(";", $s) . "}";
    }

    public static function GetObjectString($obj, array $hideProperties = null, $indent = null)
    {
        $s = [];
        if ($hideProperties === null) {
            $hideProperties = array();
        }
        if ($indent === null) {
            $indent = "";
        }
        $propIndent = $indent . "  ";
        $c = new \ReflectionClass(get_class($obj));
        $properties = $c->getProperties();
        foreach ($properties as $property) {
            $property->setAccessible(true);
            $prop = $property->getName();
            $val = $property->getValue($obj);
            if (!in_array($prop, $hideProperties)) {
                $val = self::strval($val);
                $s[] = $propIndent . "$prop: $val";
            }
        }
        $className = (new \ReflectionClass($obj))->getShortName();
        $ss = "{$indent} [$className] " . implode("\n", $s);
        return $ss;
    }
}

?>