<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\DataParams;
use App\Models\M_Product;
use App\Models\ProductModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\I18n\Time;

use TCPDF;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\Alignment as Alignment;

class Admin extends BaseController
{
    protected $helpers = ['date'];
    protected $modelUser;
    protected $modelProduct;
    private $db;

    public function __construct()
    {
        // Load helper secara manual
        helper($this->helpers);
        $this->db = db_connect();
        $this->db->initialize();
        $this->modelUser = new UserModel();
        $this->modelProduct = new ProductModel();
    }

    public function index()
    {
        $parser = \Config\Services::parser();
        $pageData = [
            'userStatistics' => [
                [
                    'total_users' => $this->modelUser->getTotalUsers(),
                    'active_users' => $this->modelUser->findActiveUsers(),
                    'new_users_this_month' => $this->modelUser->getNewUsersThisMonth(),
                    'growth_percentage' => 0
                ]
            ],
            'page_title' => 'Online Food System',
            'product_statistics_cell' => view_cell('ProductStatisticsCell', [], HOUR, 'cache_product_statistics'),
        ];

        $productByCategory = $this->getProductByCategory();
        $mostProductByCategory = $this->getMostProductByCategory();
        $productGrowth = $this->getProductGrowth();
        $currentYear = date('Y');

        $data = [
            'productByCategory' => json_encode($productByCategory),
            'mostProductByCategory' => json_encode($mostProductByCategory),
            'productGrowth' => json_encode($productGrowth),
            'currentYear' => $currentYear
        ];

        //$data['content'] = $parser->setData($pageData)->render('parser/admin/dashboard_statistics');
        return view('section_admin/dashboard', $data);
    }

    private function getProductByCategory()
    {
        $productByCategory = $this->modelProduct->getAllProductsByCategory();

        $backgroundColors = [
            'Food' => 'rgb(255, 205, 86)',
            'Beverage' => 'rgb(75, 192, 192)',
            'Snacks' => 'rgb(153, 102, 255)',
            'Special' => 'rgb(54, 162, 235)',
            'Juice' => 'rgb(255, 159, 64)',
            'Package' => 'rgb(255, 99, 132)'
        ];


        foreach ($productByCategory as $row) {
            $gradeLabels[] = $row['name'] . '=' . $row['totalProducts'];
            $productCounts[] = (int)$row['totalProducts'];
            $colors[] = $backgroundColors[$row['name']];
        }

        $result = [

            'labels' => $gradeLabels,
            'datasets' => [
                [
                    'label' => 'Product by Category',
                    'data' => $productCounts,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 4
                ]

            ]

        ];


        return $result;
    }

