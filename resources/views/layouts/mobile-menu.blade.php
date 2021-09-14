<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="{{ url('') }}" class="flex mr-auto">
            <img alt="Rubick Tailwind HTML Admin Template" class="w-6" src="{{ url('public/dist/images/logo.svg') }}">
        </a>
        <a href="javascript:;" id="mobile-menu-toggler"> <i data-feather="bar-chart-2"
                class="w-8 h-8 text-white transform -rotate-90"></i> </a>
    </div>
    <ul class="border-t border-theme-29 py-5 hidden">
        <li>
            <a href="{{ url('/') }}" class="menu">
                <div class="menu__icon"> <i data-feather="home"></i> </div>
                <div class="menu__title"> Dashboard </div>
            </a>
        </li>
        <li>
            <a href="{{ url('profile') }}" class="menu">
                <div class="menu__icon"> <i data-feather="trello"></i> </div>
                <div class="menu__title"> Profile </div>
            </a>
        </li>
        @php
        $pages = DB::table('model_has_roles as mr')
            ->join('role_has_pages as rp','rp.role_id','=','mr.role_id')
            ->join('pages as p','p.id','=','rp.page_id')
            ->where('mr.model_id', '=', Auth::user()->id)
            ->where('visible','=','1')
            ->whereNull('parent_id')
            ->orderBy('index', 'asc')
            ->get();

        foreach ($pages as $page) {
            $sub_pages = DB::table('model_has_roles as mr')
                ->join('role_has_pages as rp','rp.role_id','=','mr.role_id')
                ->join('pages as p','p.id','=','rp.page_id')
                ->where('mr.model_id', '=', Auth::user()->id)
                ->where('visible','=','1')
                ->where('parent_id','=',$page->id)
                ->orderBy('index', 'asc')
                ->get();
        @endphp
            <li>
                <a href="{{ ($page->link =='#') ? 'javascript:;' : url($page->link) }}" class="menu">
                    <div class="menu__icon"> <i data-feather="{{ $page->page_icon }}"></i> </div>
                    <div class="menu__title"> {{ $page->page_name }}
                        @if (!$sub_pages->isEmpty()) 
                            <i data-feather="chevron-down" class="menu__sub-icon "></i>
                        @endif</div>
                </a>
                @if (!empty($sub_pages))
                    <ul class="">
                        @foreach ($sub_pages as $sub_page)
                            <li>
                                <a href="{{ url($sub_page->link) }}" class="menu">
                                    <div class="menu__icon"> <i data-feather="{{ $sub_page->page_icon }}"></i> </div>
                                    <div class="menu__title"> {{ $sub_page->page_name }} </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @php 
        }
        @endphp

    </ul>
</div>
<!-- END: Mobile Menu -->
