<?php

namespace Citadelle\ReferentielApi\app;

use Illuminate\Database\Eloquent\Model;

class Correspondance extends Model
{

    public $timestamps = false;


    protected $fillable = [
        'source_reference',
        'referentiel_id',
        'referentiel_type',
    ];

}
