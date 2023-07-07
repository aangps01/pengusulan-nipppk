<?php

namespace App\Exports\Exports;

use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use Maatwebsite\Excel\Concerns\FromView;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class LaporanPermohonanExport extends DefaultValueBinder implements FromView, ShouldAutoSize, WithCustomValueBinder
{
    private $permohonans;

    public function __construct($permohonans)
    {
        $this->permohonans = $permohonans;
    }
    /**
    * @return \Illuminate\Support\View
    */
    public function view(): View
    {
        $permohonans = $this->permohonans;
        return view('exports.laporan-permohonan', compact('permohonans'));
    }

   public function bindValue(Cell $cell, $value)
   {
       if (is_numeric($value)) {
           $cell->setValueExplicit($value, DataType::TYPE_STRING);
           return true;
       }

       // else return default behavior
       return parent::bindValue($cell, $value);
   }
}
