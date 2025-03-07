<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model implements App\Contracts\FilterableContract
{
    use App\Traits\Filterable;
    
    protected $table = 'document_categories';
    public $timestamps = true;

    protected $fillable = [
        "name",
        "slug",
        "description"
    ];

    /**
     * Get all of the documents for the DocumentCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(App\Models\Documents\Document::class, 'category_id', 'id');
    }

}