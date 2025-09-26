<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description'
    ];

    /**
     * Get all profiles belonging to this department.
     */
    public function profiles()
    {
        return $this->hasMany(Profile::class);
    }

    /**
     * Get the department's full identifier (code - name).
     *
     * @return string
     */
    public function getFullIdentifier(): string
    {
        return trim(sprintf(
            '%s - %s',
            $this->code,
            $this->name
        ));
    }
}
