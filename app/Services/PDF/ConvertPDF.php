<?php
namespace App\Services\PDF;

class ConvertPDF
{
    public static function toCompatible($output, $input)
    {
        $command = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dPDFSETTINGS=/screen -dNOPAUSE -dQUIET -dBATCH -sOutputFile=$output $input";
        return shell_exec($command);
    }
}