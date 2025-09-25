<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'unique_identifier', // e.g., student number or employee ID
        'position', // e.g., job title or OJT
        'property_id'
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the full name of the profile, with the middle name shortened to its initials.
     *
     * Example:
     *  - middle_name: "San Jose" → "S.J."
     *
     * @return string
     */
    public function getFullName(): string
    {
        $middleInitials = '';

        if (!empty($this->middle_name)) {
            $words = preg_split('/\s+/', trim($this->middle_name));
            $initials = array_map(fn($word) => strtoupper($word[0]), $words);
            $middleInitials = implode('.', $initials) . '.';
        }

        return trim(sprintf(
            '%s %s %s',
            $this->first_name,
            $middleInitials,
            $this->last_name
        ));
    }
}
