<!-- сайдбар уже не используется, меню переехало в хедер, можно удалить (21.12.2017) -->

<div class="sidebar" data-background-color="brown" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | brown"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->

        <div class="logo">
            <a href="{{route('home')}}" class="simple-text">
                Nau
            </a>
        </div>
        <div class="logo logo-mini">
            <a href="{{route('home')}}" class="simple-text">
                N
            </a>
        </div>
        <div class="sidebar-wrapper">
			<ul class="nav" >
                <li class="active">
                    <a href="{{route('home')}}">
                        <i class="ti-home"></i>
                        <p class="menu-txt">Home</p>
                    </a>
                </li>
                <li>
                    <a href="{{route('referrals')}}">
                        <i class="ti-link"></i>
                        <p class="menu-txt">Referrals</p>
                    </a>
                </li>
                <li>
                    <a href="{{route('profile.place.show')}}">
                        <i class="ti-location-pin"></i>
                        <p class="menu-txt">Place show</p>
                    </a>
                </li>
                <li>
                    <a href="{{route('profile.place.offers')}}">
                        <i class="ti-zoom-in"></i>
                        <p class="menu-txt">Place offers</p>
                    </a>
                </li>
				<li>
					<a data-toggle="collapse" href="#offers" class="collapsed" aria-expanded="false">
                        <i class="ti-archive"></i>
						<p>Offers
							<b class="caret"></b>
						</p>
					</a>
					<div class="collapse" id="offers" role="navigation" aria-expanded="false" style="height: 0px;">
						<ul class="nav">
							<li>
								<a href="{{route('advert.offers.create')}}">Advert create</a>
							</li>
							<li>
								<a href="{{route('advert.offers.index')}}">Advert index</a>
							</li>
                            <li>
								<a href="{{route('offers.index')}}">Index</a>
							</li>
                            <li>
                                <a href="{{ route('advert.operators.create') }}">Operators create</a>
                            </li>
                            <li>
                                <a href="{{ route('advert.operators.index') }}">Operators list</a>
                            </li>
						</ul>
					</div>
				</li>
				<li>
					<a data-toggle="collapse" href="#operations" class="collapsed" aria-expanded="false">
                        <i class="ti-money"></i>
						<p class="menu-txt">Operations
							<b class="caret"></b>
						</p>
					</a>
					<div class="collapse" id="operations" role="navigation" aria-expanded="false" style="height: 0px;">
						<ul class="nav">
                            <li>
                                <a href="{{ route('transaction.list') }}">Transactions list</a>
                            </li>
                            <li>
                                <a href="{{ route('transaction.create') }}">Create transaction</a>
                            </li>
						</ul>
					</div>
				</li>
				<li>
					<a data-toggle="collapse" href="#places" class="collapsed" aria-expanded="false">
                        <i class="ti-world"></i>
						<p class="menu-txt">Places
							<b class="caret"></b>
						</p>
					</a>
					<div class="collapse" id="places" role="navigation" aria-expanded="false" style="height: 0px;">
						<ul class="nav">
							<li><a href="{{ route('places.create') }}">Create</a></li>
						</ul>
					</div>
				</li>
                @can('users.list')
                <li>
                    <a data-toggle="collapse" href="#admin" class="collapsed" aria-expanded="false">
                        <i class="ti-crown"></i>
						<p class="menu-txt">Admin
							<b class="caret"></b>
						</p>
					</a>
                    <div class="collapse" id="admin" role="navigation" aria-expanded="false" style="height: 0px;">
						<ul class="nav">
                            <li>
                                <a href="{{ route('users.index') }}">Users</a>
                            </li>
						</ul>
					</div>
                </li>
                @endcan
				<li>
			</ul>
        </div>
</div>
