{{-- ============================================================
     ডিজাইন নোট (ইনভয়েস/রসিদে যা কাজ হয়েছে):
     • এটি প্রিন্ট-করার রসিদ — তাই ডার্ক নয়, পরিষ্কার প্রফেশনাল প্রিন্ট-স্টাইল।
     • হেডার, রসিদের তথ্য টেবিল, প্রাইস ব্রেকডাউন, PAID স্ট্যাটাস — কনটেন্ট আগের মতোই।
     • প্রিন্ট বাটন ও @media print স্টাইল অক্ষুণ্ণ।
     ============================================================ --}}
<div id="invoice" style="max-width:800px; margin:0 auto; padding:32px; background:#ffffff; font-family:'Segoe UI', -apple-system, sans-serif; color:#1e293b; border:1px solid #e2e8f0; border-radius:12px; font-size:15px;">

  {{-- Header --}}
  <div style="display:flex; justify-content:space-between; align-items:center; gap:20px; padding-bottom:22px; border-bottom:2px solid #e2e8f0;">
    <div style="display:flex; align-items:center; gap:16px;">
      <img src="{{ asset('logo1.jpg') }}" alt="Logo" style="width:72px; height:72px; border-radius:16px; object-fit:cover; box-shadow:0 2px 8px rgba(0,0,0,.12);">
      <div>
        <h2 style="margin:0; font-size:21px; color:#0f172a;">PrimeByte</h2>
        <p style="margin:2px 0 0; font-size:12.5px; color:#64748b;">Website Design &amp; Development</p>
        <p style="margin:2px 0 0; font-size:12.5px; color:#64748b;">Uttara, Sector 6, Road 12, Dhaka</p>
      </div>
    </div>
    <div style="text-align:right; font-size:12.5px; color:#64748b;">
      <p style="margin:2px 0;">01924529986</p>
      <p style="margin:2px 0;"><a href="mailto:primebyteweb@gmail.com" style="color:#2563eb; text-decoration:none;">primebyteweb@gmail.com</a></p>
      <p style="margin:2px 0;"><a href="https://primebyte-com.netlify.app/" target="_blank" style="color:#2563eb; text-decoration:none;">primebyte-com.netlify.app</a></p>
    </div>
  </div>

  {{-- Title --}}
  <div style="display:flex; justify-content:space-between; align-items:flex-end; margin:26px 0 20px;">
    <div>
      <h3 style="margin:0; font-size:22px; color:#0f172a; letter-spacing:.4px; text-transform:uppercase;">Paid Receipt <span style="font-weight:500; color:#64748b;">(রসিদ)</span></h3>
      <p style="margin:4px 0 0; font-size:13px; color:#64748b;">Official Payment Confirmation</p>
    </div>
    <span style="background:#dcfce7; color:#15803d; font-weight:700; font-size:12px; padding:6px 14px; border-radius:999px; letter-spacing:.6px;">PAID IN FULL</span>
  </div>

  {{-- Receipt Info --}}
  <table style="width:100%; border-collapse:collapse; margin-bottom:26px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; font-size:14px;">
    <tbody>
      <tr>
        <td style="padding:11px 16px; width:34%; color:#64748b; border-bottom:1px solid #eef2f7;">Receipt No:</td>
        <td style="padding:11px 16px; font-weight:600; border-bottom:1px solid #eef2f7;">SG-WEB-2025-01</td>
      </tr>
      <tr>
        <td style="padding:11px 16px; color:#64748b; border-bottom:1px solid #eef2f7;">Date Issued:</td>
        <td style="padding:11px 16px; font-weight:600; border-bottom:1px solid #eef2f7;">{{ now()->format('d M Y') }}</td>
      </tr>
      <tr>
        <td style="padding:11px 16px; color:#64748b; border-bottom:1px solid #eef2f7;">Client Name:</td>
        <td style="padding:11px 16px; font-weight:600; border-bottom:1px solid #eef2f7;">Shahriar Group of Companies</td>
      </tr>
      <tr>
        <td style="padding:11px 16px; color:#64748b; border-bottom:1px solid #eef2f7;">Client Contact:</td>
        <td style="padding:11px 16px; font-weight:600; border-bottom:1px solid #eef2f7;">01706636599</td>
      </tr>
      <tr>
        <td style="padding:11px 16px; color:#64748b; border-bottom:1px solid #eef2f7;">Project:</td>
        <td style="padding:11px 16px; font-weight:600; border-bottom:1px solid #eef2f7;">All Sections Website Design with Premium Template</td>
      </tr>
      <tr>
        <td style="padding:11px 16px; color:#64748b;">Total Sections Designed:</td>
        <td style="padding:11px 16px; font-weight:600;">8 Sections</td>
      </tr>
    </tbody>
  </table>

  {{-- Price Breakdown --}}
  <div style="margin-bottom:24px;">
    <h5 style="margin:0 0 10px; font-size:15px; color:#0f172a; text-transform:uppercase; letter-spacing:.6px;">Price Breakdown</h5>
    <table style="width:100%; border-collapse:collapse; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden; font-size:14px;">
      <thead>
        <tr style="background:#f1f5f9;">
          <th style="padding:11px 16px; text-align:left; color:#475569; font-size:12px; text-transform:uppercase; letter-spacing:.6px;">Service</th>
          <th style="padding:11px 16px; text-align:right; color:#475569; font-size:12px; text-transform:uppercase; letter-spacing:.6px;">Amount (৳)</th>
        </tr>
      </thead>
      <tbody style="background:#fff;">
        <tr>
          <td style="padding:11px 16px; border-top:1px solid #eef2f7;">Premium Template Design &amp; Setup</td>
          <td style="padding:11px 16px; text-align:right; border-top:1px solid #eef2f7;">1,500</td>
        </tr>
        <tr>
          <td style="padding:11px 16px; border-top:1px solid #eef2f7;">Full Website Design &amp; Customization</td>
          <td style="padding:11px 16px; text-align:right; border-top:1px solid #eef2f7;">1,400</td>
        </tr>
        <tr>
          <td style="padding:11px 16px; border-top:1px solid #eef2f7;">Responsive Layout &amp; Language Switch</td>
          <td style="padding:11px 16px; text-align:right; border-top:1px solid #eef2f7;">600</td>
        </tr>
        <tr style="background:#f8fafc; font-weight:700;">
          <td style="padding:12px 16px; border-top:2px solid #e2e8f0;">Total</td>
          <td style="padding:12px 16px; text-align:right; border-top:2px solid #e2e8f0;">৳ 3,500</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- Payment Status (PAID) --}}
  <p style="font-size:14px; color:#15803d; background:#f0fdf4; padding:13px 16px; border-left:4px solid #22c55e; border-radius:8px; margin-bottom:28px;">
    Payment Status: <strong>PAID IN FULL</strong><br>
    Thank you for your payment.
  </p>

  {{-- Footer --}}
  <div style="border-top:1px solid #e2e8f0; padding-top:16px; text-align:center; font-size:12.5px; color:#64748b;">
    <p style="margin:0;">Printed on: {{ now()->format('d M Y, h:i A') }}</p>
    <p style="margin:4px 0;">Contact: 01924529986 | <a href="mailto:primebyteweb@gmail.com" style="color:#2563eb; text-decoration:none;">primebyteweb@gmail.com</a></p>
    <p style="margin:6px 0;">© {{ now()->year }} All Rights Reserved to <strong>PrimeByte</strong></p>
  </div>
</div>

{{-- Print Button --}}
<div style="text-align:center; margin-top:20px;">
  <button onclick="window.print()" style="padding:10px 24px; border:none; background:#2563eb; color:#fff; border-radius:10px; cursor:pointer; font-weight:600; font-size:14px; font-family:inherit;">
    <i class="fas fa-print"></i> Print Receipt
  </button>
</div>

{{-- Print Styling --}}
<style>
  body { background:#eef1f6; margin:0; padding:24px; font-family:'Segoe UI', -apple-system, sans-serif; }
  @media print {
    body { margin:0; padding:0; font-size:15px; background:#fff; }
    #invoice { box-shadow:none; border:none; margin:0; width:100%; border-radius:0; }
    button { display:none; }
    table, td, th { page-break-inside: avoid; }
  }
</style>
