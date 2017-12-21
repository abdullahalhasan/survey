<?php

namespace App\Http\Controllers;

use App\Common;
use App\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
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
     * Gel all company
     *
     * @return HTML view Response.
     */
    public function index()
    {
        $companies= Company::orderBy('id','DESC')->paginate(10);
        $companies->setPath(url('admin/company/all'));
        $company_pagination = $companies->render();
        $data['company_pagination'] = $company_pagination;
        $data['companies'] = $companies;
        $data['page_title'] = $this->page_title;
        return view('company.index',$data);
    }

    /**
     * Creating form for company.
     *
     * @param Request $request
     * @return HTML view Response.
     */
    public function create(Request $request)
    {
        $data['page_title'] = $this->page_title;
        return view('company.create',$data);
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
            'name' => 'required|unique:company',
            'address' => 'required',
            'mobile' => 'required',
            'description' => 'required'
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = array(
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'mobile' => $request->input('mobile'),
            'description' => $request->input('description'),
            'web_url' => $request->input('web_url'),
            'created_at' =>$now,
            'updated_at' =>$now,
            'created_by' =>\Auth::user()->id,
            'updated_by' =>\Auth::user()->id,
        );
        try {
            \DB::table('company')->insert($data);
            \App\System::EventLogWrite('inser,company',json_encode($data));
            return redirect()->back()->with('message','Record Inserted Successfully !!');
        } catch (\Exception $e){
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect()->back()->with('errormessage','Something is wrong! ');
        }

    }

    /**
     * Show company by $id.
     *
     * @param int $id
     * @return HTMl view Response
     */
    public function show($id)
    {
        $company = Company::find($id);
        if (is_null($company)) {
            return redirect('admin/company')->with('errormessage',"Sorry data not found");
        }
        $data['company'] = $company;
        $data['page_title'] = $this->page_title;
        return view('company.ajax-company-show',$data);
    }

    /**
     * Show form for editing company by $id.
     *
     * @param int $id
     * @return HTML view Response
     */
    public function edit($id)
    {
        $company = Company::find($id);
        if (is_null($company)) {
            return redirect('admin/company')->with('errormessage',"Sorry data not found");
        }
        $data['company'] = $company;
        $data['page_title'] = $this->page_title;
        return view('company.edit',$data);
    }

    /**
     * Update company by $id.
     *
     * @param Request $request
     * @param int $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $now = date('Y-m-d H:i:s');
        $v = \Validator::make($request->all(), [
            'name' => 'required|unique:company,name,'.$id.',id',
            'address' => 'required',
            'mobile' => 'required',
            'description' => 'required'
        ]);

        if ($v->fails()) {
            return redirect()->back()->withErrors($v)->withInput();
        }
        $data = array(
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'mobile' => $request->input('mobile'),
            'description' => $request->input('description'),
            'web_url' => $request->input('web_url'),
            'updated_at' =>$now,
            'updated_by' =>\Auth::user()->id,
        );
        try{
            \DB::table('company')->where('id',$id)->update($data);
            \App\System::EventLogWrite('update,company',json_encode($data));
            return redirect('admin/company/edit/'.$id)->with('message',"Record Updated Successfully !!");
        } catch(\Exception $e) {
            $message = "Message : ".$e->getMessage().", File : ".$e->getFile().", Line : ".$e->getLine();
            \App\System::ErrorLogWrite($message);
            return redirect('admin/company/edit/'.$id)->with('errormessage',"Something is wrong");
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
        \DB::table('company')
            ->where('id',$id)
            ->delete();
        return response()->json(['status' => 1]);
    }

    /**
     * Search company by name.
     *
     * @param Request $request
     * @return HTML view Response
     */
    public function searchByCompanyName(Request $request)
    {
        $name = $request->get('name');
        if($name !='' ){
            $companies=\DB::table('company')
                ->where('name','like', '%' . $name . '%')
                ->paginate(8);
            $companies->setPath(url('admin/company'));
            $company_pagination = $companies->render();
            $data['company_pagination'] = $company_pagination;
            $data['companies'] = $companies;
        } else {
            $companies= Company::orderBy('id','DESC')->paginate(10);
            $companies->setPath(url('admin/company/all'));
            $company_pagination = $companies->render();
            $data['company_pagination'] = $company_pagination;
            $data['companies'] = $companies;
        }
        return view('company.ajax-company-search',$data);
    }

    /**
     * Search company by mobile number.
     *
     * @param Request $request
     * @return HTML view Response.
     */
    public function searchByMobile(Request $request)
    {
        $name = $request->get('mobile');
        if($name !='' ){
            $companies=\DB::table('company')
                ->where('mobile','like', '%' . $name . '%')
                ->paginate(10);
            $companies->setPath(url('admin/company'));
            $company_pagination = $companies->render();
            $data['company_pagination'] = $company_pagination;
            $data['companies'] = $companies;
        } else {
            $companies= Company::orderBy('id','DESC')->paginate(10);
            $companies->setPath(url('admin/company/all'));
            $company_pagination = $companies->render();
            $data['company_pagination'] = $company_pagination;
            $data['companies'] = $companies;
        }
        return view('company.ajax-company-search',$data);
    }

}
