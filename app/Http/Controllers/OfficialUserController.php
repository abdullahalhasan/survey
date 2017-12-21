<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfficialUserController extends Controller
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

    public function index()
    {
        $data['page_title'] = $this->page_title;
        return view('admin.index', $data);
    }
}
