<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_id',
        'name_en',
        'slug',
        'description',
    ];

    public function localizedName(?string $locale = null): string
    {
        $locale = $locale ?? session('locale', 'id');

        return ($locale === 'en' ? $this->name_en : $this->name_id)
            ?: $this->name;
    }

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }
}
