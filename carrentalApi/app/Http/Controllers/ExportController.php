<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Http\Resources\CombinedResource;
use App\Http\Resources\CombinedResourceAgents;
use App\Http\Resources\CombinedResourceBookings;
use App\Http\Resources\CombinedResourceVehicles;
use App\Http\Resources\CombinedResourceBookingSources;
use App\Http\Resources\CombinedResourceInvoices;
use App\Http\Resources\CombinedResourcePayments;
use App\Http\Resources\CombinedResourceUsers;

use App\Http\Resources\CombinedResourceTypes;

use App\Http\Resources\CombinedResourceQuotes;

use App\Http\Resources\CombinedResourceRentals;


class ExportController extends Controller
{
    public static function createFileFromCollection(Collection $collection, array $columns, $filename = 'report') {
        $columns = array_filter($columns, function ($item) {
            return isset($item['enabled']) && $item['enabled'] === 'on';
        });
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $col = 1;
        foreach ($columns as $column => $value) {
            if (isset($value['text'])) {
                $sheet->setCellValueByColumnAndRow($col, 1, $value['text']);
                $col++;
            }
        }

        $row = 2;
        foreach ($collection as $single) {
            $col = 1;
            foreach ($columns as $column => $value) {
                $nested = explode('.', $column);
                $value = $single;
                foreach ($nested as $nest) {
                    if ($value) {
                        $value = $value->{$nest};
                    }
                }
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }
        $xslx = new Xlsx($spreadsheet);
        $response =  new StreamedResponse(
            function () use ($xslx) {
                $xslx->save('php://output');
            }
        );
        $filename .= '.xlsx';
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="'. $filename .'"');
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Cache-Control','max-age=0');

        return $response;
    }

    public function combinedCollections(Request $request){
        return new CombinedResource($request);
    }

    public function combinedCollectionsVehicles(Request $request)
    {
        return new CombinedResourceVehicles($request);
    }

    public function combinedCollectionsBookingSources(Request $request)
    {
        return new CombinedResourceBookingSources($request);
    }

    public function combinedCollectionsUsers(Request $request)
    {
        return new CombinedResourceUsers($request);
    }

    public function combinedCollectionsTypes(Request $request)
    {
        return new CombinedResourceTypes($request);
    }

    public function combinedCollectionsAgents(Request $request)
    {
        return new CombinedResourceAgents($request);
    }

    public function combinedCollectionsInvoices(Request $request)
    {
        return new CombinedResourceInvoices($request);
    }

    public function combinedCollectionsPayments(Request $request)
    {
        return new CombinedResourcePayments($request);
    }

    public function combinedCollectionsQuotes(Request $request)
    {
        return new CombinedResourceQuotes($request);
    }

    public function combinedCollectionsBookings(Request $request)
    {
        return new CombinedResourceBookings($request);
    }

    public function combinedCollectionsRentals(Request $request)
    {
        return new CombinedResourceRentals($request);
    }
}