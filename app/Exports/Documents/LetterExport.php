<?php

namespace App\Exports\Documents;

use App\Models\Documents\Letter;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LetterExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function query()
    {
        return Letter::query()->select([
            'id',
            'letter_number',
            'institution_id',
            'classification_id',
            'subject',
            'recipient',
            'letter_date',
            'file_path',
            "voided_at",
            "issued_at",
            "public",
        ]);
    }

    public function headings(): array
    {
        return [
            'ID',
            "Letter's Number",
            'Institution',
            'Classification',
            'Subject',
            'Recipient',
            'Letter Date',
            'File Path',
            'Voided At',
            'Issued At',
            'Public',
        ];
    }
}