    private function getMostProductByCategory()
    {
        $mostProductByCategory = $this->modelProduct->getMostProductByCategory();

        foreach ($mostProductByCategory as $row) {
            $labels[] = $row['name'] . ' (' . $row['totalProducts'] . ')';
            $totalProducts[] = (int)$row['totalProducts'];
        }
        $result = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Products',
                    'data' => $totalProducts,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1
                ],
            ]
        ];

        return $result;
    }

    private function getProductGrowth()
    {
        $productGrowth = $this->modelProduct->getProductGrowth();

        $monthsName = [
            [
                'id' => 1,
                'name' => 'January'
            ],
            [
                'id' => 2,
                'name' => 'February'
            ],
            [
                'id' => 3,
                'name' => 'March'
            ],
            [
                'id' => 4,
                'name' => 'April'
            ],
            [
                'id' => 5,
                'name' => 'May'
            ],
            [
                'id' => 6,
                'name' => 'June'
            ],
            [
                'id' => 7,
                'name' => 'July'
            ],
            [
                'id' => 8,
                'name' => 'August'
            ],
            [
                'id' => 9,
                'name' => 'September'
            ],
            [
                'id' => 10,
                'name' => 'October'
            ],
            [
                'id' => 11,
                'name' => 'November'
            ],
            [
                'id' => 12,
                'name' => 'December'
            ]
        ];

        $defaultProductGrowth = [
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
            0,
        ];

        foreach ($monthsName as $row) {
            $months[] = $row['name'];
        }

        foreach ($productGrowth as $index => $row) {
            $defaultProductGrowth[$index] = (int)$row['totalproducts'];
        }

        $result = [
            'labels' => $months,
            'datasets' => [
                [
                    'label' => 'Total Products',
                    'data' => $defaultProductGrowth,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'tension' => 0.1,
                    'fill' => false
                ]
            ]
        ];

        return $result;
    }


    public function getUsers()
    {
        $params = new DataParams([
            'search' => $this->request->getGet('search'),
            'role' => $this->request->getGet('role'),
            'status'   => $this->request->getGet('status'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page_users'),
            'perPage' => $this->request->getGet('perPage')
        ]);
        $result = $this->modelUser->getFilteredUsers($params);

        $data = [
            //'title' => 'Manajemen Users',
            'accounts' => $result['accounts'],
            'pager' => $result['pager'],
            'total' => $result['total'],
            'params' => $params,
            'role' => $this->modelUser->getAllRoles(),
            'status' => $this->modelUser->getAllStatus(),
            'baseUrl' => base_url('admin/user'),
        ];

        return view('section_admin/user_list', $data);
        //, 'cache' => MINUTE * 15, 'cache_name' => 'cache_user_list'
    }

    public function allUserForm()
    {
        $data = [
            'title' => 'User PDF Report',
        ];
        return view('reports/user', $data);
    }

    public function productByCategoryForm()
    {
        $params = $this->request->getVar('categories');
        $categories = $this->modelProduct->getAllCategories();
        $products = $this->modelProduct->getProductsByCategoryName($params);
        $data = [
            'products' => $products,
            'categories' => $categories,
            'title' => 'User PDF Report',
            'params' => $params ?? '',
        ];

        return view('reports/product', $data);
    }

    public function productByCategoryExcel()
    {
        $params = $this->request->getVar('params');

        $products = $this->modelProduct->getProductsByCategoryName($params);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'PRODUCT REPORTS BY CATEGORIES');
        $sheet->mergeCells('A1:F1');

        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->getStyle('A1')->getFont()->setSize(14);

        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A3', 'Filter:');

        $sheet->setCellValue('B3', 'Categories: ' . ($params ?? 'Semua'));

        $sheet->getStyle('A3:D3')->getFont()->setBold(true);
        $headers = [
            'A5' => 'NO',
            'B5' => 'PRODUCT NAME',
            'C5' => 'CATEGORY',
            'D5' => 'PRICE',
            'E5' => 'STOCK',
            'F5' => 'CREATED AT',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        $row = 6;
        $no = 1;

        foreach ($products as $product) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $product->productName);
            $sheet->setCellValue('C' . $row, $product->categoryName);
            $sheet->setCellValue('D' . $row, $product->price);
            $sheet->setCellValue('E' . $row, $product->stock);
            $sheet->setCellValue('F' . $row, $product->created_at);

            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('"Rp"#,##0.00');

            $row =  $row + 1;
            $no = $no + 1;
        }
        foreach (range('A', 'F') as $column) {

            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Buat border untuk seluruh tabel

        $styleArray = [

            'borders' => [

                'allBorders' => [

                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,

                ],

            ],

        ];

        $sheet->getStyle('A5:F' . ($row - 1))->applyFromArray($styleArray);

        $filename = 'Product_Report_by_Category_' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename="' . $filename . '"');

        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);

        $writer->save('php://output');

        exit();
    }

    public function allUserPdf()
    {
        $result = $this->modelUser->findAll();

        // Generate PDF
        $pdf = $this->initTcpdf();
        $this->generatePdfHtmlContent($pdf, $result);
        // Output PDF
        $filename = 'user_report_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'I');
        exit;
    }

    private function initTcpdf()
    {
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('CodeIgniter 4');
        $pdf->SetAuthor('Administrator');
        $pdf->SetTitle('User Report');
        $pdf->SetSubject('User Report');
        $pdf->SetHeaderData('', 0, 'Online Food Order System', '', [0, 0, 0], [0, 64, 128]);
        $pdf->setFooterData([0, 64, 0], [0, 64, 128]);
        $pdf->setHeaderFont(['helvetica', '', 12]);
        $pdf->setFooterFont(['helvetica', '', 8]);
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(true, 25);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->AddPage();
        return $pdf;
    }

    public function generatePdfHtmlContent($pdf, $students)
    {
        // Set title and filters info
        $title = 'All User from Online Food Order System';

        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetFillColor(220, 220, 220);
        $html = '<h2 style="text-align:center;">' . $title . '</h2>
        <p style="margin-top:30px; text-align:right;">           
         Total User: ' . count($students) . ' 
        </p>
         <table border="1" cellpadding="5" cellspacing="0" style="width:100%;">
           <thead>
           <tr style="background-color:#CCCCCC; font-weight:bold; text-align:center;">
                       <th>No</th>
                       <th>Account ID</th>
                       <th>Full Name</th>
                       <th>Email</th>
                       <th>Role</th>
                       <th>Status</th>
                       <th>Registration Date</th>
                   </tr>
               </thead>
               <tbody>';

        $no = 1;
        foreach ($students as $student) {
            $html .= '
           <tr>
            <td style="text-align:center;">' . $no . '</td>
            <td style="text-align:center;">' . $student->account_id . '</td>
            <td>' . $student->full_name . '</td>
            <td>' . $student->email . '</td>
            <td style="text-align:center;">' . $student->role . '</td>
            <td style="text-align:center;">' . $student->status . '</td>
            <td style="text-align:center;">' . $student->created_at . '</td>
           </tr>';
            $no++;
        }
        $html .= '
         </tbody>
     </table>
    
     ';
        // Write HTML to PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    }
}
