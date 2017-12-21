<div class="row">
    <div class="col-sm-12">
        <!-- start: PAGE TITLE & BREADCRUMB -->
        <ol class="breadcrumb">
            <li>
                <i class="clip-home-3"></i>
                Survey System
            </li>
            <li class="active">
                <a href="{{\Request::url()}}">
                    {{isset($page_title) ? $page_title : ''}}
                </a>
            </li>
            <li class="search-box">
                <form class="sidebar-search">
                    <div class="form-group">
                        <input type="text" placeholder="Start Searching...">
                        <button class="submit">
                            <i class="clip-search-3"></i>
                        </button>
                    </div>
                </form>
            </li>
        </ol>
        <div class="page-header">
            <h1>{{isset($page_title) ? $page_title : ''}} <small>{{isset($page_title) ? strtolower($page_title) : ''}} page</small></h1>
        </div>
        <!-- end: PAGE TITLE & BREADCRUMB -->
    </div>
</div>