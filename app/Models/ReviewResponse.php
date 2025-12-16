<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'review_responses';
    protected $primaryKey = 'id_response';
    protected $fillable = ['id_review', 'id_seniman', 'tanggapan'];

    public function review()
    {
        return $this->belongsTo(Review::class, 'id_review', 'id_review');
    }

    public function seniman()
    {
        return $this->belongsTo(Seniman::class, 'id_seniman', 'id_seniman');
    }
}