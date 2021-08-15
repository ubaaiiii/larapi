<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    <div class="mobile-menu-bar">
        <a href="" class="flex mr-auto">
            <img alt="Rubick Tailwind HTML Admin Template" class="w-6" src="public/dist/images/logo.svg">
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

        <?php
        $pages = DB::table('pages')
            ->where('user_level', '=', 'adm')
            ->get();
        ?>
        @foreach ($pages as $page)
            <li>
                <a href="{{ url($page->link) }}" class="menu">
                    <div class="menu__icon"> <i data-feather="{{ $page->page_icon }}"></i> </div>
                    <div class="menu__title"> {{ $page->page_name }} </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
<!-- END: Mobile Menu -->
