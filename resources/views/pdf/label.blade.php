<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label</title>
    <style>
         @font-face {
            font-family: 'NeueMontreal-Medium';
            src: url('{{ $fontPaths[0] }}') format('opentype');
        }

        @font-face {
            font-family: 'NeueMontreal-Regular';
            src: url('{{ $fontPaths[1] }}') format('opentype');
        }
        h1,h2 {
            font-family: 'NeueMontrealMedium', sans-serif;
        }

        p {
            font-family: 'NeueMontrealRegular', sans-serif;
        }
        .product-details td,th{
            font-family: 'NeueMontrealRegular', sans-serif;
        }
        .po-details td,th{
            font-family: 'NeueMontrealRegular', sans-serif;
        }
        .header-table td,p{
            font-family: 'NeueMontrealRegular', sans-serif;
        }
        .footer td{
            font-family: 'NeueMontrealRegular', sans-serif;
        }
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            /* height: 3cm; */
        }

        /** Define the footer rules **/
        .footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }

        .content {
            padding-top: 40px;
        }

        h1 {
            padding-top: 0px !important;
            margin-top: 0px !important;
        }
         h1.logo{
             font-family: 'NeueMontreal-Regular', sans-serif!important;
             position: relative;
             padding-left: 22px;
             font-weight: normal;
             font-size: 28px;
         }
         h1.logo > span{
             position: absolute;
             left: 0;
             transform: rotate(-90deg);
         }

    </style>
</head>

<body>
    <div class="wrapper">
        <div class="header">
            <table class="header-table" style="width:100%">
                <tr>
                    <td style="vertical-align:top;width:20%;">
                        <h1 class="logo"><span>A</span>SIGN</h1>
                    </td>
                    <td style="vertical-align:top;width:60%;text-align:center;">
                        @php
                        $full_address = config('app.address');
                        list($line1, $line2) = explode('$', $full_address);
                    @endphp
                        <h3 style="margin: 0px !important;">{{ config('app.company') }}</h3>
                        <p style="margin: 0px !important; padding-top:1% !important; font-size:12px;">{{ $line1 }}</p>
                        <p style="margin: 0px !important; font-size:12px;">{{ $line2 }}</p>
                    </td>
                    <td style="vertical-align:top;width:20%;"><span style="color: #cfcfcf;">Date: </span>{{ \Carbon\Carbon::now()->format('d M Y') }}</td>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content">
            <h2 style="text-align: center;">Label Request</h2>
            <table class="po-details" width="font-size:15px;">
                <tr>
                    <td style="color: #cfcfcf;">Request No:</td>
                    <td>{{ $data->request_id }}</td>
                </tr>
                <tr style="padding-top: 50px;">
                    <td style="color: #cfcfcf;">Created By:</td>
                    <td>{{ $data->createdBy->name }}</td>

                </tr>
                <tr style="padding-top: 50px;">
                    <td style="color: #cfcfcf;">Agent Name:</td>
                    <td>{{ $data->agent->name }}</td>

                </tr>
            </table>

            <table class="product-details"
                style="width: 100%;padding-top:50px;font-size:15px;border: 1px solid #cfcfcf;
                        border-collapse: collapse;">
                <thead style="padding-top:25px;padding-bottom:25px;">
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        LABEL TYPE</th>
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        REQUESTED</th>
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        ISSUED</th>
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        CONSUMED</th>
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        RETURNED</th>
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        BALANCE</th>
                    <th style="background-color: #cfcfcf;border-collapse: collapse;padding-top:7px;padding-bottom:7px;text-align:left;padding-left:25px">
                        STATUS</th>
                </thead>
                <tbody style="padding-top: 30px !important;">
                    @foreach ($data->products as $item)
                        <tr style="border-bottom: 1px solid #cfcfcf;">
                            <td style="padding-left:25px;padding-top:7px;padding-bottom:7px;">{{$item->product?->name}}</td>
                            <td style="padding-left:25px;">{{ $item->qty ?? 0 }}</td>
                            <td style="padding-left:25px;">{{ $item->issued_qty ?? 0 }}</td>
                            <td style="padding-left:25px;">{{ $item->consumed_qty ?? 0 }}</td>
                            <td style="padding-left:25px;">{{ $item->returned_qty ?? 0 }}</td>
                            <td style="padding-left:25px;">{{ $item->balance_qty  ?? 0 }}</td>
                            <td style="padding-left:25px;">{{ $item->status ?? '-' }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>

        <div class="footer">
        <table>
                <tr>
                    <td style=" font-size:12px;">
                        Created By
                    </td>
                    <td style="width:40%;">
                         <hr style="width:250px; margin-top:19px; margin-left:8px; border-color: #cfcfcf;">
                    </td>
                    <td style="width:40px;">
                     </td>
                    <td style=" font-size:12px;">Checked By</td>
                    <td style="width:40%;">
                        <hr style="width:250px; margin-top:19px; margin-left:8px; border-color: #cfcfcf;">
                    </td>
                 </tr>
            </table>
        </div>



    </div>
</body>

</html>
