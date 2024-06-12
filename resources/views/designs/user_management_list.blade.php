@extends('layouts.index')
@section('title', 'User Management List')

@push('head')
@endpush

@section('content')
<section class="section main-header filterHeaderCtr">
	<div class="section-breadcrumb">
		<ul>
			<li>
				<img src="{{ asset('icons/crown-1.svg') }}" width="20" height="20" />
                <a href="">Master /</a> <a href="">Users</a>
			</li>
			<li>
				<span id="toggle_sidebar">
					<img src="{{ asset('icons/arrange-square.svg') }}" width="20" />
				</span>
			</li> 
		</ul>
	</div>
	<div class="section-title">
		<h4>Users <span>(130 Users)</span></h4>
	</div>
	@include('components.tables.usermanagement_filter')
</section>
@include('components.tables.usermanagement_table')
 @include('components.tables.asign_paginate')
@endsection

@push('scripts')
<script type="text/javascript">
	$(document).ready(function() {

	});
</script>
@endpush