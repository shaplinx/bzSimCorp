<?php

namespace App\Rules;

use App\Models\Finance\Transaction;
use App\Models\Finance\TransactionCategory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FinanceCategoryHasRightType implements ValidationRule
{

    protected string $type;

    // Pass the value of the other field via the constructor
    public function __construct(string $type)
    {
        $this->type = $type;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $category = TransactionCategory::find($value);
        if (!$category) $fail("$attribute does not exist");
        if ($category->type !== $this->type) $fail("$attribute's type is not the right one");

    }
}
