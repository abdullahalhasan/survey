<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    /**
     * Class constructor.
     * get current route name for page title.
     *
     * data write to access_log table.
     */
    public function __construct()
    {
        $this->page_title = \Request::route()->getName();
        \App\System::AccessLogWrite();
    }
    /**
     * Show page for 404 error.
     *
     * @return HTML view Response.
     */
    public function Error404()
    {
        $data['page_title'] = $this->page_title;
        return \View::make('errors.404', $data);
    }
    /**
    * Show page for 500 error.
    *
    * @return HTML view Response.
    */
    public function Error500()
    {
        $data['page_title'] = $this->page_title;
        return \View::make('errors.500', $data);
    }
    /**
    * Show page for 503 error.
    *
    * @return HTML view Response.
    */
    public function Error503()
    {
        $data['page_title'] = $this->page_title;
        return \View::make('errors.503', $data);
    }
}
