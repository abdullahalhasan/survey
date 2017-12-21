<?php

namespace App\Http\Controllers;

use App\CampaignCategory;
use Illuminate\Http\Request;
class SurveyCampaignController extends Controller
{
    /**
     * Class constructor.
     * get current route name for page title
     * data write to access_log table.
     *
     * @param Request $request;
     */
    public function __construct(Request $request)
    {
        $this->page_title = $request->route()->getName();
        //\App\System::AccessLogWrite();
    }
    /**
     * Gel all campaign survey
     *
     * @return HTML view Response.
     */
    public function index()
    {
        $surveyCampaigns= \DB::table('survey_campaign')
            ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
            ->select('survey_campaign.*','campaign_category.name')
            ->orderBy('survey_campaign.id','DESC')
            ->paginate(10);
        $surveyCampaigns->setPath(url('admin/survey/campaign'));
        $surveyCampaignsPagination = $surveyCampaigns->render();
        $data['surveyCampaignsPagination'] = $surveyCampaignsPagination;
        $data['surveyCampaigns'] = $surveyCampaigns;
        $data['page_title'] = $this->page_title;
        return view('campaign-survey.index',$data);
    }

    /**
     * creating form for survey campaign
     * get all campaign category.
     *
     * @return HTMl view response
     */
    public function create()
    {
        $categories = CampaignCategory::all();
        $data['categories'] = $categories;
        $data['page_title'] = $this->page_title;
        return view('campaign-survey.create',$data);
    }

    public function store(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'campaign_category_id' => 'required',
            'campaign_title' => 'required',
            'campaign_owner' => 'required',
            'active_date' => 'required|date',
            'expire_date' => 'required|date',
            'campaign_incentive_amount' => 'required',
            'campaign_incentive_point' => 'required',
            'campaign_instructions' => 'required',
            'campaign_ending_text' => 'required'
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = array(
            'campaign_category_id' => $request->input('campaign_category_id'),
            'campaign_name' => 'campaign_title',
            'campaign_title' => $request->input('campaign_title'),
            'campaign_owner' => $request->input('campaign_owner'),
            'active_date' => $request->input('active_date'),
            'expire_date' => $request->input('expire_date'),
            'campaign_incentive_amount' => $request->input('campaign_incentive_amount'),
            'campaign_incentive_point' => $request->input('campaign_incentive_point'),
            'campaign_instructions' => $request->input('campaign_instructions'),
            'campaign_ending_text' => $request->input('campaign_ending_text'),
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        try {
            \DB::table('survey_campaign')->insert($data);
            \App\System::EventLogWrite('insert,survey campaign',json_encode($data));
            return redirect()->back()->with('message','Record Inserted Successfully !!');
        } catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Something is wrong! ');
        }
    }

    /**
     * Show survey campaign by $id.
     *
     * @param int $id
     * @return HTMl view Response
     */
    public function show($id)
    {
        $surveyCampaign= \DB::table('survey_campaign')
            ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
            ->select('survey_campaign.*','campaign_category.id','campaign_category.name')
            ->where('survey_campaign.id',$id)
            ->first();
        if (is_null($surveyCampaign)) {
            return redirect('admin/survey/campaign')->with('errormessage',"Sorry data not found");
        }
        $data['surveyCampaign'] = $surveyCampaign;
        $data['page_title'] = $this->page_title;
        return view('campaign-survey.ajax-show',$data);
    }

