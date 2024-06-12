@extends('layouts.index')
@section('title', 'Label Request Summary')
@section('style')
    @parent
    <link rel="stylesheet" href="{{ asset('css/label.css') }}">
@endsection
@section('content')
    <div class="pages purchase-order-summary">
        <section class="m-header">
            <main class="hstack gap-3">
                <a href="#" id="backBtn">
                    <img src="{{ asset('icons/arrow-left-alt.svg') }}" width="24" height="24" class="cP"/>
                </a>
                <h4>{{$label->request_id}}</h4>
            </main>
            <div>
                @if($label->status === 'requested')
                <a href="{{ url('label-request/pdf', ['id' => $label->id]) }}" target="_blank">
                    <button type="button" class="btn cancel-btn">
                        Print
                    </button>
                </a>
                <a href="{{url('label-issues', ['id' => $label->id])}}" class="text-white">
                    <button type="button" class="btn apply-btn">
                        Issue Labels
                    </button>
                </a>
                @else
                <a href="{{ url('label-request/pdf', ['id' => $label->id]) }}" target="_blank" class="text-white">     
                    <button type="button" class="btn apply-btn">
                        Print
                    </button>
                </a>
                @endif
            </div>
        </section>
        <section class="section-inner">
            <h1>Label Request Summary</h1>
            <ul class="personal-info">
                <li>
                    <span>Request No.</span>
                    <span>{{$label->request_id}}</span>
                </li>
                <li>
                    <span>Created by</span>
                    <span>{{$label->createdBy?->name ?? '-'}}</span>
                </li>
                <li>
                    <span>Agent Name</span>
                    <span>{{$label->agent?->name ?? '-'}}</span>
                </li>
                <li>
                    <span>Location</span>
                    <span>{{$label->location?->name ?? '-'}}</span>
                </li>
                <li>
                    <span>Request Date</span>
                    <span>{{$label->created_at ? date('d M Y', strtotime($label->created_at)) : '-'}}</span>
                </li>
                <li>
                    <span>Issue Date</span>
                    <span>{{$label->issued_at ? date('d M Y', strtotime($label->issued_at)) : '-'}}</span>
                </li>
                <li>
                    <span>Return Date</span>
                    <span>{{$label->return_at ? date('d M Y', strtotime($label->return_at)) : '-'}}</span>
                </li>
            </ul>
        </section>
        <section class="table-content">

            <table class="asign-table purchase-order-table">
                <thead>
                <tr>
                    <th scope="col" width="10%">SR. NO.</th>
                    <th scope="col" width="14%">LABEL TYPE</th>
                    <th scope="col" width="14%">REQUESTED</th>
                    <th scope="col" width="14%">ISSUED</th>
                    <th scope="col" width="14%">CONSUMED</th>
                    <th scope="col" width="14%">RETURNED</th>
                    <th scope="col" width="10%">BALANCE</th>
                    <th scope="col" width="10%">STATUS</th>
                </tr>
                </thead>
                <tbody>

                @foreach($label->products as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            {{$item->product?->name}}
                        </td>
                        <td>{{ $item->qty ?? 0 }}</td>
                        <td>{{ $item->issued_qty ?? 0 }}</td>
                        <td>{{ $item->consumed_qty ?? 0 }}</td>
                        <td>{{ $item->returned_qty ?? 0 }}</td>
                        <td>{{ $item->balance_qty  ?? 0 }}</td>
                        <td>
                            <a href=""
                               class="{{$status[$item->status]['color']}} statusCtr">{{$status[$item->status]['label']}}</a>
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </section>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).on('click', '#backBtn', function (e) {
            e.preventDefault();
            let url = window.location.href;

            if (url.indexOf('label-issues') > -1) {
                window.location.href = "{{url('label-issues')}}";
            }
            else if (url.indexOf('label-return') > -1) {
                window.location.href = "{{url('label-return')}}";
            }
            else {
                window.location.href = "{{url('label-request')}}";
            }
        });

    </script>
@endpush

