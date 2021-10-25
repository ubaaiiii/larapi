<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Rubick Tailwind HTML Admin Template" class="w-6" src="{{ url('public/dist/images/logo.svg') }}">
        <span class="hidden xl:block text-white text-lg ml-3"><span class="font-medium">BDS</span> General</span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="{{ url('/') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>
        <li>
            <a href="{{ url('/profile') }}" class="side-menu">
                <div class="side-menu__icon"> <i data-feather="trello"></i> </div>
                <div class="side-menu__title"> Profile </div>
            </a>
        </li>

        <?php
        // DB::enableQueryLog();
        $pages = DB::table('model_has_roles as mr')
            ->join('role_has_pages as rp','rp.role_id','=','mr.role_id')
            ->join('pages as p','p.id','=','rp.page_id')
            ->where('mr.model_id', '=', Auth::user()->id)
            ->where('visible','=','1')
            ->whereNull('parent_id')
            ->orderBy('index', 'asc')
            ->get();
        // dd(DB::getQueryLog());

        foreach ($pages as $page) {
            $sub_pages = DB::table('model_has_roles as mr')
                ->join('role_has_pages as rp','rp.role_id','=','mr.role_id')
                ->join('pages as p','p.id','=','rp.page_id')
                ->where('mr.model_id', '=', Auth::user()->id)
                ->where('visible','=','1')
                ->where('parent_id','=',$page->id)
                ->orderBy('index', 'asc')
                ->get();

        ?>
            <li>
                <a href="{{ ($page->link =='#') ? 'javascript:;' : url($page->link) }}" class="side-menu">
                    <div class="side-menu__icon"> <i data-feather="{{ $page->page_icon }}"></i> </div>
                    <div class="side-menu__title"> {{ $page->page_name }}
                        @if (!$sub_pages->isEmpty()) 
                            <div class="side-menu__sub-icon "> <i data-feather="chevron-down"></i> </div>
                        @endif
                    </div>
                </a>
                @if (!empty($sub_pages))
                <ul class="">
                    @foreach ($sub_pages as $sub_page)
                        <li>
                            <a href="{{ url($sub_page->link) }}" class="side-menu">
                                <div class="side-menu__icon"> <i data-feather="{{ $sub_page->page_icon }}"></i> </div>
                                <div class="side-menu__title"> {{ $sub_page->page_name }} </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @endif
            </li>
        <?php
        }
        // dd(DB::getQueryLog());
        ?>
    </ul>
</nav>
<!-- END: Side Menu -->