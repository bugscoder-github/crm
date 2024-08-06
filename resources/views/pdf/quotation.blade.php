<!DOCTYPE html>
<html>
<head>
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
      }
      td {
        padding: .75rem;
      }
      .table-striped tbody tr:nth-of-type(2n+1) {
        background-color: rgba(0,0,0,.05);
      }
      table {
        width: 100%;
      }
      .table td, .table th {
        padding: .75rem;
        vertical-align: top;
        border-top: 1px solid #dee2e6;
        text-align: left;
        white-space: pre-wrap;
      }
    </style>
</head>
<body>
  
  <img src="{{ public_path('img/logo.png') }}" width="60">
  <table class="table">
    <tr>
        <td width="35%">From

<b>UniSmart</b>
93, Jalan BP 6/3, Bandar Bukit
Puchong, 47120 Puchong, Selangor

Phone: (+60) 12 987 9936
<td width="35%">To

<b>{{ $quotation_name }}</b>
@if($quotation_attn != "")
(<i>Attn: {{ $quotation_attn }}</i>)
@endif
{{ $quotation_billingAddress }}

Phone: {{ $quotation_phone}}
@if($quotation_email != "")
Email: {{ $quotation_email }}
@endif
</td>
        <td width="30%">Quotation #{{ $quotation_number }}
          <!-- <br>
<br>
Order ID: 4F3S8J<br>
Payment Due: 2/22/2014<br>
Account: 968-34567  -->
</td>
    </tr>
    @if($quotation_deliveryAddress != "" && $quotation_deliveryAddress != $quotation_billingAddress)
    <tr><td colspan="3">Delivery Address: {{ $quotation_deliveryAddress }}</td></tr>
    @endif
  </table>
<table class="table table-striped" width="100%">
          <thead>
          <tr>
            <th>Item</th>
            <th width="50" style="text-align: center;">Unit Price</th>
            <th style="text-align: center;">Freq.</th>
            <th style="text-align: center;">Amount</th>
          </tr>
          </thead>
          <tbody>

          @foreach ($quotation_items as $value)
          <tr>
          <td>{{ $value->quotationItem_desc }}</td>
          <td style="text-align: right">{{ $value->quotationItem_ppu }}</td>
          <td style="text-align: center">{{ $value->quotationItem_qty }}</td>
          <td style="text-align: right">{{ $value->quotationItem_total }}</td>
          </tr>
          @endforeach
          </tbody>
        </table>

        <table class="table">
          <tr>
            <td width="50%">  
@if($quotation_remark != "")
<b>Remarks:</b> {{ $quotation_remark }}
@endif
@if($quotation_tnc != "")
@if($quotation_remark != "")<br>@endif
<b>Terms and Conditions:</b> {{ $quotation_tnc }}
@endif
            </td><td width="50%"><table class="table">
            <tbody><tr>
              <th style="width:60%">Subtotal:</th>
              <th style="width:1px">RM</th>
              <td style="text-align: right;">{{ $quotation_total }}</td>
            </tr>
            @if($quotation_sstPct > 0)
            <tr>
              <th>Tax ({{ $quotation_sstPct }}%):</th>
              <th>RM</th>
              <td style="text-align: right;">{{ $quotation_sst }}</td>
            </tr>
            @endif
            <tr>
              <th>Total:</th>
              <th>RM</th>
              <td style="text-align: right;">{{ $quotation_grandTotal }}</td>
            </tr>
          </tbody></table>
            </td>
          </tr>
        </table>
</body>
</html>