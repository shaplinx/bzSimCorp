<?php

namespace App\Exports\Documents;

use App\Models\Documents\Classification;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClassificationExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Classification::query()->select(
            'id',
            'code',
            'name',
            'classification_separator',
            'parent_id'
        );
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Name',
            'Classification Separator',
            'Parent',
        ];
    }
}
