<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'expense_number',
        'name',
        'category',
        'amount',
        'date',
        'description',
        'receipt_path',
        'receipt_original_name',
    ];

    protected $casts = [
        'amount' => 'float',
        'date' => 'date',
    ];

    public const CATEGORIES = [
        'BBM' => 'BBM',
        'Perawatan dan Servis Jeep' => 'Perawatan dan Servis Jeep',
        'Perlengkapan Wisata' => 'Perlengkapan Wisata',
        'Operasional Pos' => 'Operasional Pos',
        'Kebersihan' => 'Kebersihan',
        'Listrik dan Air' => 'Listrik dan Air',
        'Konsumsi' => 'Konsumsi',
        'Lain-lain' => 'Lain-lain',
    ];

    protected static function booted()
    {
        static::creating(function ($expense) {
            if (empty($expense->expense_number)) {
                $dateStr = $expense->date ? Carbon::parse($expense->date)->format('Ymd') : Carbon::now()->format('Ymd');
                $countToday = static::whereDate('date', $expense->date ?? Carbon::now())->count() + 1;
                $expense->expense_number = 'EXP-' . $dateStr . '-' . str_pad($countToday, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->translatedFormat('d M Y');
    }

    public function getReceiptUrlAttribute(): ?string
    {
        if (empty($this->receipt_path)) {
            return null;
        }

        if (str_starts_with($this->receipt_path, 'http://') || str_starts_with($this->receipt_path, 'https://')) {
            return $this->receipt_path;
        }

        $cleanPath = ltrim(preg_replace('#^/?(public/)?#', '', $this->receipt_path), '/');
        return asset($cleanPath);
    }

    public function getIsReceiptPdfAttribute(): bool
    {
        if (empty($this->receipt_path)) return false;
        $ext = strtolower(pathinfo($this->receipt_path, PATHINFO_EXTENSION));
        return $ext === 'pdf';
    }

    public function getIsReceiptImageAttribute(): bool
    {
        if (empty($this->receipt_path)) return false;
        $ext = strtolower(pathinfo($this->receipt_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'avif']);
    }
}
