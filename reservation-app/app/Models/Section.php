<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Institution;
class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function institutions()
    {
    return $this->belongsToMany(Institution::class, 'sections_in_institutions')->withPivot('institution_id');
    }

}
