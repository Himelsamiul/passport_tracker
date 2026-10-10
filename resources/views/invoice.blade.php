<div id="invoice" style="max-width:800px; margin:0 auto; padding:30px; background:#f4f6f9; font-family:'Segoe UI', sans-serif; color:#333; border-radius:12px; box-shadow:0 10px 25px rgba(0,0,0,0.08); font-size:15px;">

  {{-- Header --}}
  <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px; background:linear-gradient(90deg, #007bff, #00c0ff); padding:15px 20px; border-radius:10px; color:#fff;">
    <div>
      <img src="{{ asset('logo1.jpg') }}" alt="Logo" style="width:100px; height:100px; border-radius:50%; object-fit:cover; margin-bottom:4px;">
      <p style="font-size:11px; font-style:italic; color:#f0f8ff; max-width:200px; margin:0;">“Crafting Digital Solutions for Modern Businesses”</p>
    </div>
    <div style="text-align:right;">
      <h2 style="margin:0; font-size:22px;">PrimeByte</h2>
      <p style="margin:3px 0; font-size:13px; color:#f0f8ff;">Website Design & Development Department</p>
      <p style="margin:3px 0; font-size:13px; color:#f0f8ff;">01924529986 | <a href="mailto:primebyteweb@gmail.com" style="color:#e0f7ff;">primebyteweb@gmail.com</a></p>
      <p style="margin:3px 0; font-size:13px; color:#f0f8ff;">Uttara, Sector 6, Road 12, Dhaka</p>
      <p style="margin:3px 0; font-size:13px;"><a href="https://primebyte-com.netlify.app/" target="_blank" style="color:#e0f7ff;">primebyte-com.netlify.app</a></p>
    </div>
  </div>

  {{-- Title --}}
  <div style="text-align:center; margin:25px 0;">
    <h3 style="margin:0; font-size:24px; color:#28a745; letter-spacing:1px; text-transform:uppercase;">Paid Receipt (রসিদ)</h3>
    <p style="font-size:14px; color:#555;">Official Payment Confirmation</p>
  </div>

  {{-- Receipt Info --}}
  <table style="width:100%; border-collapse:collapse; margin-bottom:25px; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,0.05); font-size:15px;">
    <tbody>
      <tr style="background:#e6f2ff;">
        <td style="padding:12px 15px; width:30%; font-weight:bold;">Receipt No:</td>
        <td style="padding:12px 15px;">SG-WEB-2025-01</td>
      </tr>
      <tr>
        <td style="padding:12px 15px; font-weight:bold;">Date Issued:</td>
        <td style="padding:12px 15px;">{{ now()->format('d M Y') }}</td>
      </tr>
      <tr style="background:#e6f2ff;">
        <td style="padding:12px 15px; font-weight:bold;">Client Name:</td>
        <td style="padding:12px 15px;">Shahriar Group of Companies</td>
      </tr>
      <tr>
        <td style="padding:12px 15px; font-weight:bold;">Client Contact:</td>
        <td style="padding:12px 15px;">01706636599</td>
      </tr>
      <tr style="background:#e6f2ff;">
        <td style="padding:12px 15px; font-weight:bold;">Project:</td>
        <td style="padding:12px 15px;">All Sections Website Design with Premium Template</td>
      </tr>
      <tr>
        <td style="padding:12px 15px; font-weight:bold;">Total Sections Designed:</td>
        <td style="padding:12px 15px;">8 Sections</td>
      </tr>
    </tbody>
  </table>

  {{-- Price Breakdown --}}
  <div style="margin-bottom:20px;">
    <h5 style="margin-bottom:10px; font-size:17px; color:#007bff;">Price Breakdown:</h5>
    <table style="width:100%; border-collapse:collapse; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05); font-size:15px;">
      <thead style="background:#007bff; color:#fff;">
        <tr>
          <th style="padding:12px; text-align:left;">Service</th>
          <th style="padding:12px; text-align:right;">Amount (৳)</th>
        </tr>
      </thead>
      <tbody style="background:#fff; color:#333;">
        <tr>
          <td style="padding:12px; border-bottom:1px solid #ddd;">Premium Template Design & Setup</td>
          <td style="padding:12px; text-align:right; border-bottom:1px solid #ddd;">1,500</td>
        </tr>
        <tr>
          <td style="padding:12px; border-bottom:1px solid #ddd;">Full Website Design & Customization</td>
          <td style="padding:12px; text-align:right; border-bottom:1px solid #ddd;">1,400</td>
        </tr>
        <tr>
          <td style="padding:12px; border-bottom:1px solid #ddd;">Responsive Layout & Language Switch</td>
          <td style="padding:12px; text-align:right; border-bottom:1px solid #ddd;">600</td>
        </tr>
        <tr style="background:#e6f2ff; font-weight:bold;">
          <td style="padding:12px;">Total</td>
          <td style="padding:12px; text-align:right;">৳ 3,500</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Payment Status (PAID) --}}
  <p style="font-size:15px; color:#28a745; font-weight:bold; background:#e9fbe9; padding:12px 15px; border-left:4px solid #28a745; border-radius:4px; margin-bottom:30px;">
    Payment Status: <strong>PAID IN FULL</strong><br>
    Thank you for your payment.
  </p>

  <hr style="border:1px solid #ddd; margin-bottom:20px;">

  {{-- Footer --}}
  <div style="text-align:center; font-size:14px; color:#555;">
    <p style="margin:0;">Printed on: {{ now()->format('d M Y, h:i A') }}</p>
    <p style="margin:4px 0;">Contact: 01924529986 | <a href="mailto:primebyteweb@gmail.com" style="color:#007bff;">primebyteweb@gmail.com</a></p>
    <p style="margin:6px 0;">© {{ now()->year }} All Rights Reserved to <strong>PrimeByte</strong></p>
  </div>

</div>

{{-- Print Button --}}
<div style="text-align:center; margin-top:20px;">
  <button onclick="window.print()" style="padding:10px 22px; border:none; background:#007bff; color:#fff; border-radius:6px; cursor:pointer; font-weight:bold; font-size:15px;">
    Print Receipt
  </button>
</div>

{{-- Print Styling --}}
<style>
  @media print {
    body { margin:0; padding:0; font-size:15px; }
    #invoice { box-shadow:none; border:none; margin:0; width:100%; }
    button { display:none; }
    table, td, th { page-break-inside: avoid; }
  }
</style>
