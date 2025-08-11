<?php

namespace App\Exports\ShortURL;

use App\Models\ShortURL\ShortURL;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ShortURLExport implements FromQuery,WithHeadings
{
     use Exportable;

    
    private $model;

    public function __construct(ShortURL $model)
    {
        $this->model = $model;
    }
    public function query()
    {
        return $this->model->visits();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Short URL ID',
            'IP Address',
            'Operating System',
            'OS Version',
            'Browser',
            'Browser Version',
            'Referer',
            'Device type',
            'Visited At',
            'Created At',
            'Updated At'

        ];
    }
}
