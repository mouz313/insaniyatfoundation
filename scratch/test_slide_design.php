<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'DejaVu Sans');
$dompdf = new Dompdf($options);
$dompdf->setPaper('a4', 'landscape');

$html = '<!DOCTYPE html><html><head><style>
@page { size: 297mm 210mm; margin: 8mm 10mm; }
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: "DejaVu Sans", sans-serif; background: #ffffff; color: #1e293b; font-size: 10px; }
.slide { width: 100%; height: 188mm; max-height: 188mm; overflow: hidden; page-break-after: always; position: relative; }
.slide:last-child { page-break-after: avoid; }
.cover-bg { background: #0f172a; color: #fff; width: 100%; height: 100%; border-radius: 6px; padding: 25px 30px; }
.inner-slide { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; width: 100%; height: 100%; padding: 15px 20px; }
</style></head><body>';

$html .= '<div class="slide"><div class="cover-bg"><h1>Cover Page</h1><p>Full Slide Cover</p></div></div>';
for ($i = 2; $i <= 10; $i++) {
    $html .= '<div class="slide"><div class="inner-slide"><h2>Slide ' . $i . '</h2><p>Content fills slide cleanly</p></div></div>';
}
$html .= '</body></html>';

$dompdf->loadHtml($html);
$dompdf->render();
$output = $dompdf->output();

preg_match_all('/\/Type\s*\/Page\b/', $output, $matches);
echo "Resulting page count: " . count($matches[0]) . "\n";
