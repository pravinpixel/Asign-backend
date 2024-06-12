@php
    $existing_code = [];
    if (sizeof($damage_details) > 0) {
        foreach ($damage_details as $dd => $detail) {
            $existing_code[] = $detail->code;
        }
    }
@endphp
<section class="section-inner">
    <div class="row">
        <div class="col-md-6">
            <div class="headerBorderBox">
                <div class="vstack gap-2">
                    <div class="headerBorderBox-head">Product Type</div>
                    <div class="headerBorderBox-sub">{{ $product_type }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="headerBorderBox">
                <div class="vstack gap-2">
                    <div class="headerBorderBox-head">Quantity</div>
                    <div class="headerBorderBox-sub">{{ sizeOf($damage_details) }}</div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="table-content">
    <table class="asign-table edit-asign-table" id="summary_damage_table">
        <thead>
            <tr>
                <th scope="col" width="11%">SR NO.</th>
                <th scope="col" width="27%">ENVELOPE ID</th>
                <th scope="col" width="20%">LABEL ID</th>
                <th scope="col" width="27%">DAMAGE TYPE</th>
                <th scope="col" width="15%">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @if (count($damage_details) > 0)
                @foreach ($damage_details as $in => $detail)
                    <tr>
                        <td class="serial_no">{{ ++$in }}</td>
                        <td>{{ $detail->code }}</td>
                        <td>{{ $detail->envelope_label_code }}</td>
                        <td>{{ $detail->damage_type_txt }}</td>
                        <td>
                            <button class="btn outlined removeLabel" data-rowid="{{ $detail->id }}">Remove</button>
                        </td>
                    </tr>
                @endforeach
            @else
                {{-- <tr>
                    <td colspan="5" class="txt-center empty-msg">Add Labels</td>
                </tr> --}}
            @endif
        </tbody>
    </table>
    <form id="create_form" autocomplete="off">
        <input type="hidden" id="product_id" name="product_id" value="{{ $product_id }}">
        <input type="hidden" id="damaged_id" name="damaged_id" value="{{ $damage_id }}">
        <input type="hidden" id="pro_tabel_id" name="product_id_url" value="{{ $product_id_url }}">
        <table class="asign-table edit-asign-table">
            <tbody>
                <tr>
                    <td width="11%" id="total_labels">{{ sizeof($damage_details) + 1 }}</td>
                    <td width="27%">
                        <div class="w100Select">
                            <select id="envelope_id" name="code" class="select2Box"
                                data-placeholder="Scan or Enter Envelope ID"
                                data-search="yes">
                                <option value=""></option>
                                @foreach ($label_list as $lab => $label)
                                    <option value="{{ $label->scanned_product_id }}"
                                        @if (in_array($label->scanned_product_id, $existing_code)) disabled @endif>
                                        {{ $label->scanned_product_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td width="20%">
                        <input id="label_id" name="label_id" type="text" class="form-control formplaceholder-bg"
                            placeholder="Scan or Enter Label ID">
                    </td>
                    <td width="27%">
                        <div class="w100Select">
                            <select id="damage_type" name="damage_type" class="select2Box"
                                data-placeholder="Select Damage Type"
                                data-search="no">
                                <option value=""></option>
                                @foreach ($damage_types as $ty => $type)
                                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td width="15%">
                        <button class="btn outlined off">Remove</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</section>
