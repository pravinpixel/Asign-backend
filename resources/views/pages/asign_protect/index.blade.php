@extends('layouts.index')
@section('title', 'Asign Protect')
@push('head')
@endpush
@section('content')
<div class="pages protect-plus">
	<section class="main-header">
		<div class="section-breadcrumb">
			<ul>
				<li>
					<img src="{{ asset('icons/clipboard-text.svg') }} " width="18" height="18" /><a href="">Asign Protect+</a>
				</li>
				<li>
					<span id="toggle_sidebar">
						<!-- <img src="https://uat-api.asign.art/admin/public/assets/icons/arrange-square.svg" width="20" /> -->
					</span>
				</li>
			</ul>
		</div>
		<div class="section-title">
			<h4>Asign Protect+ <span>(1034 Asign Protect+)</span></h4>
		</div>
	</section>
	@include('components.tables.asign_filter')
	<section class="table-content">
		@include('components.tables.asign_table')
	</section>
</div>

@include('components.tables.asign_paginate')
@include('components.popups.bulk_asign_popup')
@endsection
