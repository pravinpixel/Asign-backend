@extends('layouts.index')
@section('title', 'Role Management List')

@push('head')
@endpush

@section('content')
<section class="section main-header filterHeaderCtr">
	<div class="section-breadcrumb">
		<ul>
			<li>
				<img src="{{ asset('icons/crown-1.svg') }}" width="20" height="20" />
                <a href="">Master /</a> <a href="">Roles</a>
			</li>
			<li>
				<span id="toggle_sidebar">
					<img src="{{ asset('icons/arrange-square.svg') }}" width="20" />
				</span>
			</li> 
		</ul>
	</div>
	<div class="section-title">
		<h4>Roles <span>(6 Roles)</span></h4>
	</div>
	@include('components.tables.rolemanagement_filter')
</section>
@include('components.tables.rolemanagement_table')
 @include('components.tables.asign_paginate')
@endsection

@push('scripts')
<script type="text/javascript">
	$(document).ready(function() {

	});
</script>
@endpush