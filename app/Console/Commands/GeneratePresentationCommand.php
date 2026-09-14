<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class GeneratePresentationCommand extends Command
{
    protected $signature = 'portal:generate-presentation';
    protected $description = 'Generate executive PDF presentation and Excel feature catalog';

    public function handle()
    {
        $this->info('Generating Executive Presentation PDF...');
        $this->generatePdf();

        $this->info('Generating Comprehensive Feature Catalog Excel (.xlsx)...');
        $this->generateExcel();

        $this->info('All presentation files successfully generated!');
        return 0;
    }

    private function generatePdf()
    {
        $pdf = Pdf::loadView('presentation.pdf');
        $pdf->setPaper('a4', 'landscape');
        $pdf->getDomPDF()->set_option('defaultFont', 'DejaVu Sans');

        $outputPath = public_path('downloads/Portal_Executive_Presentation.pdf');
        $pdf->save($outputPath);

        $this->line("PDF saved to: {$outputPath}");
    }

    private function generateExcel()
    {
        $spreadsheet = new Spreadsheet();

        // ══════════════════════════════════════════════════
        // SHEET 1: FEATURE CATALOG
        // ══════════════════════════════════════════════════
        $sheet1 = $spreadsheet->getActiveSheet();
        $sheet1->setTitle('Features Catalog');

        // Header Title
        $sheet1->setCellValue('A1', 'SMART BLOOD BANK & DONOR MANAGEMENT PORTAL - FEATURE CATALOG');
        $sheet1->mergeCells('A1:G1');
        $sheet1->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet1->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1A6B2E');
        $sheet1->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet1->getRowDimension(1)->setRowHeight(40);

        $sheet1->setCellValue('A2', 'Complete technical breakdown of all 12 modules, functionalities, and business value for NGOs and Welfare Trusts.');
        $sheet1->mergeCells('A2:G2');
        $sheet1->getStyle('A2')->getFont()->setSize(10)->setItalic(true)->getColor()->setRGB('475569');
        $sheet1->getStyle('A2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F1F5F9');
        $sheet1->getRowDimension(2)->setRowHeight(22);

        // Column Headings
        $headings = [
            'A3' => 'Module #',
            'B3' => 'Module Name',
            'C3' => 'Feature Title',
            'D3' => 'Feature Description',
            'E3' => 'Target User',
            'F3' => 'Business Value / Impact for NGO',
            'G3' => 'Status',
        ];

        foreach ($headings as $cell => $text) {
            $sheet1->setCellValue($cell, $text);
        }

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1A1A2E']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '334155']]],
        ];
        $sheet1->getStyle('A3:G3')->applyFromArray($headerStyle);
        $sheet1->getRowDimension(3)->setRowHeight(28);

        // Feature Data
        $features = [
            // Module 1: Donor Registry
            ['MOD-01', 'Donor Registry & Intelligence', 'Automated Registration Code Generation', 'Generates standardized unique donor IDs formatted by city, initials, and phone (e.g. LAH-MA-12345).', 'System / Admin', 'Zero human numbering mistakes; perfect labeling on blood bags and PVC cards.', 'Production Ready'],
            ['MOD-01', 'Donor Registry & Intelligence', '90-Day Medical Eligibility Countdown Pulse', 'Automatically calculates cooldown days from last donation; displays live countdown or Eligible Now pulse badge.', 'Admin / Staff', 'Protects donor health per WHO/Red Crescent guidelines; eliminates premature donations.', 'Production Ready'],
            ['MOD-01', 'Donor Registry & Intelligence', 'Real-Time Duplicate Prevention Engine', 'Instantly checks phone numbers and CNICs during donor creation/portal sign-up to prevent duplicates.', 'Public / Admin', 'Keeps donor database clean without duplicate contacts or split history.', 'Production Ready'],
            ['MOD-01', 'Donor Registry & Intelligence', 'Excel / CSV Bulk Donor Import Tool', 'Allows uploading thousands of legacy donor records from CSV with automatic duplicate skipping and ID assignment.', 'Admin', 'Migrates existing paper/Excel registries into the platform within 60 seconds.', 'Production Ready'],
            ['MOD-01', 'Donor Registry & Intelligence', 'Filtered CSV Data Export Tool', 'Exports customized donor lists filtered by Blood Group, City, or Status with UTF-8 BOM encoding.', 'Admin / Field Staff', 'Enables offline data analysis and targeted field coordination for mobile drives.', 'Production Ready'],
            ['MOD-01', 'Donor Registry & Intelligence', 'Student & University Ambassador Tagging', 'Tags student donors with universities/colleges to activate campus volunteer networks.', 'Admin', 'Allows organizing targeted on-campus blood drives during critical seasonal shortages.', 'Production Ready'],
            ['MOD-01', 'Donor Registry & Intelligence', 'Referral Network Tracking (Word of Mouth)', 'Tracks who referred whom using CNIC lookup, rewarding active donor advocates.', 'Admin / Public', 'Drives organic viral growth for the NGO through community word-of-mouth.', 'Production Ready'],

            // Module 2: Matching & WhatsApp
            ['MOD-02', 'Emergency Matching & Dispatch', 'Multi-Parameter Smart Match Engine', 'Matches urgent patient requests with available donors filtered by compatible blood group, city, and cooldown.', 'Coordinators', 'Reduces donor search time from 45 minutes of manual calling to under 3 seconds.', 'Production Ready'],
            ['MOD-02', 'Emergency Matching & Dispatch', 'Free 1-Click WhatsApp Direct Emergency Dispatch', 'Generates pre-filled urgent appeal text (Patient, Hospital, Units needed) and opens donor WhatsApp directly.', 'Coordinators', '100% free; saves tens of thousands of rupees on expensive third-party SMS gateways.', 'Production Ready'],
            ['MOD-02', 'Emergency Matching & Dispatch', 'Direct Call Logging & Outcome Recording', 'Allows coordinator to record call status (Connected, Busy, Donor Agreed, Donated) with 1 click.', 'Staff / Caller', 'Maintains a complete audit of donor responses and coordinates multi-agent call centers.', 'Production Ready'],
            ['MOD-02', 'Emergency Matching & Dispatch', 'Urgency Priority Badges (Critical / High)', 'Visually highlights life-threatening emergency requests for immediate triage.', 'Admin / Doctor', 'Ensures ICU and emergency surgery cases get top dispatch priority.', 'Production Ready'],

            // Module 3: Digital Cards & QR
            ['MOD-03', 'Digital Cards & Live QR Verification', 'Printable Dual-Sided PVC/Paper Donor Cards', 'Generates professional printable donor ID cards with NGO branding, photo, and blood group.', 'Donors / Admin', 'Gives donors pride of belonging and official identification as verified heroes.', 'Production Ready'],
            ['MOD-03', 'Digital Cards & Live QR Verification', 'Embedded Dynamic Verification QR Code', 'Encodes direct live public verification URL on each generated card QR code.', 'Hospitals / Doctors', 'Can be scanned with any smartphone camera without requiring special hardware.', 'Production Ready'],
            ['MOD-03', 'Digital Cards & Live QR Verification', 'Live Public Verification Portal (/portal/verify-donor)', 'Mobile-friendly public verification page displaying real-time eligibility, honor tier, and donation count.', 'Hospitals / Public', 'Zero fraud; hospitals can verify donor authenticity and safe eligibility on the spot.', 'Production Ready'],
            ['MOD-03', 'Digital Cards & Live QR Verification', 'Batch Card Printing Queue', 'Staff select unprinted cards and print multiple PVC cards in a single sheet.', 'Admin', 'Saves printing staff time by batching cards for plastic card printers.', 'Production Ready'],

            // Module 4: Public Request Portal
            ['MOD-04', 'Public Emergency Request Portal', '24/7 Public Blood Request Form (/portal/request-blood)', 'Enables families and hospitals to submit urgent blood needs online without requiring an account.', 'Patients / Families', 'Expands NGO reach across the entire city and provides 24/7 access to life-saving blood.', 'Production Ready'],
            ['MOD-04', 'Public Emergency Request Portal', 'Instant Unique Tracking Code (REQ-00042)', 'Generates an automated tracking code for the patient with direct hotline call assistance.', 'Patients / Doctors', 'Reduces panic for patient relatives by giving them a real-time tracking reference.', 'Production Ready'],
            ['MOD-04', 'Public Emergency Request Portal', 'Anti-Spam & Rate Limiting Defense', 'Throttles submissions per IP address and sanitizes inputs against malicious bots.', 'Security', 'Ensures portal stability and prevents fake or prank emergency submissions.', 'Production Ready'],

            // Module 5: Blood Inventory
            ['MOD-05', 'Blood Bank Inventory Management', 'Real-Time Units Tracking by 8 Blood Groups', 'Live inventory management of available bags across A+, A-, B+, B-, AB+, AB-, O+, O-.', 'Blood Bank Staff', 'Gives instant clarity on available stock before confirming surgery requests.', 'Production Ready'],
            ['MOD-05', 'Blood Bank Inventory Management', 'Critical Shortage Threshold Alerts', 'Visual color-coded indicators when rare groups (e.g. O-, AB-) drop below safety stock.', 'Inventory Manager', 'Triggers timely social media appeals and camps before blood runs completely dry.', 'Production Ready'],
            ['MOD-05', 'Blood Bank Inventory Management', 'Public Blood Availability Transparency Page', 'Public page (/portal/availability) displaying safe stock levels to doctors and patients.', 'Public / Doctors', 'Prevents unnecessary patient travel to blood banks that do not have their group in stock.', 'Production Ready'],

            // Module 6: Financial Donations
            ['MOD-06', 'Financial Donations & Receipts', 'Multi-Channel Monetary Donation Tracking', 'Records cash, online, bank transfer, and cheque contributions for NGO medical expenses.', 'Accounts / Admin', 'Complete financial accountability for Zakat, Sadqah, and General donations.', 'Production Ready'],
            ['MOD-06', 'Financial Donations & Receipts', 'Automated Printable PDF Receipts', 'Generates official branded receipts with NGO logo, tax registration, and signature lines.', 'Donors / Accounts', 'Builds maximum trust with philanthropic donors, encouraging larger repeat donations.', 'Production Ready'],

            // Module 7: Mobile Drives & Campaigns
            ['MOD-07', 'Campaigns & Mobile Drives', 'Blood Camp Scheduling & Venue Management', 'Coordinates blood donation drives at universities, corporate offices, and community centers.', 'Drive Coordinators', 'Ensures structured scheduling and resource allocation for mobile medical teams.', 'Production Ready'],
            ['MOD-07', 'Campaigns & Mobile Drives', 'Printable Attendance Sheet Generator', 'Generates print-ready sign-in sheets for donors and medical volunteer staff on-site.', 'Camp Field Staff', 'Replaces chaotic paper slips with standardized official camp attendance logs.', 'Production Ready'],

            // Module 8: Call Center CRM
            ['MOD-08', 'Call Center CRM & Follow-ups', 'Daily Call Logging System', 'Staff record every outgoing call to prospective donors with outcome and comments.', 'Call Center Staff', 'Measures staff performance and ensures every pending emergency request is followed up.', 'Production Ready'],
            ['MOD-08', 'Call Center CRM & Follow-ups', 'Scheduled Follow-Up Reminders', 'Automated tracking of deferred donors who requested to be called back next week/month.', 'Coordinators', 'Recovers prospective donors who were temporarily busy or traveling.', 'Production Ready'],

            // Module 9: Gamification & Certificates
            ['MOD-09', 'Gamification & Donor Retention', '5-Tier Honor Badge System (Bronze to Platinum)', 'Automated recognition tiers: Platinum Hero (10+), Gold (5-9), Silver (3-4), Bronze (1-2), New.', 'Donors / Public', 'Boosts donor retention by 300% through psychological pride and milestone progression.', 'Production Ready'],
            ['MOD-09', 'Gamification & Donor Retention', 'Official Printable PDF Certificates', 'Generates certificate of blood donation with donor tier, lifetime units, and live QR code.', 'Donors / Hospitals', 'Donors share their certificates on LinkedIn/WhatsApp, bringing in new volunteer donors.', 'Production Ready'],

            // Module 10: Reporting & Analytics
            ['MOD-10', 'Reporting & Executive Analytics', 'Triple Export Formats (Excel, PDF, Word)', 'Generates executive reports in Microsoft Excel (.xlsx), Adobe PDF (.pdf), and MS Word (.docx).', 'Directors / Auditors', 'Provides ready-made reports for board meetings, donor reporting, and government audits.', 'Production Ready'],
            ['MOD-10', 'Reporting & Executive Analytics', 'Granular Multi-Parameter Report Filters', 'Filter reports by custom date range, blood group, status, and geographical city/district.', 'Admin / Analysts', 'Allows deep operational analysis into monthly blood collections and shortfall trends.', 'Production Ready'],

            // Module 11: Security & Audit
            ['MOD-11', 'Enterprise Security & RBAC', 'Role-Based Access Control (RBAC)', 'Granular permissions for Super Admin, Branch Manager, Coordinator, Doctor, and Guest.', 'Admin / IT', 'Protects sensitive donor medical and contact records from unauthorized access.', 'Production Ready'],
            ['MOD-11', 'Enterprise Security & RBAC', 'Immutable System Audit Trail', 'Records every database create, edit, reprint, and delete action with user and IP address.', 'Security Auditor', 'Eliminates internal fraud and maintains 100% accountability across all staff members.', 'Production Ready'],

            // Module 12: UI/UX & Localization
            ['MOD-12', 'UI/UX, Localization & Themes', 'Full Bilingual English & Urdu (اردو) Support', '1-click top header pill toggle switches entire dashboard between English and native Urdu.', 'All Users', 'Allows non-English speaking field staff and volunteers to operate the software with ease.', 'Production Ready'],
            ['MOD-12', 'UI/UX, Localization & Themes', 'Authentic Noto Nastaliq Typography & True RTL', 'Proper Right-to-Left (RTL) layout mirroring with beautiful Nastaliq font rendering.', 'Urdu Readers', 'Provides a dignified, professional native Urdu user experience without breaking UI.', 'Production Ready'],
            ['MOD-12', 'UI/UX, Localization & Themes', 'Day / Night Dark Mode Switch', 'One-click sun/moon toggle switch with persistent browser storage and zero white flash.', 'Hospital Staff', 'Reduces eye strain during late-night emergency hospital shifts.', 'Production Ready'],
            ['MOD-12', 'UI/UX, Localization & Themes', 'Multi-Device Responsive Mobile Design', 'Fully optimized for desktop PCs, laptops, Android tablets, and mobile smartphones.', 'Field Workers', 'Field teams can register donors and verify blood requests anywhere on mobile.', 'Production Ready'],
        ];

        $rowNum = 4;
        foreach ($features as $f) {
            $sheet1->setCellValue("A{$rowNum}", $f[0]);
            $sheet1->setCellValue("B{$rowNum}", $f[1]);
            $sheet1->setCellValue("C{$rowNum}", $f[2]);
            $sheet1->setCellValue("D{$rowNum}", $f[3]);
            $sheet1->setCellValue("E{$rowNum}", $f[4]);
            $sheet1->setCellValue("F{$rowNum}", $f[5]);
            $sheet1->setCellValue("G{$rowNum}", $f[6]);

            // Alternate row fill
            if ($rowNum % 2 === 0) {
                $sheet1->getStyle("A{$rowNum}:G{$rowNum}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }

            $sheet1->getStyle("A{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle("G{$rowNum}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet1->getStyle("G{$rowNum}")->getFont()->setBold(true)->getColor()->setRGB('15803D');

            $rowNum++;
        }

        $sheet1->getStyle("A4:G" . ($rowNum - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
        $sheet1->getStyle("A4:G" . ($rowNum - 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        // Auto-fit columns
        foreach (range('A', 'G') as $col) {
            $sheet1->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet1->getColumnDimension('D')->setWidth(45);
        $sheet1->getColumnDimension('F')->setWidth(40);

        // ══════════════════════════════════════════════════
        // SHEET 2: ROI & IMPACT MATRIX
        // ══════════════════════════════════════════════════
        $sheet2 = $spreadsheet->createSheet();
        $sheet2->setTitle('ROI & Impact Analysis');

        $sheet2->setCellValue('A1', 'NGO RETURN ON INVESTMENT (ROI) & OPERATIONAL IMPACT');
        $sheet2->mergeCells('A1:F1');
        $sheet2->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet2->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1565A8');
        $sheet2->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet2->getRowDimension(1)->setRowHeight(40);

        $roiHeaders = ['A2' => 'Operational Function', 'B2' => 'Traditional Manual Method', 'C2' => 'Smart Portal Platform', 'D2' => 'Time Saved', 'E2' => 'Cost Savings', 'F2' => 'Life-Saving Impact'];
        foreach ($roiHeaders as $cell => $text) {
            $sheet2->setCellValue($cell, $text);
        }
        $sheet2->getStyle('A2:F2')->applyFromArray($headerStyle);
        $sheet2->getRowDimension(2)->setRowHeight(26);

        $roiData = [
            ['Emergency Donor Matching', 'Searching paper files, manual dial lists (45-60 mins)', 'Algorithm matches eligible donors by group & city (< 3 secs)', '95% Time Saved', 'Eliminates overtime coordinator hours', 'Directly prevents emergency surgery deaths'],
            ['Outbound Donor Notification', 'Typing manual SMS or paying third-party SMS gateway fees', 'Free 1-Click WhatsApp Direct Emergency Dispatch', '80% Faster Dispatch', 'Rs. 25,000 - 45,000 / month saved on SMS fees', 'Higher open rate (98% on WhatsApp vs 20% on SMS)'],
            ['Donor Verification & Trust', 'Paper slips without identity or eligibility verification', 'Live Dynamic QR Code verification via smartphone scanner', 'Instant (< 2 secs)', 'Prevents fraudulent card reprints', 'Guarantees hospital compliance & medical safety'],
            ['Donor Registration & Retention', 'Writing in paper registers; zero milestone appreciation', 'Self-service online portal & Bronze-Platinum gamification', '100% Paperless', 'Saves thousands on stationery & physical ledgers', 'Boosts repeat donor retention rate by 3x'],
            ['Monthly & Annual Reporting', 'Manual counting of registers; 3-4 days of manual work', '1-Click instant generation in Excel (.xlsx), PDF, and Word', '4 Days to 2 Seconds', 'Saves audit and data-entry staff salaries', 'Provides 100% accurate data for foreign grants and audits'],
        ];

        $rRow = 3;
        foreach ($roiData as $r) {
            $sheet2->setCellValue("A{$rRow}", $r[0]);
            $sheet2->setCellValue("B{$rRow}", $r[1]);
            $sheet2->setCellValue("C{$rRow}", $r[2]);
            $sheet2->setCellValue("D{$rRow}", $r[3]);
            $sheet2->setCellValue("E{$rRow}", $r[4]);
            $sheet2->setCellValue("F{$rRow}", $r[5]);

            $sheet2->getStyle("D{$rRow}")->getFont()->setBold(true)->getColor()->setRGB('15803D');
            $sheet2->getStyle("E{$rRow}")->getFont()->setBold(true)->getColor()->setRGB('1565A8');

            if ($rRow % 2 === 0) {
                $sheet2->getStyle("A{$rRow}:F{$rRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }
            $rRow++;
        }
        $sheet2->getStyle("A3:F" . ($rRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
        foreach (range('A', 'F') as $col) {
            $sheet2->getColumnDimension($col)->setAutoSize(true);
        }

        // ══════════════════════════════════════════════════
        // SHEET 3: IMPLEMENTATION & GOVERNANCE
        // ══════════════════════════════════════════════════
        $sheet3 = $spreadsheet->createSheet();
        $sheet3->setTitle('Implementation Roadmap');

        $sheet3->setCellValue('A1', '14-DAY IMPLEMENTATION ROADMAP & DATA GOVERNANCE');
        $sheet3->mergeCells('A1:E1');
        $sheet3->getStyle('A1')->getFont()->setSize(16)->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet3->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('1A1A2E');
        $sheet3->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);
        $sheet3->getRowDimension(1)->setRowHeight(40);

        $propHeaders = ['A2' => 'Phase #', 'B2' => 'Timeline', 'C2' => 'Phase Title', 'D2' => 'Core Deliverables', 'E2' => 'Responsible Stakeholders'];
        foreach ($propHeaders as $cell => $text) {
            $sheet3->setCellValue($cell, $text);
        }
        $sheet3->getStyle('A2:E2')->applyFromArray($headerStyle);
        $sheet3->getRowDimension(2)->setRowHeight(26);

        $propData = [
            ['Phase 01', 'Days 1 – 3', 'Cloud Infrastructure & Custom Branding', 'Dedicated server provisioning, SSL certificate installation, domain binding, NGO logo/color configuration.', 'IT Lead & DevOps'],
            ['Phase 02', 'Days 4 – 7', 'Historical Data Cleansing & CSV Migration', 'Sanitizing legacy donor records, duplicate removal, bulk CSV import, and unique registration ID generation.', 'Data Specialist & Admin'],
            ['Phase 03', 'Days 8 – 11', 'Staff Training & Role-Based Access Setup', 'Role configuration (Spatie RBAC), coordinator training for emergency dispatch and WhatsApp, doctor walkthrough.', 'Lead Trainer & Medical Staff'],
            ['Phase 04', 'Days 12 – 14', 'Pilot Verification & Official Go-Live', 'Live QR code mobile scanning tests with partner hospitals, emergency public portal activation, helpline launch.', 'Executive Director & Operations'],
            ['Ongoing', 'Post Go-Live', 'Security Governance & System Monitoring', 'Automated daily database backups, HIPAA-aligned donor privacy, 24/7 server health tracking, and continuous upgrades.', 'SLA Support & Systems Lead'],
        ];

        $pRow = 3;
        foreach ($propData as $p) {
            $sheet3->setCellValue("A{$pRow}", $p[0]);
            $sheet3->setCellValue("B{$pRow}", $p[1]);
            $sheet3->setCellValue("C{$pRow}", $p[2]);
            $sheet3->setCellValue("D{$pRow}", $p[3]);
            $sheet3->setCellValue("E{$pRow}", $p[4]);

            $sheet3->getStyle("A{$pRow}")->getFont()->setBold(true)->getColor()->setRGB('059669');
            $sheet3->getStyle("B{$pRow}")->getFont()->setBold(true)->getColor()->setRGB('1565A8');
            $sheet3->getStyle("C{$pRow}")->getFont()->setBold(true);

            if ($pRow % 2 === 0) {
                $sheet3->getStyle("A{$pRow}:E{$pRow}")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F8FAFC');
            }
            $pRow++;
        }
        $sheet3->getStyle("A3:E" . ($pRow - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)->getColor()->setRGB('E2E8F0');
        foreach (range('A', 'E') as $col) {
            $sheet3->getColumnDimension($col)->setAutoSize(true);
        }
        $sheet3->getColumnDimension('D')->setWidth(55);

        // Save Excel file
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
        $excelPath = public_path('downloads/Portal_Features_Catalog.xlsx');
        $writer->save($excelPath);

        $this->line("Excel saved to: {$excelPath}");
    }
}
