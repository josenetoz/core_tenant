<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PromotionCode extends Model
{
   use HasFactory;

   protected $fillable = [
      'id_promotional_code',
      'active',
      'code',
      'id_cupom_code',
      'duration',
      'duration_in_months',
      'percent_off',
      'max_redemptions',
      'redeem_by',
      'customer',
      'valid',
      'first_time_transaction',
      'expires_at',
   ];

}
