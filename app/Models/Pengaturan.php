<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturans';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Helper to set a settings value.
     */
    public static function setVal(string $key, $value): self
    {
        return self::updateOrCreate(['key' => $key], ['value' => (string) $value]);
    }

    /**
     * Helper to get a settings value.
     */
    public static function getVal(string $key, $default = null): ?string
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
