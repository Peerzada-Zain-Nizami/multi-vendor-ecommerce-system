@php
$profile = null;
if (!empty(Auth::user()->profile_img))
{
	$profile = asset ('uploads/profiles/'.Auth::user()->profile_img);
}
else{
	$profile = asset ('assets/user_avator.png');
}
$layout = Auth::user()->theme;
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="Azea – Laravel Admin & Dashboard Template" name="description">
		<meta content="Spruko Private Limited" name="author">
		<meta name="keywords" content="laravel ui admin template, laravel admin template, laravel dashboard template,laravel ui template, laravel ui, livewire, laravel, laravel admin panel, laravel admin panel template, laravel blade, laravel bootstrap5, bootstrap admin template, admin, dashboard, admin template">
	<meta name="csrf-token" content="{{ csrf_token() }}">
		<!-- Title -->
		<title>Subadmin | Dashboard</title>

        <!--Favicon -->
		<link rel="icon" href="{{asset ('assets/images/brand/favicon.ico ')}}" type="image/x-icon"/>

		<!--Bootstrap css -->
		<link href="{{asset ('assets/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">

		<!-- Style css -->
		<link href="{{asset ('assets/css/style.css')}}" rel="stylesheet" />
		<link href="{{asset ('assets/css/dark.css')}}" rel="stylesheet" />
		<link href="{{asset ('assets/css/skin-modes.css')}}" rel="stylesheet" />

		<!-- Animate css -->
		<link href="{{asset ('assets/css/animated.css')}}" rel="stylesheet" />

		<!--Sidemenu css -->
       <link href="{{asset ('assets/css/sidemenu.css')}}" rel="stylesheet">

		<!-- P-scroll bar css-->
		<link href="{{asset ('assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />

		<!---Icons css-->
		<link href="{{asset ('assets/plugins/icons/icons.css')}}" rel="stylesheet" />


		<!-- Simplebar css -->
		<link rel="stylesheet" href="{{asset ('assets/plugins/simplebar/css/simplebar.css')}}">

		<!-- INTERNAL Morris Charts css -->
		<link href="{{asset ('assets/plugins/morris/morris.css')}}" rel="stylesheet" />

		<!-- INTERNAL File Uploads css -->
		<link href="{{asset('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />

		<!-- INTERNAL Select2 css -->
		<link href="{{asset ('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />

		<!-- INTERNAL File Uploads css -->
		<link href="{{asset ('assets/plugins/fancyuploder/fancy_fileupload.css')}}" rel="stylesheet" />

		<!-- Data table css -->
		<link href="{{asset ('assets/plugins/datatables/DataTables/css/dataTables.bootstrap5.css')}}" rel="stylesheet" />
		<link href="{{asset ('assets/plugins/datatables/Buttons/css/buttons.bootstrap5.min.css')}}"  rel="stylesheet">
		<link href="{{asset ('assets/plugins/datatables/Responsive/css/responsive.bootstrap5.min.css')}}" rel="stylesheet" />

	    <!-- Color Skin css -->
		<link id="theme" href="{{asset ('assets/colors/color1.css')}}" rel="stylesheet" type="text/css"/>

	    <!-- INTERNAL Switcher css -->
		<link href="{{asset ('assets/switcher/css/switcher.css')}}" rel="stylesheet"/>
		<link href="{{asset ('assets/switcher/demo.css')}}" rel="stylesheet"/>

		<!-- INTERNAl WYSIWYG Editor css -->
		<link href="{{asset('assets/plugins/wysiwyag/richtext.css')}}" rel="stylesheet" />

		<!-- INTERNAl Quill css -->
		<link href="{{asset('assets/plugins/quill/quill.snow.css')}}" rel="stylesheet">
		<link href="{{asset('assets/plugins/quill/quill.bubble.css')}}" rel="stylesheet">
	</head>

	<body class="app sidebar-mini {{$layout}}">

        <!---Global-loader-->
{{--        <div id="global-loader" >
            <img src="{{asset ('assets/images/svgs/loader.svg')}}" alt="loader">
        </div>--}}
        <!--- End Global-loader-->

		<!-- PAGE -->
		<div class="page">
			<div class="page-main">

            <!--aside open-->
				<aside class="app-sidebar">
					<div class="app-sidebar__logo">
						<a class="header-brand" href="{{route('subadmin.dashboard')}}">
							<img src="{{asset ('assets/images/brand/logo.png')}}" class="header-brand-img desktop-lgo" alt="Azea logo">
							<img src="{{asset ('assets/images/brand/logo1.png')}}" class="header-brand-img dark-logo" alt="Azea logo">
							<img src="{{asset ('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="Azea logo">
							<img src="{{asset ('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="Azea logo">
						</a>
					</div>
					<ul class="side-menu app-sidebar3">
						<li class="side-item side-item-category">Main</li>
						<li class="slide">
							<a class="side-menu__item"  href="{{route('subadmin.dashboard')}}">
								<svg xmlns="http://www.w3.org/2000/svg"  class="side-menu__icon" width="24" height="24" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4zm2-15.586 6 6V15l.001 5H16v-5c0-1.103-.897-2-2-2h-4c-1.103 0-2 .897-2 2v5H6v-9.586l6-6z"/></svg>
							<span class="side-menu__label">Dashboard</span></a>
						</li>
						<li class="side-item side-item-category">Components</li>

						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="fe fe-briefcase side-menu__icon"></i>
							<span class="side-menu__label">Wallet Management</span></a>
							<ul class="slide-menu">
								<li><a href="{{route('subadmin.wallet')}}" class="slide-item"> My Wallet</a></li>
							</ul>
							<ul class="slide-menu">
								<li><a href="{{route('subadmin.transhistory')}}" class="slide-item"> Transaction History</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
								<i class="fe fe-briefcase side-menu__icon"></i>
								<span class="side-menu__label">Settings</span></a>
							<ul class="slide-menu">
								<li><a href="{{route('subadmin.bank')}}" class="slide-item">Bank Details</a></li>
							</ul>
							<ul class="slide-menu">
								<li><a href="{{route('subadmin.paypal')}}" class="slide-item">Paypal Details</a></li>
							</ul>
							<ul class="slide-menu">
								<li><a href="{{route('subadmin.profile')}}" class="slide-item">My Profile</a></li>
							</ul>
						</li>
					</ul>
				</aside>
				<!--aside closed-->
            <!--app header-->
						<div class="app-header header main-header1">
							<div class="container-fluid">
								<div class="d-flex">
									<a class="header-brand" href="index.html">
										<img src="{{asset ('assets/images/brand/logo.png')}}" class="header-brand-img desktop-lgo" alt="Azea logo">
										<img src="{{asset ('assets/images/brand/logo1.png')}}" class="header-brand-img dark-logo" alt="Azea logo">
										<img src="{{asset ('assets/images/brand/favicon.png')}}" class="header-brand-img mobile-logo" alt="Azea logo">
										<img src="{{asset ('assets/images/brand/favicon1.png')}}" class="header-brand-img darkmobile-logo" alt="Azea logo">
									</a>
									<div class="app-sidebar__toggle d-flex" data-bs-toggle="sidebar">
										<a class="open-toggle" href="javascript:void(0);">
											<svg xmlns="http://www.w3.org/2000/svg" class="feather feather-align-left header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
										</a>
									</div>

									<div class="d-flex order-lg-2 ms-auto main-header-end">
										<button  class="navbar-toggler navresponsive-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="true" aria-label="Toggle navigation">
											<i class="fe fe-more-vertical header-icons navbar-toggler-icon"></i>
										</button>
										<div class="navbar navbar-expand-lg navbar-collapse responsive-navbar p-0">
											<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
												<div class="d-flex order-lg-2">
													<div class="dropdown d-flex">
														<a class="nav-link icon theme-layout nav-link-bg my-layout">
															<span class="light-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M20.742 13.045a8.088 8.088 0 0 1-2.077.271c-2.135 0-4.14-.83-5.646-2.336a8.025 8.025 0 0 1-2.064-7.723A1 1 0 0 0 9.73 2.034a10.014 10.014 0 0 0-4.489 2.582c-3.898 3.898-3.898 10.243 0 14.143a9.937 9.937 0 0 0 7.072 2.93 9.93 9.93 0 0 0 7.07-2.929 10.007 10.007 0 0 0 2.583-4.491 1.001 1.001 0 0 0-1.224-1.224zm-2.772 4.301a7.947 7.947 0 0 1-5.656 2.343 7.953 7.953 0 0 1-5.658-2.344c-3.118-3.119-3.118-8.195 0-11.314a7.923 7.923 0 0 1 2.06-1.483 10.027 10.027 0 0 0 2.89 7.848 9.972 9.972 0 0 0 7.848 2.891 8.036 8.036 0 0 1-1.484 2.059z"/></svg></span>
															<span class="dark-layout"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M6.993 12c0 2.761 2.246 5.007 5.007 5.007s5.007-2.246 5.007-5.007S14.761 6.993 12 6.993 6.993 9.239 6.993 12zM12 8.993c1.658 0 3.007 1.349 3.007 3.007S13.658 15.007 12 15.007 8.993 13.658 8.993 12 10.342 8.993 12 8.993zM10.998 19h2v3h-2zm0-17h2v3h-2zm-9 9h3v2h-3zm17 0h3v2h-3zM4.219 18.363l2.12-2.122 1.415 1.414-2.12 2.122zM16.24 6.344l2.122-2.122 1.414 1.414-2.122 2.122zM6.342 7.759 4.22 5.637l1.415-1.414 2.12 2.122zm13.434 10.605-1.414 1.414-2.122-2.122 1.414-1.414z"/></svg></span>
														</a>
													</div><!-- Theme-Layout -->
													<div class="dropdown  header-fullscreen d-flex" >
														<a  class="nav-link icon full-screen-link p-0"  id="fullscreen-button">
															<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"/></svg>
														</a>
													</div>
													<div class="dropdown country-selector  d-flex">
														<a href="javascript:void(0);" class="nav-link leading-none" data-bs-toggle="dropdown">
															<span class="header-avatar1">
																<img src="{{asset ('assets/images/us_flag.jpg')}}" alt="img" class="me-2 country">
																<span class="fs-14 font-weight-semibold"> English</span>
															</span>
														</a>
														<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
															<a class="dropdown-item d-flex" href="profile1.html">
																<img src="{{asset ('assets/images/germany_flag.jpg')}}" alt="img" class="me-2 country mt-1">
																<span class="fs-13"> Germany</span>
															</a>
															<a class="dropdown-item d-flex" href="search.html">
																<img src="{{asset ('assets/images/italy_flag.jpg')}}" alt="img" class="me-2 country mt-1">
																<span class="fs-13"> Italy</span>
															</a>
															<a class="dropdown-item d-flex" href="chat.html">
																<img src="{{asset ('assets/images/russia_flag.jpg')}}" alt="img" class="me-2 country mt-1">
																<span class="fs-13"> Russia</span>
															</a>
															<a class="dropdown-item d-flex" href="login1.html">
																<img src="{{asset ('assets/images/spain_flag.jpg')}}" alt="img" class=" me-2 country mt-1">
																<span class="fs-13"> Spain</span>
															</a>
														</div>
													</div>
																		<div class="dropdown header-notify d-flex">
														<a class="nav-link icon" data-bs-toggle="dropdown">
															<svg xmlns="http://www.w3.org/2000/svg" class="header-icon" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"/></svg><span class="pulse "></span>
														</a>
														<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow  animated">
															<div class="dropdown-header">
																<h6 class="mb-0">Notifications</h6>
																<span class="badge fs-10 bg-secondary br-7 ms-auto">New</span>
															</div>
															<div class="notify-menu">
																<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4">
																	<div class="notifyimg  text-primary bg-primary-transparent border-primary"> <i class="fa fa-envelope"></i> </div>
																	<div>
																		<span class="fs-13">Message Sent.</span>
																		<div class="small text-muted">3 hours ago</div>
																	</div>
																</a>
																<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4">
																	<div class="notifyimg  text-secondary bg-secondary-transparent border-secondary"> <i class="fa fa-shopping-cart"></i></div>
																	<div>
																		<span class="fs-13">Order Placed</span>
																		<div class="small text-muted">5  hour ago</div>
																	</div>
																</a>
																<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4">
																	<div class="notifyimg  text-danger bg-danger-transparent border-danger"> <i class="fa fa-gift"></i> </div>
																	<div>
																		<span class="fs-13">Event Started</span>
																		<div class="small text-muted">45 mintues ago</div>
																	</div>
																</a>
																<a href="email-inbox.html" class="dropdown-item border-bottom d-flex ps-4 mb-2">
																	<div class="notifyimg  text-success  bg-success-transparent border-success"> <i class="fa fa-windows"></i> </div>
																	<div>
																		<span class="fs-13">Your Admin lanuched</span>
																		<div class="small text-muted">1 daya ago</div>
																	</div>
																</a>
															</div>
															<div class=" text-center p-2">
																<a href="email-inbox.html" class="btn btn-primary btn-md fs-13 btn-block">View All</a>
															</div>
														</div>
													</div>

													<div class="dropdown profile-dropdown d-flex">
														<a href="javascript:void(0);" class="nav-link pe-0 leading-none" data-bs-toggle="dropdown">
															<span class="header-avatar1">
																<img src="{{$profile}}" alt="img" class="avatar avatar-md brround">
															</span>
														</a>
														<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow animated">
															<div class="text-center">
																<div class="text-center user pb-0 font-weight-bold">{{Auth::user()->name}}</div>
																<span class="text-center user-semi-title">{{Auth::user()->role}}</span>
																<div class="dropdown-divider"></div>
															</div>
															<a class="dropdown-item d-flex" href="{{route('subadmin.profile')}}">
																<svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"/></svg>
																<div class="fs-13">Profile</div>
															</a>
															<a class="dropdown-item d-flex" href="{{route('logout')}}"  onclick="event.preventDefault();
															document.getElementById('logout-form').submit();">
																<svg class="header-icon me-2" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect fill="none" height="24" width="24"/></g><g><path d="M11,7L9.6,8.4l2.6,2.6H2v2h10.2l-2.6,2.6L11,17l5-5L11,7z M20,19h-8v2h8c1.1,0,2-0.9,2-2V5c0-1.1-0.9-2-2-2h-8v2h8V19z"/></g></svg>
																<div class="fs-13">Sign Out</div>
															</a>
															<form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
																@csrf
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--/app header-->


                        @yield('content')
            </div>

            <!--Footer-->
			<footer class="footer">
				<div class="container">
					<div class="row align-items-center flex-row-reverse">
						<div class="col-md-12 col-sm-12 text-center">
							Copyright © 2021 <a href="#"></a> | All rights reserved
						</div>
					</div>
				</div>
			</footer>
			<!-- End Footer-->

		</div>
        <!-- Back to top -->
		<a href="#top" id="back-to-top"><i class="fe fe-chevron-up"></i></a>

		<!-- Jquery js-->
		<script src="{{asset ('assets/plugins/jquery/jquery.min.js ')}}"></script>
        {{--ajax--}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxy/1.6.1/scripts/jquery.ajaxy.min.js"></script>
        <!-- Bootstrap5 js-->
        <script src="{{asset ('assets/plugins/bootstrap/popper.min.js ')}}"></script>
        <script src="{{asset ('assets/plugins/bootstrap/js/bootstrap.min.js ')}}"></script>

		<!--Othercharts js-->
		<script src="{{asset ('assets/plugins/othercharts/jquery.sparkline.min.js ')}}"></script>

		<!-- Jquery-rating js-->
		<script src="{{asset ('assets/plugins/rating/jquery.rating-stars.js ')}}"></script>

		<!--Sidemenu js-->
		<script src="{{asset ('assets/plugins/sidemenu/sidemenu.js ')}}"></script>

		<!-- P-scroll js-->
		<script src="{{asset ('assets/plugins/p-scrollbar/p-scrollbar.js ')}}"></script>
		<script src="{{asset ('assets/plugins/p-scrollbar/p-scroll1.js ')}}"></script>
		<script src="{{asset ('assets/plugins/p-scrollbar/p-scroll.js ')}}"></script>


		<!--INTERNAL Flot Charts js-->
		<script src="{{asset ('assets/plugins/flot/jquery.flot.js ')}}"></script>
		<script src="{{asset ('assets/plugins/flot/jquery.flot.fillbetween.js ')}}"></script>
		<script src="{{asset ('assets/plugins/flot/jquery.flot.pie.js ')}}"></script>
		<script src="{{asset ('assets/js/dashboard.sampledata.js ')}}"></script>
		<script src="{{asset ('assets/js/chart.flot.sampledata.js ')}}"></script>

		<!-- INTERNAL Chart js -->
		<script src="{{asset ('assets/plugins/chart/chart.bundle.js ')}}"></script>
		<script src="{{asset ('assets/plugins/chart/utils.js ')}}"></script>

		<!-- INTERNAL Apexchart js -->
		<script src="{{asset ('assets/js/apexcharts.js ')}}"></script>

		<!--INTERNAL Moment js-->
		<script src="{{asset ('assets/plugins/moment/moment.js ')}}"></script>

		<!--INTERNAL Index js-->
		<script src="{{asset ('assets/js/index1.js ')}}"></script>


		<!-- INTERNAL Data tables -->
		<script src="{{asset('assets/plugins/datatables/DataTables/js/jquery.dataTables.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/DataTables/js/dataTables.bootstrap5.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Buttons/js/dataTables.buttons.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.bootstrap4.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/JSZip/jszip.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/pdfmake/pdfmake.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/pdfmake/vfs_fonts.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.html5.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.print.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Buttons/js/buttons.colVis.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Responsive/js/dataTables.responsive.min.js')}}"></script>
		<script src="{{asset('assets/plugins/datatables/Responsive/js/responsive.bootstrap5.min.js')}}"></script>
		<script src="{{asset('assets/js/datatables.js')}}"></script>

		<!-- INTERNAL Select2 js -->
		<script src="{{asset ('assets/plugins/select2/select2.full.min.js ')}}"></script>
		<script src="{{asset ('assets/js/select2.js ')}}"></script>

		<!-- Simplebar JS -->
		<script src="{{asset ('assets/plugins/simplebar/js/simplebar.min.js ')}}"></script>

		<!-- Rounded bar chart js-->
		<script src="{{asset ('assets/js/rounded-barchart.js ')}}"></script>


		<!-- Custom js-->
		<script src="{{asset ('assets/js/print.js ')}}"></script>
		<script src="{{asset ('assets/js/custom.js ')}}"></script>

		<!-- Sweet Alert -->
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


		<!-- Switcher js -->
		<script src="{{asset ('assets/switcher/js/switcher.js ')}}"></script>

		<!-- INTERNAL File-Uploads Js-->
		<script src="{{asset('assets/plugins/fancyuploder/jquery.ui.widget.js')}}"></script>
		<script src="{{asset('assets/plugins/fancyuploder/jquery.fileupload.js')}}"></script>
		<script src="{{asset('assets/plugins/fancyuploder/jquery.iframe-transport.js')}}"></script>
		<script src="{{asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js')}}"></script>
		<script src="{{asset('assets/plugins/fancyuploder/fancy-uploader.js')}}"></script>

		<!-- INTERNAL File uploads js -->
		<script src="{{asset('assets/plugins/fileupload/js/dropify.js')}}"></script>
		<script src="{{asset('assets/js/filupload.js')}}"></script>

		<!--INTERNAL Form Advanced Element -->
		<script src="{{asset('assets/js/formelementadvnced.js')}}"></script>
		<script src="{{asset('assets/js/form-elements.js')}}"></script>
		<script src="{{asset('assets/js/file-upload.js')}}"></script>

		<!-- INTERNAL WYSIWYG Editor js -->
		<script src="{{asset('assets/plugins/wysiwyag/jquery.richtext.js')}}"></script>
		<script src="{{asset('assets/js/form-editor.js')}}"></script>

		<!-- INTERNAL quill js -->
		<script src="{{asset('assets/plugins/quill/quill.min.js')}}"></script>
		<script src="{{asset('assets/js/form-editor2.js')}}"></script>
		<script src="{{asset('mycustom.jmycustom.js')}}"></script>
		<script>
			$(".my-layout").click(function () {
				if($('body').hasClass('light-mode')) {
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					var data = {
						'layout': "dark-mode",
					};
					$.ajax({
						url:"{{route('subadmin.theme')}}",
						type:"POST",
						dataType:"json",
						data: data,
						success:function(data){
							if (data.status == 200)
							{
								$('body').removeClass('light-mode');
								$('body').addClass('dark-mode');
							}
						}
					})
				}
				if($('body').hasClass('dark-mode')) {
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						}
					});
					var data = {
						'layout': "light-mode",
					};
					$.ajax({
						url:"{{route('subadmin.theme')}}",
						type:"POST",
						dataType:"json",
						data: data,
						success:function(data){
							if (data.status == 200)
							{
								$('body').removeClass('dark-mode');
								$('body').addClass('light-mode');
							}
						}
					})
				}
			});
		</script>
		@yield('query')
	</body>

</html>
