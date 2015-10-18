<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="container-fluid">
            <div class="navbar-header">

                <ol class="breadcrumb ilfate-breadcrumb">
                    @foreach (\Ilfate\Helper\Breadcrumbs::getLinks() as $link)
                        <li {{ $link['active'] ? 'class="active"' : '' }} >
                            @if ($link['active'])
                                <a href="{{{ $link['url'] }}}">
                            @endif
                            {{{ $link['name'] }}}
                            @if ($link['active'])
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>

        </div>
    </div>
</nav>

