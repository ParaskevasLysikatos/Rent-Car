<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int         $id
 * @property string      $created_at
 * @property string      $updated_at
 * @property int         $language_id
 * @property int         $program_id
 * @property string      $title
 */
class ProgramProfile extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program() {
        return $this->belongsTo(Program::class);
    }
}
