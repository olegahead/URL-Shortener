<?php

namespace Model\Links;

class HashGenerator
{
    private $table = [
        'a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E',
        'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',
        'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O',
        'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',
        'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y',
        'z', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
    ];

    public function generate(?string $str) : string
    {
        $last_table_char = end($this->table);
        $first_table_char = $this->table[0];

        if (!strlen($str)) {
            $str = $first_table_char;
            return $str;
        }

        for ($i = 0; $i < strlen($str); ++$i) {

            if ($str[$i] == $last_table_char) {

                $str[$i] = $first_table_char;

                if ($i == strlen($str) - 1) {
                    $str .= $first_table_char;
                }

            } else {

                $str[$i] = $this->table[array_search($str[$i], $this->table) + 1];
                break;

            }

        }

        return $str;
    }

}