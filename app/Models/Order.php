<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource; // Для коректної роботи з Orchid

class Order extends Model
{
    use HasFactory, AsSource;

    /**
     * Поля, які можна заповнювати масово
     */
    protected $fillable = [
        'keycrm_id',
        'client_name',
        'phone',
        'status',
        'grand_total',
        'raw_data',
    ];

    /**
     * Атрибути, які слід перетворювати до нативних типів
     */
    protected $casts = [
        'raw_data' => 'array', // Автоматично перетворює JSON з БД в масив PHP
        'grand_total' => 'decimal:2',
    ];
}