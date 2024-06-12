<table class="asign-table purchase-order-table">
    <thead>
    <tr>
        <th scope="col" width="15%">SR NO.</th>
        <th scope="col" width="45%">PRODUCT NAME</th>
        <th scope="col" width="40%">QUANTITY</th>
        <th scope="col" width="20%">ACTION</th>
    </tr>
    </thead>
    <tbody>
        @if (count($products) > 0)
            @foreach ($products as $pro)
                <tr>
                    <td>{{$pro["sr_no"]}}</td>
                    <td>{{$pro["product"]}}</td>
                    <td class="qty">{{$pro["qty"]}}</td>
                    <td>
                        <a href="{{url('/label-damaged/summary/' . $pro["damage_id"])}}" class="btn cancel-btn cancel-btn-md">Edit</a>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="5" class="txt-center empty-msg">Add Labels</td>
            </tr>
        @endif
    </tbody>
</table>
