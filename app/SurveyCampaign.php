<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SurveyCampaign extends Model
{
    //
    //
    public $table = "campaign_category";
    protected $fillable = [
        'name', 'name_slug'
    ];

    public static function getSurveyCampaign()
    {
        $survey_campaign = \DB::table('survey_campaign')
            ->leftjoin('campaign_category','survey_campaign.campaign_category_id', '=','campaign_category.id')
            ->select('survey_campaign.*','campaign_category.id','campaign_category.name')
            ->get();
        return $survey_campaign;
    }

    public static function getCampaignTitleByCategoryId($category_id)
    {
        $campaignTitles = \DB::table('survey_campaign')
            ->where('campaign_category_id',$category_id)
            ->get();
        return $campaignTitles;
    }
    public static function getCampaignTitleById($id)
    {
        $campaignCategory = \DB::table('survey_campaign')
            ->leftjoin('campaign_category','survey_campaign.campaign_category_id', '=','campaign_category.id')
            ->select('survey_campaign.*','campaign_category.id','campaign_category.name')
            ->where('survey_campaign.id',$id)
            ->first();
        return $campaignCategory;
    }
}
