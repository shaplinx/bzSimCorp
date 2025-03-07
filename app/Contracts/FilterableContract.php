<?
namespace App\Contracts;

interface FilterableContract
{
    /**
     * Define searchable columns for the model.
     *
     * @return array
     */
    public static function searchableColumns(): array;
}