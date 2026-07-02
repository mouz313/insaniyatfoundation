<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodInventory extends Model
{
    protected $table = 'blood_inventory';

    protected $fillable = [
        'blood_group', 'units', 'batch_no', 'received_date', 'expiry_date',
        'source', 'source_campaign_id', 'location', 'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'received_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class, 'source_campaign_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function stockByGroup(): array
    {
        return self::where('status', 'available')
            ->selectRaw('blood_group, SUM(units) as total')
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();
    }

    public static function lowStockGroups(int $threshold = 5, ?array $stock = null): array
    {
        $stock ??= self::stockByGroup();
        $low = [];
        foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg) {
            $units = $stock[$bg] ?? 0;
            if ($units <= $threshold) {
                $low[$bg] = $units;
            }
        }
        return $low;
    }
}
