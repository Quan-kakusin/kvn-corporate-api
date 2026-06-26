<?php

namespace App\Models\Wordpress;

use Illuminate\Database\Eloquent\Model;

class WpTerm extends Model
{
    protected $table = 'wp_terms';
    protected $primaryKey = 'term_id';
}
