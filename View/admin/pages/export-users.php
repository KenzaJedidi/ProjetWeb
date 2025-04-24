<?php
session_start();
require_once '../../../Controller/userC.php';
require_once '../../../Model/user.php';
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}

$userC = new userC();
$users = $userC->getFilteredUsers('', '', 'name'); // Get all users

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set headers
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'First Name');
$sheet->setCellValue('C1', 'Last Name');
$sheet->setCellValue('D1', 'Email');
$sheet->setCellValue('E1', 'Phone');
$sheet->setCellValue('F1', 'Role');
$sheet->setCellValue('G1', 'Created At');

// Style the header row
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FF4680FF');
$sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB('FFFFFFFF');

// Add data
$row = 2;
foreach ($users as $user) {
    $sheet->setCellValue('A' . $row, $user->getIdUser());
    $sheet->setCellValue('B' . $row, $user->getNom());
    $sheet->setCellValue('C' . $row, $user->getPrenom());
    $sheet->setCellValue('D' . $row, $user->getEmail());
    $sheet->setCellValue('E' . $row, $user->getTel());
    $sheet->setCellValue('F' . $row, $user->getRole());
    $sheet->setCellValue('G' . $row, $user->getCreatedAt());
    $row++;
}

// Auto size columns
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set the content type headers
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="users_list.xlsx"');
header('Cache-Control: max-age=0');

// Create Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;