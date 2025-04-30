<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'service_category_id',
        'title_az',
        'title_en',
        'title_ru',
        'description_az',
        'description_en',
        'description_ru',
        'image',
        'icon',
        'slug',
        'order',
        'status',
    ];
    
    // İlişkiler
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
    
    // Aksessorlar
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
        
        static::creating(function ($service) {
            if (!$service->slug) {
                $service->slug = Str::slug($service->title_az);
            }
        });
        
        static::updating(function ($service) {
            if ($service->isDirty('title_az') && !$service->isDirty('slug')) {
                $service->slug = Str::slug($service->title_az);
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
