<?php
if (!function_exists('maskCpfBlade')) {
    function maskCpfBlade($cpf) {
        $cpf = preg_replace('/\D/', '', $cpf);
        if (strlen($cpf) === 11) {
            return substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9,2);
        }
        return $cpf;
    }
}
