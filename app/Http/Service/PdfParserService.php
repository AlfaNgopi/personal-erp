<?php

namespace App\Http\Service;

use Smalot\PdfParser\Parser;

class PdfParserService
{

    public function extract(string $filepath)
    {

        $parser = new Parser();
        $pdf = $parser->parseFile($filepath);

        $text = $pdf->getText();

        return $text;
    }
}
