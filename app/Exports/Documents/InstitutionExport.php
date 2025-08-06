<?php

namespace App\Exports\Documents;

use App\Models\Documents\Institution;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InstitutionExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Institution::query()->select(
            'id',
            'code',
            'name',
            'reset_sn_period',
            'sn_template',
        );
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Name',
            'Reset SN Period',
            'SN Template',
        ];
    }
}
