<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Announcement
 * @package App\Models
 * @property int $id
 * @property string $announcement_id
 * @property string $url
 * @property float $price
 */
class Announcement extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'announcement_id',
        'price',
        'url'
    ];

    /**
     * @return BelongsToMany
     */
    public function subscribers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tracked_ads', 'announcement_id', 'user_id');
    }
}
