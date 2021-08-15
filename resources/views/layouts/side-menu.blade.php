<!-- BEGIN: Side Menu -->
<nav class="side-nav">
    <a href="" class="intro-x flex items-center pl-5 pt-4">
        <img alt="Rubick Tailwind HTML Admin Template" class="w-6" src="public/dist/images/logo.svg">
        <span class="hidden xl:block text-white text-lg ml-3"><span class="font-medium">BDS</span> General</span>
    </a>
    <div class="side-nav__devider my-6"></div>
    <ul>
        <li>
            <a href="{{ url('/') }}/" class="side-menu">
                <div class="side-menu__icon"> <i data-feather="home"></i> </div>
                <div class="side-menu__title"> Dashboard </div>
            </a>
        </li>
        <li>
            <a href="{{ url('/profile') }}/" class="side-menu">
                <div class="side-menu__icon"> <i data-feather="trello"></i> </div>
                <div class="side-menu__title"> Profile </div>
            </a>
        </li>

        <?php
        $pages = DB::table('pages')
            ->where('user_level', '=', 'adm')
            ->get();
        ?>
        @foreach ($pages as $page)
            <li>
                <a href="{{ url('/') . $page->link }}/" class="side-menu">
                    <div class="side-menu__icon"> <i data-feather="{{ $page->page_icon }}"></i> </div>
                    <div class="side-menu__title"> {{ $page->page_name }} </div>
                </a>
            </li>
        @endforeach


    </ul>
</nav>
<!-- END: Side Menu -->
