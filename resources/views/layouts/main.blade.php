

<div class="main-wrapper col p-0 min-vh-100">

    <!-- Modal -->
    <div class="modal modal-left fade sidebar" id="sidebar-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog bg-dark-blue" role="document">
            <div class="modal-content">

                <div class="modal-body bg-dark-blue">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <div class="logo text-center mt-2">
                        <a href="/">
                            SendPortal
                        </a>
                    </div>
                    
                    <ul class="mt-4">


                    </ul>
                </div>
            </div>
        </div>
    </div>


    @include('layouts.partials.header')

    <div class="main-content pl-4-half pr-4-half pb-4-half">

        <h1>@yield('heading')</h1>


        @if( ! in_array(request()->route()->getName(), [
            'login',
            'register',
            'password.reset',
        ]))
            @include('layouts.partials.errors')
        @endif

        @include('layouts.partials.success')
        @include('layouts.partials.error')

        @include('layouts.partials.actions')

        @yield('content')
    </div>

</div>