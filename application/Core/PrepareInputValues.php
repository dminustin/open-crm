<?php
/**
 * Class for manipulations with input values
 * Adding quotes, special chars convert, checking the input values
 */

namespace OpenCRM\Core;


use OpenCRM\Exception\InvalidArgumentException;

class PrepareInputValues
{
    const PIV_AL_NUM_SYMBOLS = 'AL_NUM_SYMBOLS';
    const PIV_AL_NUM = 'AL_NUM';
    const PIV_NUM = 'NUM';
    const PIV_AL = 'AL';
    const PIV_EMAIL = 'EMAIL';
    const PIV_NONE = null;

    /**
     * @param $val
     * @param null $piv_mode
     * @return string
     * @throws InvalidArgumentException
     */
    static function escapeTheInput($val, $piv_mode = self::PIV_AL_NUM_SYMBOLS) {
        $val = trim($val);
        switch ($piv_mode) {
            case self::PIV_NONE: {
                return $val;
                break;
            }
            case self::PIV_AL: {
                if (preg_match('#[^\w]#sui', $val)) {
                    throw new InvalidArgumentException("${val} is not a alphabetical value");
                }
                return $val;
                break;
            }
            case self::PIV_EMAIL: {
                if (preg_match('#[^a-z0-9@\\._]#sui', $val)) {
                    throw new InvalidArgumentException("${val} is not an email");
                }
                return $val;
                break;
            }
            case self::PIV_AL_NUM: {
                if (preg_match('#[^:alnum:]#sui', $val)) {
                    throw new InvalidArgumentException("${val} is not a alphabetical value");
                }
                return $val;
                break;
            }
            case self::PIV_NUM: {
                if (!is_numeric($val)) {
                    throw new InvalidArgumentException("${val} is not a numeric value");
                }
                return $val;
                break;
            }
            case self::PIV_AL_NUM_SYMBOLS: {
                return htmlentities($val, ENT_QUOTES, 'utf-8', false);
                break;
            }
            default: {
                throw new InvalidArgumentException("Mode ${piv_mode} is not supported");
            }
        }


    }


}