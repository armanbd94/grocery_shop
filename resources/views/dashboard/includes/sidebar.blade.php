<!-- BEGIN: Left Aside -->
<button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
    <i class="la la-close"></i>
</button>
<div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
    <!-- BEGIN: Aside Menu -->
    <div id="m_ver_menu" class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark " m-menu-vertical="1" m-menu-scrollable="0" m-menu-dropdown-timeout="500">
        <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow ">
           

        @foreach(Helper::get_menu() as $menu)
        <li class="m-menu__item <?php if(!empty($menu['sub_menus'])){ ?> m-menu__item--submenu <?php } ?>  {{$menu['active']}}
        @if(!empty($menu['sub_menus']))
            @foreach($menu['sub_menus'] as $sub_menu)
                {{$sub_menu['active']}}
            @endforeach
        @endif
        
        " <?php if(!empty($menu['sub_menus'])){ ?>  aria-haspopup="true"  m-menu-submenu-toggle="hover" <?php }?> >
                <a  
                @if($menu['menu_url'] != '') 
                href="{{url('').$menu['menu_url']}}"                    
                @else
                href="javascript:;"
                @endif
                class="m-menu__link <?php if(!empty($menu['sub_menus'])){ ?> m-menu__toggle <?php } ?>">
                    <i class="{{$menu['menu_icon']}}"></i>
                    <span class="m-menu__link-text">
                        {{$menu['menu_name']}}
                    </span>
                    @if(!empty($menu['sub_menus'])) 
                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                    @endif
                </a>
                @if(!empty($menu['sub_menus']))
                <div class="m-menu__submenu ">
                    <span class="m-menu__arrow"></span>
                    <ul class="m-menu__subnav">
                        @foreach($menu['sub_menus'] as $sub_menu)
                        <li class="m-menu__item" aria-haspopup="true" >
                            <a  href="{{url('').$sub_menu['submenu_url']}}" class="m-menu__link ">
                                <i class="{{$sub_menu['sub_menu_icon']}}">
                                    <span></span>
                                </i>
                                <span class="m-menu__link-text">
                                    {{$sub_menu['sub_menu_name']}}
                                </span>
                            </a>
                        </li>
                        @endforeach     
                    </ul>
                </div>
                @endif
            </li>
@endforeach

        </ul>
    </div>
    <!-- END: Aside Menu -->
</div>
<!-- END: Left Aside -->