{{-- <div :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed z-20 inset-0 bg-black opacity-50 transition-opacity lg:hidden allback"></div> --}}

<link href="{{ asset('assets/css/Navigation.css') }}" rel="stylesheet">

<div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
    class="fixed z-30 inset-y-0 left-0 w-64 bg-black transition duration-300 transform  overflow-y-auto lg:translate-x-0 lg:static lg:inset-0">
    <div class="flex items-center justify-center ">
        <div class="flex items-center">
            <!-- Use a condition to hide the image on the Store-list page -->
            <img v-if="" src="{{ asset('assets/imgs/logo_admin_removebg.png') }}" alt="Turkey Creek"
                class="text-white text-2xl mx-2 font-semibold" style="height: 100%; width: 90%">
        </div>
    </div>

    <nav class="mt-5 " x-data="{ isMultiLevelMenuOpen: false }">
        <x-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" style="text-decoration: none" class="Navigation">
            <x-slot name="icon">
                <svg width="25px" height="25px" viewBox="0 -0.5 25 25" fill="#ffffff"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12.5 11.75C12.9142 11.75 13.25 11.4142 13.25 11C13.25 10.5858 12.9142 10.25 12.5 10.25V11.75ZM5.5 10.25C5.08579 10.25 4.75 10.5858 4.75 11C4.75 11.4142 5.08579 11.75 5.5 11.75V10.25ZM12.5 10.25C12.0858 10.25 11.75 10.5858 11.75 11C11.75 11.4142 12.0858 11.75 12.5 11.75V10.25ZM19.5 11.75C19.9142 11.75 20.25 11.4142 20.25 11C20.25 10.5858 19.9142 10.25 19.5 10.25V11.75ZM11.75 11C11.75 11.4142 12.0858 11.75 12.5 11.75C12.9142 11.75 13.25 11.4142 13.25 11H11.75ZM13.25 5C13.25 4.58579 12.9142 4.25 12.5 4.25C12.0858 4.25 11.75 4.58579 11.75 5H13.25ZM6.25 11C6.25 10.5858 5.91421 10.25 5.5 10.25C5.08579 10.25 4.75 10.5858 4.75 11H6.25ZM20.25 11C20.25 10.5858 19.9142 10.25 19.5 10.25C19.0858 10.25 18.75 10.5858 18.75 11H20.25ZM4.75 11C4.75 11.4142 5.08579 11.75 5.5 11.75C5.91421 11.75 6.25 11.4142 6.25 11H4.75ZM12.5 5.75C12.9142 5.75 13.25 5.41421 13.25 5C13.25 4.58579 12.9142 4.25 12.5 4.25V5.75ZM18.75 11C18.75 11.4142 19.0858 11.75 19.5 11.75C19.9142 11.75 20.25 11.4142 20.25 11H18.75ZM12.5 4.25C12.0858 4.25 11.75 4.58579 11.75 5C11.75 5.41421 12.0858 5.75 12.5 5.75V4.25ZM12.5 10.25H5.5V11.75H12.5V10.25ZM12.5 11.75H19.5V10.25H12.5V11.75ZM13.25 11V5H11.75V11H13.25ZM4.75 11V15H6.25V11H4.75ZM4.75 15C4.75 17.6234 6.87665 19.75 9.5 19.75V18.25C7.70507 18.25 6.25 16.7949 6.25 15H4.75ZM9.5 19.75H15.5V18.25H9.5V19.75ZM15.5 19.75C18.1234 19.75 20.25 17.6234 20.25 15H18.75C18.75 16.7949 17.2949 18.25 15.5 18.25V19.75ZM20.25 15V11H18.75V15H20.25ZM6.25 11V9H4.75V11H6.25ZM6.25 9C6.25 7.20507 7.70507 5.75 9.5 5.75V4.25C6.87665 4.25 4.75 6.37665 4.75 9H6.25ZM9.5 5.75H12.5V4.25H9.5V5.75ZM20.25 11V9H18.75V11H20.25ZM20.25 9C20.25 6.37665 18.1234 4.25 15.5 4.25V5.75C17.2949 5.75 18.75 7.20507 18.75 9H20.25ZM15.5 4.25H12.5V5.75H15.5V4.25Z"
                        fill="#ffff" />
                </svg>
            </x-slot>
            {{ __('Dashboard') }}
        </x-nav-link>

        <x-nav-link href="{{ route('category') }}" :active="request()->routeIs('category')" style="text-decoration: none" class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="#fffs"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M2 6.47762C2 5.1008 2 4.4124 2.22168 3.86821C2.52645 3.12007 3.12007 2.52645 3.86821 2.22168C4.4124 2 5.1008 2 6.47762 2V2C7.85443 2 8.54284 2 9.08702 2.22168C9.83517 2.52645 10.4288 3.12007 10.7336 3.86821C10.9552 4.4124 10.9552 5.1008 10.9552 6.47762V6.47762C10.9552 7.85443 10.9552 8.54284 10.7336 9.08702C10.4288 9.83517 9.83517 10.4288 9.08702 10.7336C8.54284 10.9552 7.85443 10.9552 6.47762 10.9552V10.9552C5.1008 10.9552 4.4124 10.9552 3.86821 10.7336C3.12007 10.4288 2.52645 9.83517 2.22168 9.08702C2 8.54284 2 7.85443 2 6.47762V6.47762Z"
                        fill="#fff" />
                    <path
                        d="M2 17.5224C2 16.1456 2 15.4572 2.22168 14.913C2.52645 14.1649 3.12007 13.5712 3.86821 13.2665C4.4124 13.0448 5.1008 13.0448 6.47762 13.0448V13.0448C7.85443 13.0448 8.54284 13.0448 9.08702 13.2665C9.83517 13.5712 10.4288 14.1649 10.7336 14.913C10.9552 15.4572 10.9552 16.1456 10.9552 17.5224V17.5224C10.9552 18.8992 10.9552 19.5876 10.7336 20.1318C10.4288 20.88 9.83517 21.4736 9.08702 21.7783C8.54284 22 7.85443 22 6.47762 22V22C5.1008 22 4.4124 22 3.86821 21.7783C3.12007 21.4736 2.52645 20.88 2.22168 20.1318C2 19.5876 2 18.8992 2 17.5224V17.5224Z"
                        fill="#ffff" />
                    <path
                        d="M13.0449 17.5224C13.0449 16.1456 13.0449 15.4572 13.2666 14.913C13.5714 14.1649 14.165 13.5712 14.9131 13.2665C15.4573 13.0448 16.1457 13.0448 17.5225 13.0448V13.0448C18.8994 13.0448 19.5878 13.0448 20.1319 13.2665C20.8801 13.5712 21.4737 14.1649 21.7785 14.913C22.0002 15.4572 22.0002 16.1456 22.0002 17.5224V17.5224C22.0002 18.8992 22.0002 19.5876 21.7785 20.1318C21.4737 20.88 20.8801 21.4736 20.1319 21.7783C19.5878 22 18.8994 22 17.5225 22V22C16.1457 22 15.4573 22 14.9131 21.7783C14.165 21.4736 13.5714 20.88 13.2666 20.1318C13.0449 19.5876 13.0449 18.8992 13.0449 17.5224V17.5224Z"
                        fill="#ffff" />
                    <path clip-rule="evenodd"
                        d="M16.7725 9.47766C16.7725 9.89187 17.1082 10.2277 17.5225 10.2277C17.9367 10.2277 18.2725 9.89187 18.2725 9.47766V7.22766H20.5225C20.9367 7.22766 21.2725 6.89187 21.2725 6.47766C21.2725 6.06345 20.9367 5.72766 20.5225 5.72766H18.2725V3.47766C18.2725 3.06345 17.9367 2.72766 17.5225 2.72766C17.1082 2.72766 16.7725 3.06345 16.7725 3.47766L16.7725 5.72766H14.5225C14.1082 5.72766 13.7725 6.06345 13.7725 6.47766C13.7725 6.89187 14.1082 7.22766 14.5225 7.22766H16.7725L16.7725 9.47766Z"
                        fill="#fff" fill-rule="evenodd" />
                </svg>
            </x-slot>
            {{ __('Categories') }}
        </x-nav-link>

        <x-nav-link href="{{ route('store-list') }}" :active="request()->routeIs('store-list')" style="text-decoration: none" class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="#fff"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.87617 3.75H19.1238L21 8.86683V10.5C21 11.2516 20.7177 11.9465 20.25 12.4667V21H3.75V12.4667C3.28234 11.9465 3 11.2516 3 10.5V8.86683L4.87617 3.75ZM18.1875 13.3929C18.3807 13.3929 18.5688 13.3731 18.75 13.3355V19.5H15V15H9L9 19.5H5.25V13.3355C5.43122 13.3731 5.61926 13.3929 5.8125 13.3929C6.63629 13.3929 7.36559 13.0334 7.875 12.4667C8.38441 13.0334 9.11371 13.3929 9.9375 13.3929C10.7613 13.3929 11.4906 13.0334 12 12.4667C12.5094 13.0334 13.2387 13.3929 14.0625 13.3929C14.8863 13.3929 15.6156 13.0334 16.125 12.4667C16.6344 13.0334 17.3637 13.3929 18.1875 13.3929ZM10.5 19.5H13.5V16.5H10.5L10.5 19.5ZM19.5 9.75V10.5C19.5 11.2965 18.8856 11.8929 18.1875 11.8929C17.4894 11.8929 16.875 11.2965 16.875 10.5V9.75H19.5ZM19.1762 8.25L18.0762 5.25H5.92383L4.82383 8.25H19.1762ZM4.5 9.75V10.5C4.5 11.2965 5.11439 11.8929 5.8125 11.8929C6.51061 11.8929 7.125 11.2965 7.125 10.5V9.75H4.5ZM8.625 9.75V10.5C8.625 11.2965 9.23939 11.8929 9.9375 11.8929C10.6356 11.8929 11.25 11.2965 11.25 10.5V9.75H8.625ZM12.75 9.75V10.5C12.75 11.2965 13.3644 11.8929 14.0625 11.8929C14.7606 11.8929 15.375 11.2965 15.375 10.5V9.75H12.75Z"
                        fill="#ffff" />
                </svg>
            </x-slot>
            {{ __('Stores') }}
        </x-nav-link>

        <x-nav-link href="{{ route('coupon.index') }}" :active="request()->routeIs('coupon.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="#fff"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.75 6.75L4.5 6H20.25L21 6.75V10.7812H20.25C19.5769 10.7812 19.0312 11.3269 19.0312 12C19.0312 12.6731 19.5769 13.2188 20.25 13.2188H21V17.25L20.25 18L4.5 18L3.75 17.25V13.2188H4.5C5.1731 13.2188 5.71875 12.6731 5.71875 12C5.71875 11.3269 5.1731 10.7812 4.5 10.7812H3.75V6.75ZM5.25 7.5V9.38602C6.38677 9.71157 7.21875 10.7586 7.21875 12C7.21875 13.2414 6.38677 14.2884 5.25 14.614V16.5L9 16.5L9 7.5H5.25ZM10.5 7.5V16.5L19.5 16.5V14.614C18.3632 14.2884 17.5312 13.2414 17.5312 12C17.5312 10.7586 18.3632 9.71157 19.5 9.38602V7.5H10.5Z"
                        fill="#ffff" />
                </svg>
            </x-slot>
            {{ __('Coupons') }}
        </x-nav-link>

        <x-nav-link href="{{ route('vip_membership.index') }}" :active="request()->routeIs('vip_membership.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" viewBox="0 0 24 24" fill="#fff"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.75 6.75L4.5 6H20.25L21 6.75V10.7812H20.25C19.5769 10.7812 19.0312 11.3269 19.0312 12C19.0312 12.6731 19.5769 13.2188 20.25 13.2188H21V17.25L20.25 18L4.5 18L3.75 17.25V13.2188H4.5C5.1731 13.2188 5.71875 12.6731 5.71875 12C5.71875 11.3269 5.1731 10.7812 4.5 10.7812H3.75V6.75ZM5.25 7.5V9.38602C6.38677 9.71157 7.21875 10.7586 7.21875 12C7.21875 13.2414 6.38677 14.2884 5.25 14.614V16.5L9 16.5L9 7.5H5.25ZM10.5 7.5V16.5L19.5 16.5V14.614C18.3632 14.2884 17.5312 13.2414 17.5312 12C17.5312 10.7586 18.3632 9.71157 19.5 9.38602V7.5H10.5Z"
                        fill="#ffff" />
                </svg>
            </x-slot>
            {{ __('Vip Membership') }}
        </x-nav-link>

        <x-nav-link href="{{ route('event.index') }}" :active="request()->routeIs('event.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" fill="#ffff" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
                    xml:space="preserve">
                    <style type="text/css">
                        .st0 {
                            fill: none;
                        }
                    </style>
                    <path
                        d="M6,2v2H5C3.9,4,3,4.9,3,6v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V6c0-1.1-0.9-2-2-2h-1V2h-2v2H8V2H6z M5,9h14v11H5V9z" />
                    <path
                        d="M15.9,18.3l-2.4-1.5L11,18.3l0.6-2.7l-2.1-1.8l2.8-0.2l1.1-2.6l1.1,2.6l2.8,0.3l-2.1,1.8L15.9,18.3z" />
                    <rect class="st0" width="24" height="24" />
                </svg>
            </x-slot>
            {{ __('Events') }}
        </x-nav-link>


        <x-nav-link href="{{ route('product.index') }}" :active="request()->routeIs('event.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" fill="#ffff" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
                    xml:space="preserve">
                    <path
                        d="M21,4h-2.17l-1.47-1.47C17.05,2.16,16.55,2,16,2H8C7.45,2,6.95,2.16,6.64,2.47L5.17,3.94L3.71,2.47C3.4,2.16,2.9,2,2.35,2H2 C1.51,2,1.13,2.38,1.13,2.88C1.13,3.37,1.51,3.75,2,3.75h0.72L4.28,5.2C4.11,5.47,4,5.77,4,6.12V20c0,1.1,0.9,2,2,2h12 c0.54,0,1.05-0.21,1.42-0.58l0,0l4.33-4.33c0.39-0.39,0.58-0.9,0.58-1.42V6C22,4.9,21.1,4,21,4z M6.5,17c-1.38,0-2.5-1.12-2.5-2.5 S5.12,12,6.5,12S9,13.12,9,14.5S7.88,17,6.5,17z M14,14.5c0,1.38-1.12,2.5-2.5,2.5S9,15.88,9,14.5S10.12,12,11.5,12 S14,13.12,14,14.5z M19.85,14.44l-3.57,3.57L8.5,9.29L5.15,5.93H6v2h12V5.15l3.57,3.57L19.85,14.44z" />
                    <rect width="24" height="24" fill="none" />
                </svg>
            </x-slot>
            {{ __('Products') }}
        </x-nav-link>

        <x-nav-link href="{{ route('order.index') }}" :active="request()->routeIs('order.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" fill="#ffff" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
                    xml:space="preserve">
                    <path d="M3,3v5h18V3H3z M21,7H3V5h18V7z M3,12h18v-2H3V12z M3,17h18v-2H3V17z M3,21h18v-2H3V21z" />
                    <rect width="24" height="24" fill="none" />
                </svg>
            </x-slot>
            {{ __('Orders') }}
        </x-nav-link>

        <x-nav-link href="{{ route('user.index') }}" :active="request()->routeIs('user.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg width="22px" height="22px" fill="#ffff" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
                    xml:space="preserve">
                    <path
                        d="M12,11c2.21,0,4-1.79,4-4s-1.79-4-4-4s-4,1.79-4,4S9.79,11,12,11z M12,13c-2.67,0-8,1.34-8,4v2h16v-2 C20,14.34,14.67,13,12,13z" />
                    <rect width="24" height="24" fill="none" />
                </svg>
            </x-slot>
            {{ __('Users') }}
        </x-nav-link>

        <x-nav-link href="{{ route('pushNotification.index') }}" :active="request()->routeIs('user.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22px" height="22px" fill="#ffff">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path d="M11 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm5.5-6c0-2.29-1.62-4.21-3.75-4.72V13c0 .55-.45 1-1 1H8c-.55 0-1-.45-1-1v-1.72c-2.13.51-3.75 2.43-3.75 4.72v3.78l-2 2v1h16v-1l-2-2v-3.78zM12 2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6.3 5.3l-1.27-1.27C16.55 5.16 14.82 4 13 4s-3.55 1.16-4.02 2.73L7.7 7.3c-.47.47-1.08.7-1.7.7s-1.23-.23-1.7-.7L3.7 6.03C2.26 7.47 1.5 9.31 1.5 11v5.77l-.94.94c-.19.2-.31.46-.31.76s.12.56.31.76c.2.19.45.3.74.3h18c.29 0 .54-.11.74-.3.19-.2.31-.46.31-.76s-.12-.56-.31-.76l-.94-.94V11c0-1.69-.76-3.53-2.2-4.97zM15.5 20h-7l1.49 1.49c.19.2.45.3.74.3s.56-.1.76-.29c.2-.2.3-.46.3-.75V18h2v1c0 .55.45 1 1 1s1-.45 1-1v-1h2v1.25c0 .28.11.54.3.74.2.19.46.29.75.29.29 0 .55-.1.74-.3.2-.2.3-.46.3-.75L15.5 20z"/>
                </svg>
            </x-slot>
            {{ __('Push Notification') }}
        </x-nav-link>

        <x-nav-link href="{{ route('pushNotification.list') }}" :active="request()->routeIs('user.index')" style="text-decoration: none"
            class="Navigation">
            <x-slot name="icon">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22px" height="22px" fill="#ffff">
                    <path d="M0 0h24v24H0z" fill="none"/>
                    <path d="M11 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm5.5-6c0-2.29-1.62-4.21-3.75-4.72V13c0 .55-.45 1-1 1H8c-.55 0-1-.45-1-1v-1.72c-2.13.51-3.75 2.43-3.75 4.72v3.78l-2 2v1h16v-1l-2-2v-3.78zM12 2c1.1 0 2 .9 2 2h-4c0-1.1.9-2 2-2zm6.3 5.3l-1.27-1.27C16.55 5.16 14.82 4 13 4s-3.55 1.16-4.02 2.73L7.7 7.3c-.47.47-1.08.7-1.7.7s-1.23-.23-1.7-.7L3.7 6.03C2.26 7.47 1.5 9.31 1.5 11v5.77l-.94.94c-.19.2-.31.46-.31.76s.12.56.31.76c.2.19.45.3.74.3h18c.29 0 .54-.11.74-.3.19-.2.31-.46.31-.76s-.12-.56-.31-.76l-.94-.94V11c0-1.69-.76-3.53-2.2-4.97zM15.5 20h-7l1.49 1.49c.19.2.45.3.74.3s.56-.1.76-.29c.2-.2.3-.46.3-.75V18h2v1c0 .55.45 1 1 1s1-.45 1-1v-1h2v1.25c0 .28.11.54.3.74.2.19.46.29.75.29.29 0 .55-.1.74-.3.2-.2.3-.46.3-.75L15.5 20z"/>
                </svg>
            </x-slot>
            {{ __('Push Notification List') }}
        </x-nav-link>
        {{-- <x-nav-link class="mt-0"> --}}
        <form method="POST" action="{{ route('logout') }}" class="text-white Navigation logout">
            @csrf
            <div class="row">
                <div class="col-md-2"> <svg width="22px" height="22px" viewBox="0 0 24 24" fill="#fff"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.00195 7C8.01406 4.82497 8.11051 3.64706 8.87889 2.87868C9.75757 2 11.1718 2 14.0002 2H15.0002C17.8286 2 19.2429 2 20.1215 2.87868C21.0002 3.75736 21.0002 5.17157 21.0002 8V16C21.0002 18.8284 21.0002 20.2426 20.1215 21.1213C19.2429 22 17.8286 22 15.0002 22H14.0002C11.1718 22 9.75757 22 8.87889 21.1213C8.11051 20.3529 8.01406 19.175 8.00195 17"
                            stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                        <path
                            d="M8 19.5C5.64298 19.5 4.46447 19.5 3.73223 18.7678C3 18.0355 3 16.857 3 14.5V9.5C3 7.14298 3 5.96447 3.73223 5.23223C4.46447 4.5 5.64298 4.5 8 4.5"
                            stroke="#1C274C" stroke-width="1.5" />
                        <path d="M15 12L6 12M6 12L8 14M6 12L8 10" stroke="#1C274C" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg> </div>
                <div class="col-md-8">
                    <a class="" href="{{ route('logout') }}" style="text-decoration: none"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Logout') }}
                    </a>
                </div>
            </div>
        </form>
        {{-- </x-nav-link> --}}
    </nav>
</div>
