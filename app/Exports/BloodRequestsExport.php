<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BloodRequestsExport implements FromCollection, WithHeadings, WithEvents
{
    protected Collection $records;
    protected array $ngo;

    public function __construct(Collection $records, array $ngo = [])
    {
        $this->records = $records;
        $this->ngo = $ngo;
    }

    public function collection()
    {
        return $this->records->map(fn($d) => [
            $d->id,
            $d->patient_name,
            $d->hospital ?? '',
            $d->blood_group ?? '',
            $d->units_required ?? '',
            $d->contact_phone ?? '',
            $d->status ?? '',
            $d->created_at->format('d-m-Y'),
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Patient', 'Hospital', 'Blood Group', 'Units Required', 'Contact Phone', 'Status', 'Created'];
    }

    public function registerEvents(): array
    {
        $ngo = $this->ngo;
        return [
            BeforeSheet::class => function (BeforeSheet $event) use ($ngo) {
                $sheet = $event->getSheet();
                if (!empty($ngo['name'])) {
                    $sheet->mergeCells('A1:H1');
                    $sheet->setCellValue('A1', $ngo['name']);
                    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                if (!empty($ngo['address'])) {
                    $sheet->mergeCells('A2:H2');
                    $sheet->setCellValue('A2', $ngo['address']);
                    $sheet->getStyle('A2')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('888888'));
                    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $sheet->mergeCells('A3:H3');
                $sheet->setCellValue('A3', 'Generated: ' . now()->format('d M Y h:i A'));
                $sheet->getStyle('A3')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('aaaaaa'));
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
