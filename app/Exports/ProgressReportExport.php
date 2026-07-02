<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ProgressReportExport implements FromCollection, WithEvents
{
    protected array $data;
    protected array $ngo;

    public function __construct(array $data, array $ngo = [])
    {
        $this->data = $data;
        $this->ngo = $ngo;
    }

    public function collection()
    {
        $d = $this->data;
        $rows = collect();

        $rows->push(['DONOR STATISTICS', '', '']);
        $rows->push(['Total Donors', $d['totalDonors'], '']);
        $rows->push(['Active', $d['activeDonors'], '']);
        $rows->push(['Inactive', $d['inactiveDonors'], '']);
        $rows->push(['Ineligible', $d['ineligibleDonors'], '']);
        $rows->push(['', '', '']);

        $rows->push(['BLOOD DONATIONS', '', '']);
        $rows->push(['Total Donations', $d['totalDonations'], '']);
        $rows->push(['Units Donated', $d['donatedUnits'], '']);
        foreach ($d['donationsByGroup'] as $bg => $units) {
            $rows->push(["  {$bg} Units", $units, '']);
        }
        $rows->push(['', '', '']);

        $rows->push(['MONEY COLLECTED', '', '']);
        $rows->push(['Total', 'PKR ' . number_format($d['totalMoney'], 2), '']);
        $rows->push(['This Month', 'PKR ' . number_format($d['moneyThisMonth'], 2), '']);
        foreach ($d['moneyByMethod'] as $method => $total) {
            $rows->push(["  {$method}", 'PKR ' . number_format($total, 2), '']);
        }
        $rows->push(['', '', '']);

        $rows->push(['CAMPAIGNS', '', '']);
        $rows->push(['Total Campaigns', $d['totalCampaigns'], '']);
        $rows->push(['Upcoming', $d['upcomingCampaigns'], '']);
        $rows->push(['', '', '']);

        $rows->push(['BLOOD REQUESTS', '', '']);
        $rows->push(['Pending', $d['pendingRequests'], '']);
        $rows->push(['Resolved', $d['resolvedRequests'], '']);
        $rows->push(['Closed', $d['closedRequests'], '']);

        return $rows;
    }

    public function registerEvents(): array
    {
        $ngo = $this->ngo;
        return [
            BeforeSheet::class => function (BeforeSheet $event) use ($ngo) {
                $sheet = $event->getSheet();
                if (!empty($ngo['name'])) {
                    $sheet->mergeCells('A1:C1');
                    $sheet->setCellValue('A1', $ngo['name']);
                    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                if (!empty($ngo['address'])) {
                    $sheet->mergeCells('A2:C2');
                    $sheet->setCellValue('A2', $ngo['address']);
                    $sheet->getStyle('A2')->getFont()->setSize(10)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('888888'));
                    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
                $sheet->mergeCells('A3:C3');
                $sheet->setCellValue('A3', 'Progress Report - Generated: ' . now()->format('d M Y h:i A'));
                $sheet->getStyle('A3')->getFont()->setSize(9)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('aaaaaa'));
                $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
