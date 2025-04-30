<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name_az',
        'name_en',
        'name_ru',
        'position_az',
        'position_en',
        'position_ru',
        'biography_az',
        'biography_en',
        'biography_ru',
        'image',
        'social_accounts',
        'order',
        'status',
    ];
    
    protected $casts = [
        'social_accounts' => 'array',
        'status' => 'boolean',
    ];
    
    // Aksesörler - Mevcut dilde içeriği almak için
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $column = "name_" . $locale;
        
        return $this->{$column};
    }

    public function getPositionAttribute()
    {
        $locale = app()->getLocale();
        $column = "position_" . $locale;
        
        return $this->{$column};
    }

    public function getBiographyAttribute()
    {
        $locale = app()->getLocale();
        $column = "biography_" . $locale;
        
        return $this->{$column};
    }

    // Mutator - Sosyal medya hesapları için
    public function setSocialAccountsAttribute($value)
    {
        if (is_array($value)) {
            // Boş değerleri filtrele
            $filteredAccounts = array_filter($value, function($account) {
                return !empty($account['platform']) && !empty($account['url']);
            });
            
            $this->attributes['social_accounts'] = json_encode(array_values($filteredAccounts));
        } else {
            $this->attributes['social_accounts'] = $value;
        }
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