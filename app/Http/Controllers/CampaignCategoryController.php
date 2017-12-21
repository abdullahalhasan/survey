<?php

namespace App\Http\Controllers;

use App\CampaignCategory;
use App\User;
use Illuminate\Http\Request;

class CampaignCategoryController extends Controller
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
     * Gel all campaign category
     *
     * @return HTML view Response.
     */
    public function index()
    {
        $categories= CampaignCategory::orderBy('id','DESC')->paginate(10);
        $categories->setPath(url('admin/campaign/category'));
        $category_pagination = $categories->render();
        $data['category_pagination'] = $category_pagination;
        $data['categories'] = $categories;
        $data['page_title'] = $this->page_title;
        return view('campaign-category.create',$data);
    }

    /**
     * store data into database.
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required|unique:campaign_category',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $name_slug = explode(' ', strtolower($request->input('name')));
        $name_new_slug = implode('.', $name_slug);
        $data = array(
            'name' => $request->input('name'),
            'name_slug' => $name_new_slug,
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        try {
            \DB::table('campaign_category')->insert($data);
            \App\System::EventLogWrite('insert,campaing category',json_encode($data));
            return redirect()->back()->with('message','Record Inserted Successfully !!');
        } catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Something is wrong! ');
        }

    }

    /**
     * Show campaign category by $id.
     *
     * @param int $id
     * @return HTMl view Response
     */
    public function show($id)
    {
        $category = CampaignCategory::find($id);
        if (is_null($category)) {
            return redirect('admin/campaign/category')->with('errormessage',"Sorry data not found");
        }
        $created_by = User::find($category->created_by);
        $updated_by = User::find($category->updated_by);
        $data['created_by'] = $created_by;
        $data['updated_by'] = $updated_by;
        $data['category'] = $category;
        $data['page_title'] = $this->page_title;
        return view('campaign-category.ajax-show',$data);
    }

    /**
     * Show form for editing campaign category by $id.
     *
     * @param int $id
     * @return HTML view Response
     */
    public function edit($id)
    {
        $category = CampaignCategory::find($id);
        if (is_null($category)) {
            return redirect('admin/campaign/category')->with('errormessage',"Sorry data not found");
        }
        $data['category'] = $category;
        $categories= CampaignCategory::orderBy('id','DESC')->paginate(10);
        $categories->setPath(url('admin/campaign/category'));
        $category_pagination = $categories->render();
        $data['category_pagination'] = $category_pagination;
        $data['categories'] = $categories;
        $data['page_title'] = $this->page_title;
        return view('campaign-category.edit',$data);
    }

    /**
     * Update campaign category by $id.
     *
     * @param Request $request
     * @param int $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required|unique:campaign_category,name,'.$id.',id',
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $name_slug = explode(' ', strtolower($request->input('name')));
        $name_new_slug = implode('.', $name_slug);
        $data = array(
            'name' => $request->input('name'),
            'name_slug' => $name_new_slug,
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->id,
        );
        try{
            \DB::table('campaign_category')->where('id',$id)->update($data);
            \App\System::EventLogWrite('update,campaign category',json_encode($data));
            return redirect('admin/campaign/category/edit/'.$id)->with('message',"Record Updated Successfully !!");
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('admin/campaign/category/edit/'.$id)->with('errormessage',"Something is wrong");
        }

    }

    /**
     * Delete campaign category by $id.
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        if ($id === "undefined" || $id === null) {
            return response()->json(['status' => 0]);
        }
        \DB::table('campaign_category')
            ->where('id',$id)
            ->delete();
        return response()->json(['status' => 1]);

    }

    /**
     * Search campaign category by name.
     *
     * @param Request $request
     * @return HTML view Response
     */
    public function searchByCategoryName(Request $request)
    {
        $name = $request->get('name');
        if($name !='' ){
            $categories=\DB::table('campaign_category')
                ->where('name','like', '%' . $name . '%')
                ->paginate(10);
            $categories->setPath(url('admin/campaign/category'));
            $category_pagination = $categories->render();
            $data['category_pagination'] = $category_pagination;
            $data['categories'] = $categories;
        } else {
            $categories= CampaignCategory::orderBy('id','DESC')->paginate(10);
            $categories->setPath(url('admin/campaign/category'));
            $category_pagination = $categories->render();
            $data['category_pagination'] = $category_pagination;
            $data['categories'] = $categories;
        }
        return view('campaign-category.ajax-category-search',$data);
    }

}
