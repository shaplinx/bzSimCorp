<?php

namespace App\Models\Documents;

use Illuminate\Database\Eloquent\Model;

class Document extends Model implements App\Contracts\FilterableContract
{
    use App\Traits\Filterable;

    protected $table = 'documents';
    public $timestamps = true;
    protected $fillable = array('title', 'number', 'author', 'about', 'status', 'signed_by', 'signed_place', 'signed_at', 'category_id');

    /**
     * Get the category that owns the Documents
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(App\Models\Documents\DocumentCategory::class, 'foreign_key', 'other_key');
    }

    /**
     * The revising that belong to the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function revising(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'revisions', 'revised_id','revising_id')
            ->using(App\Models\Documents\Revision::class);
    }

        /**
     * The revised that belong to the Document
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function revised(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'revisions', 'revising_id','revised_id')
            ->using(App\Models\Documents\Revision::class);
    }

}