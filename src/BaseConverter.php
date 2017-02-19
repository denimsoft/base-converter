<?php

namespace Denimsoft\Stdlib\BaseConverter;

class BaseConverter
{
    const CHARACTER_SET_DECIMAL = '0123456789';
    const CHARACTER_SET_HEXADECIMAL = '0123456789ABCDEF';
    const CHARACTER_SET_BASE36 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const CHARACTER_SET_BASE62 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    const CHARACTER_SET_BASE64 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/';
    const CHARACTER_SET_BASE95 = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz+/!"#$%&\'()*,-.:;<=>?@[\]^_`{|}~ ';

    private $outputBase = 10;
    private $inputBase = 10;
    private $characters = null;

    public function __construct(
        int $outputBase = 10,
        int $inputBase = 10,
        string $characters = null)
    {
        $this->outputBase = $outputBase;
        $this->inputBase = $inputBase;

        if ($characters === null) {
            $characterSets = [
                self::CHARACTER_SET_DECIMAL,
                self::CHARACTER_SET_HEXADECIMAL,
                self::CHARACTER_SET_BASE36,
                self::CHARACTER_SET_BASE62,
                self::CHARACTER_SET_BASE64,
                self::CHARACTER_SET_BASE95,
            ];
            $base = max($outputBase, $inputBase);
            foreach ($characterSets as $characterSet) {
                $len = strlen($characterSet);
                if ($base <= $len) {
                    $characters = substr($characterSet, 0, $base);
                    break;
                }
            }
        }

        $this->characters = $characters;
    }

    public function decode(string $value): string
    {
        return $this->convert($value, $this->outputBase, $this->inputBase);
    }

    public function encode(string $value): string
    {
        return $this->convert($value, $this->inputBase, $this->outputBase);
    }

    //region Getters and Setters
    /**
     * @return int
     */
    public function getOutputBase()
    {
        return $this->outputBase;
    }

    /**
     * @param int $outputBase
     * @return $this
     */
    public function setOutputBase($outputBase)
    {
        $this->outputBase = $outputBase;
        return $this;
    }

    /**
     * @return int
     */
    public function getInputBase()
    {
        return $this->inputBase;
    }

    /**
     * @param int $inputBase
     * @return $this
     */
    public function setInputBase($inputBase)
    {
        $this->inputBase = $inputBase;
        return $this;
    }

    /**
     * @return string
     */
    public function getCharacters()
    {
        return $this->characters;
    }

    /**
     * @param string $characters
     * @return $this
     */
    public function setCharacters($characters)
    {
        $this->characters = $characters;
        return $this;
    }

    //endregion

    private function convert(string $value, int $inputBase, int $outputBase): string
    {
        $characters = substr($this->characters, 0, max($inputBase, $outputBase));

        if (min($inputBase, $outputBase) < 2) {
            trigger_error('Bad Format min: 2', E_USER_ERROR);
        }

        if (max($inputBase, $outputBase) > strlen($characters)) {
            trigger_error('Bad Format max: ' . strlen($characters), E_USER_ERROR);
        }

        $result = $value >= 0 ? '' : '-';
        $number = '0';
        $value = strrev(ltrim($value, '-0'));

        for ($i = 0, $len = strlen($value); $i < $len; $i++) {
            $index = strpos($characters, $value[$i]);
            if ($index === false) {
                trigger_error('Bad Char in input 1', E_USER_ERROR);
            }
            $number = bcadd($number, bcmul(bcpow($inputBase, $i), $index));
        }

        if ($outputBase !== 10) {
            $bits = 0;
            while (1 !== bccomp(bcpow($outputBase, $bits), $number)) {
                $bits++;
            }
            for ($bits--; $bits >= 0; $bits--) {
                $modulus = bcpow($outputBase, $bits);
                $index = bcdiv($number, $modulus, 0);
                $number = bcmod($number, $modulus);
                $result .= $characters[$index];
            }
        } else {
            $result .= $number;
        }

        return $result;
    }
}
