<table class="asign-table purchase-order-table">
    <thead>
        <tr>
            <th scope="col" width="10%">SR NO.</th>
            <th scope="col" width="30%">PRODUCT NAME</th>
            <th scope="col" width="20%">ORDER QUANTITY</th>
            <th scope="col" width="20%">GRN QUANTITY</th>
            <th scope="col" width="20%">STATUS</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($stoSummary->stockTransferOrderProduct as $index => $stoProduct)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $stoProduct->product->name }}</td>
                <td>{{ $stoProduct->quantity }}</td>
                <td>{{ $stoProduct->grn_quantity }}</td>
                <td>
                    @if ($stoSummary->status == "Transit")
                        <span class="statusLavender statusCtr">{{ $stoSummary->status }}</span>
                    @elseif ($stoSummary->status == "Fulfilled")
                        <span class="statusGreen statusCtr">{{ $stoSummary->status }}</span>
                    @elseif ($stoSummary->status == "Packed")
                        <span class="statusOrange statusCtr">{{ $stoSummary->status }}</span>
                    @else
                        <span class="statusYellow statusCtr">{{ $stoSummary->status }}</span>
                    @endif
                </td>
            </tr>
        @empty  
            <tr>
                
            </tr>
        @endforelse
        
    </tbody>
</table>