    /**
     * Show form for editing survey campaign by $id.
     *
     * @param int $id
     * @return HTML view Response
     */
    public function edit($id)
    {
        $surveyCampaign= \DB::table('survey_campaign')
            ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
            ->select('survey_campaign.*','campaign_category.id','campaign_category.name')
            ->where('survey_campaign.id',$id)
            ->first();
        if (is_null($surveyCampaign)) {
            return redirect('admin/survey/campaign')->with('errormessage',"Sorry data not found");
        }
        $categories = CampaignCategory::all();
        $data['surveyCampaign'] = $surveyCampaign;
        $data['categories'] = $categories;
        $data['page_title'] = $this->page_title;
        return view('campaign-survey.edit',$data);
    }
    /**
     * Update survey campaign by $id.
     *
     * @param Request $request
     * @param int $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'campaign_category_id' => 'required',
            'campaign_title' => 'required',
            'campaign_owner' => 'required',
            'active_date' => 'required|date',
            'expire_date' => 'required|date',
            'campaign_incentive_amount' => 'required',
            'campaign_incentive_point' => 'required',
            'campaign_instructions' => 'required',
            'campaign_ending_text' => 'required'
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = array(
            'campaign_category_id' => $request->input('campaign_category_id'),
            'campaign_name' => 'campaign_title',
            'campaign_title' => $request->input('campaign_title'),
            'campaign_owner' => $request->input('campaign_owner'),
            'active_date' => $request->input('active_date'),
            'expire_date' => $request->input('expire_date'),
            'campaign_incentive_amount' => $request->input('campaign_incentive_amount'),
            'campaign_incentive_point' => $request->input('campaign_incentive_point'),
            'campaign_instructions' => $request->input('campaign_instructions'),
            'campaign_ending_text' => $request->input('campaign_ending_text'),
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->id,
        );
        try{
            \DB::table('survey_campaign')->where('id',$id)->update($data);
            \App\System::EventLogWrite('update,campaign survey',json_encode($data));
            return redirect('admin/survey/campaign/edit/'.$id)->with('message',"Record Updated Successfully !!");
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('admin/survey/campaign/edit/'.$id)->with('errormessage',"Something is wrong");
        }

    }

    /**
     * Delete company by $id
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        if ($id === "undefined" || $id === null) {
            return response()->json(['status' => 0]);
        }
        $delete = \DB::table('survey_campaign')
            ->where('id',$id)
            ->delete();
        if($delete) {
            \DB::table('survey_question')
                ->where('campaign_id',$id)
                ->delete();
        }
        return response()->json(['status' => 1]);
    }

    /***
     * @param int $campaign_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish($campaign_id)
    {
        $now = date('Y-m-d H:i:s');
        $question = \DB::table('survey_question')->where('campaign_id',$campaign_id)->count();
        if($question > 0) {
            $publish = \DB::table('survey_campaign')
                ->where('id',$campaign_id)
                ->update(array(
                    'status' => 1,
                    'updated_at' =>$now,
                    'updated_by' =>\Auth::user()->id,
                ));
            if($publish) {
                return response()->json(['status'=>1]);
            }
        }
        return response()->json(['status' => 0]);
    }

    /***
     * @param int $campaign_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unpublish($campaign_id)
    {
        $now = date('Y-m-d H:i:s');
        $publish = \DB::table('survey_campaign')
            ->where('id',$campaign_id)
            ->update(array(
                'status' => 0,
                'updated_at' =>$now,
                'updated_by' =>\Auth::user()->id,
            ));
        if($publish) {
            return response()->json(['status'=>1]);
        }
        return response()->json(['status' => 0]);
    }

    /**
     * Search survey campaign by title.
     *
     * @param Request $request
     * @return HTML view Response
     */
    public function searchByCampaignTitle(Request $request)
    {
        $title = $request->get('campaign_title');
        if($title !='' ){
            $surveyCampaigns= \DB::table('survey_campaign')
                ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
                ->select('survey_campaign.*','campaign_category.id','campaign_category.name')
                ->where('campaign_title','like', '%' . $title . '%')
                ->paginate(10);
            $surveyCampaigns->setPath(url('admin/survey/campaign'));
            $surveyCampaignsPagination = $surveyCampaigns->render();
            $data['surveyCampaignsPagination'] = $surveyCampaignsPagination;
            $data['surveyCampaigns'] = $surveyCampaigns;
        } else {
            $surveyCampaigns= \DB::table('survey_campaign')
                ->leftjoin('campaign_category','survey_campaign.campaign_category_id','=','campaign_category.id')
                ->select('survey_campaign.*','campaign_category.id','campaign_category.name')
                ->paginate(10);
            $surveyCampaigns->setPath(url('admin/survey/campaign'));
            $surveyCampaignsPagination = $surveyCampaigns->render();
            $data['surveyCampaignsPagination'] = $surveyCampaignsPagination;
            $data['surveyCampaigns'] = $surveyCampaigns;
        }
        return view('campaign-survey.ajax-survey-campaign-search',$data);
    }

}
