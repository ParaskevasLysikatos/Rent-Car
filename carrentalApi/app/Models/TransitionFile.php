<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransitionFile extends Model
{
    /**
     * @property int    $id
     * @property int    $transition_id
     */
    protected $table="transition_file";

    protected $fillable = [
        'transition_id',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'id', 'document_id');
    }
}
