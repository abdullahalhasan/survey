<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignCategory extends Model
{
    //
    public $table = "campaign_category";
    protected $fillable = [
        'name', 'name_slug'
    ];

    public function surveyCampaign()
    {
        return $this->hasOne('SurveyCampaign');
    }

}
