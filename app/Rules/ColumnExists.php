<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Schema;

class ColumnExists implements ValidationRule
{

    protected $table;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table)
    {
        $this->table = $table;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if  (!Schema::hasColumn($this->table, $value)) {
            $fail(__('The selected column does not exist in the table.'));
        }
    }
}
