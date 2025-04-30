<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title_az',
        'title_en',
        'title_ru',
        'description_az',
        'description_en',
        'description_ru',
        'image',
        'slug',
        'order',
        'status',
    ];
    
    // İlişkiler
    public function services()
    {
        return $this->hasMany(Service::class);
    }
    
    // Aksesörler
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        $column = "title_" . $locale;
        
        return $this->{$column};
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $column = "description_" . $locale;
        
        return $this->{$column};
    }
    
    // Model yaradıldığında slug avtomatik yaradılsın
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->title_az);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('title_az') && !$category->isDirty('slug')) {
                $category->slug = Str::slug($category->title_az);
            }
        });
    }
    
    // Scopelar
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
