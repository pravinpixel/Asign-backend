@php
    $data = $data->toArray() ?? [];

    $disablePrev = $data['from'] == 1 ? 'disabled' : '';
    $disableNext = $data['to'] >= $data['total'] ? 'disabled' : '';
    $showPagination = !($data['total'] <= 10);
    $perPage = $data['per_page'];
@endphp

@if($showPagination)

    <section class="section table-footer footer-form px-4">
        <div>
            <div><span>Rows:</span></div>
            <div>
                <select class="form-select form-select-sm" aria-label="per_page" id="per-page">
                    <option value="10" {{$perPage == 10 ? 'selected' : ''}}>10</option>
                    <option value="25" {{$perPage == 25 ? 'selected' : ''}}>25</option>
                    <option value="50" {{$perPage == 50 ? 'selected' : ''}}>50</option>
                    <option value="100" {{$perPage == 100 ? 'selected' : ''}}>100</option>
                </select>
            </div>

            <div class="paginate">
                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="pagination">
                    <div class="btn-group" role="group" aria-label="First group">
                        <button {{$disablePrev}} type="button"
                                class="btn btn-outline-secondary btn-light btn-sm arrow-btn"
                                data-value="dec">
                            <i class='bx bx-chevron-left'></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-light btn-sm">
                        <span class="from-to-page">
                            {{$data['current_page']}}
                        </span> of <span class="total-page">{{ ceil($data['total']/$perPage) }}</span>
                        </button>
                        <button {{$disableNext}} type="button"
                                class="btn btn-outline-secondary btn-light btn-sm arrow-btn"
                                data-value="inc">
                            <i class='bx bx-chevron-right'></i>
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endif

