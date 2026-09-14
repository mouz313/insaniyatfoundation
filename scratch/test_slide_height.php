<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');

for ($h = 190; $h <= 208; $h += 2) {
    $dompdf = new Dompdf($options);
    $dompdf->setPaper('a4', 'landscape');

    $html = '<!DOCTYPE html><html><head><style>
    @page { size: 297mm 210mm; margin: 0; }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    html, body { margin: 0; padding: 0; background: #ffffff; }
    .slide { width: 297mm; height: ' . $h . 'mm; max-height: ' . $h . 'mm; overflow: hidden; page-break-after: always; }
    .slide:last-child { page-break-after: avoid; }
    .inner { width: 100%; height: 100%; padding: 10mm 15mm; background: #0f172a; color: #fff; }
    </style></head><body>';

    for ($i = 1; $i <= 10; $i++) {
        $html .= '<div class="slide"><div class="inner"><h1>Slide ' . $i . '</h1><p>Height: ' . $h . 'mm</p></div></div>';
    }
    $html .= '</body></html>';

    $dompdf->loadHtml($html);
    $dompdf->render();
    $output = $dompdf->output();

    preg_match_all('/\/Type\s*\/Page\b/', $output, $matches);
    echo "Slide Height {$h}mm -> Pages: " . count($matches[0]) . "\n";
}
