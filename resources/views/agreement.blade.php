<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>এজেন্ট–কোম্পানি চুক্তিপত্র - প্রিন্টযোগ্য</title>
  <style>
    /*
     * এই CSS টি মূলত প্রিন্টযোগ্য লেটারহেড এবং ফুটার ফিক্সড করার জন্য
     * @media print কোয়েরি ব্যবহার করে তৈরি।
    */
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@300;400;500;700&display=swap');
    
    body {
      font-family: 'Noto Sans Bengali', sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f4f4;
    }

    :root{
      --line:#e9ecef;
      --muted:#6b7280;
      --title:#111827;
      --shade:#f8fafc;
      --brand:#12824a;
      --brand-dark:#0c5c34;
      --mint:#e9f7ef;
    }
    .app-wrap{
      max-width:820px;
      margin:24px auto;
      background:#fff;
      border:1px solid var(--line);
      box-shadow:0 6px 20px rgba(0,0,0,.06);
      border-radius:12px;
      overflow:hidden;
      position:relative;
      background-image:url("data:image/svg+xml,%3Csvg width='340' height='340' viewBox='0 0 340 340' xmlns='http://www.w3.org/2000/svg'%3E%3Cg opacity='0.06' fill='%23000000'%3E%3Cpath d='M170 40c40 25 60 55 60 90s-20 65-60 90c-40-25-60-55-60-90s20-65 60-90z'/%3E%3Cpath d='M240 180c30 19 45 41 45 67s-15 48-45 67c-30-19-45-41-45-67s15-48 45-67z'/%3E%3C/g%3E%3C/svg%3E");
      background-repeat:no-repeat;
      background-position:120% 90%;
    }
    .letterhead{
      background:linear-gradient(0deg,#ffffff 0,#ffffff 58%,transparent 58%), var(--mint);
    }
    .letterhead .topbar{
      position:relative;
      padding:16px 20px;
      color:#fff;
      background:linear-gradient(90deg,var(--brand-dark),var(--brand));
    }
    .letterhead .topbar:after{
      content:"";
      position:absolute;
      right:-50px;
      top:0;
      height:100%;
      width:160px;
      background:linear-gradient(90deg,rgba(255,255,255,.08),rgba(255,255,255,.22));
      transform:skewX(-24deg);
    }
    .topbar .flex{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:16px;
      position:relative;
      z-index:2;
    }
    .brand{
      display:flex;
      align-items:center;
      gap:12px;
    }
    .brand img{
      width:56px;
      height:56px;
      object-fit:contain;
      background:#fff;
      border-radius:8px;
      padding:6px;
    }
    .brand .name{
      display:flex;
      flex-direction:column;
    }
    .brand .name b{
      font-size:22px;
      letter-spacing:1px;
    }
    .brand .name small{
      font-size:12px;
      opacity:.9;
    }
    .contact{
      display:flex;
      flex-direction:column;
      gap:4px;
      text-align:right;
    }
    .contact span{
      font-size:12px;
      line-height:1;
    }
    .contact a{
      color:#fff;
      text-decoration:none;
    }
    .letterhead .divider{
      height:5px;
      background:linear-gradient(90deg,var(--brand) 0 20%,#1fa35f 20% 60%,var(--brand-dark) 60% 100%);
    }
    .app-title{
      padding:18px;
      text-align:center;
    }
    .app-title h3{
      font-size:18px;
      margin:0;
      font-weight:800;
      color:var(--title);
    }
    .app-title small{
      display:block;
      margin-top:4px;
      font-size:13px;
      color:var(--muted);
    }
    .section{
      margin:18px;
      border:1px solid var(--line);
      border-radius:8px;
      overflow:hidden;
      background:#fff;
      padding:16px;
    }
    .content-block {
        padding-bottom: 20px;
        margin-bottom: 20px;
        border-bottom: 1px dashed var(--line);
    }
    .content-block:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0;
    }

    .section p, .section ul{
      font-size:14px;
      line-height:1.8; /* লাইনের ব্যবধান বাড়ানো হলো */
      color:#111;
      margin-bottom: 12px;
    }
    .section h4 {
        font-size: 15px;
        font-weight: 700;
        color: var(--brand-dark);
        margin-top: 25px;
        margin-bottom: 10px;
        border-left: 4px solid var(--brand);
        padding-left: 8px;
    }
    .signs{
      display:flex;
      justify-content:space-between;
      margin-top:36px;
      flex-wrap:wrap;
    }
    .sign-box{
      width:30%;
      text-align:left;
      margin-bottom:16px;
      border-top: 1px solid var(--line);
      padding-top: 8px;
      font-size: 13px;
    }
    .letterfoot{
      position:relative;
      height:72px;
      background:var(--mint);
      overflow:hidden;
      border-top:1px solid var(--line);
    }
    .letterfoot .band{
      position:absolute;
      left:0;
      right:0;
      bottom:0;
      height:56px;
      background:linear-gradient(90deg,var(--brand-dark),var(--brand));
    }
    .letterfoot .w1,.letterfoot .w2{
      position:absolute;
      bottom:-32px;
      width:240px;
      height:120px;
      transform:skewX(-24deg);
    }
    .letterfoot .w1{left:14%; background:rgba(255,255,255,.08);}
    .letterfoot .w2{left:36%; background:rgba(255,255,255,.04);}
    .letterfoot .foot-text{
      position:absolute;
      left:50%;
      bottom:10px;
      transform:translateX(-50%);
      width:92%;
      text-align:center;
      z-index:3;
      color:#ffffff;
      font-size:12px;
      line-height:1.35;
      font-weight:500;
      text-shadow:0 1px 2px rgba(0,0,0,.4);
    }
    .btn{
      display:inline-block;
      padding:8px 16px;
      border-radius:8px;
      text-decoration:none;
      font-weight:600;
      border:1px solid transparent;
      cursor: pointer;
    }
    .btn-primary{
      background:#0d6efd;
      color:#fff;
    }

    /* প্রিন্ট মিডিয়া কোয়েরি */
    @media print{
      .btn{display:none !important}
      /* পেজ মার্জিন সেট করা হয়েছে */
      @page { margin: 0.5in; }
      
      .app-wrap{
        box-shadow:none;
        border:none;
        width: 100%;
        max-width: none;
        margin: 0;
      }
      body{
        -webkit-print-color-adjust:exact;
        print-color-adjust:exact;
        background: #fff;
      }
      
      /* 1. চুক্তিপত্রের শিরোনাম (app-title) প্রিন্ট করার সময় লুকানো হলো */
      .app-title {
        display: none !important;
      }
      
      /* 2. ট্যাগালাইন লুকানো হলো (সম্ভাব্য "extra word") */
      .brand .name small {
          display: none !important;
      }
      
      /* 3. ফুটার (letterfoot) সম্পূর্ণ লুকানো হলো */
      .letterfoot {
        display: none !important;
      }
      
      /* 4. ওয়েবসাইট লিংক (.contact span:last-child) লুকানো হলো */
      .contact span:last-child {
        display: none !important;
      }

      /* হেডারকে প্রতিটি পেজে ফিক্সড করা হলো */
      .letterhead{
        position:fixed; 
        left:0; 
        right:0;
        top:0;
        z-index: 1000;
        padding-top: 15px; 
      }
      
      /* কন্টেন্ট ব্লককে হেডার ও ফুটারের সাথে ওভারল্যাপ এড়ানোর জন্য মার্জিন */
      .section{
        border: none;
        box-shadow: none;
        padding: 0;
        /* app-title, tagline এবং letterfoot সরানো হয়েছে বলে মার্জিন সামঞ্জস্য করা হয়েছে */
        margin: 100px 40px 40px 40px; 
        border-radius: 0;
        overflow: visible;
      }
    }
  </style>
</head>
<body>
<div style="text-align:right;margin:10px 18px;">
  <button class="btn btn-primary" onclick="window.print()">প্রিন্ট করুন</button>
</div>
<div class="app-wrap">
  <!-- HEADER -->
  <div class="letterhead">
    <div class="topbar">
      <div class="flex">
        <div class="brand">
          <!-- ডেমো লোগো হিসেবে placeholder ব্যবহার করা হয়েছে -->
          <img src="https://placehold.co/56x56/ffffff/12824a?text=LOGO" alt="Company Logo"> 
          <div class="name">
            <b>SHAHRIAR WORLDWIDE VENTURES</b>
            <small>Excellence • Integrity • Service</small>
          </div>
        </div>
        <div class="contact">
          <span>+66 83 850 3337</span>
          <span><a href="mailto:shahriar.agroup@gmail.com">shahriar.agroup@gmail.com</a></span>
          <span><a href="https://shahriar-group.com" target="_blank">www.shahriar-group.com</a></span>
        </div>
      </div>
    </div>
    <div class="divider"></div>
  </div>
  <!-- TITLE - এই অংশটি প্রিন্টে অদৃশ্য থাকবে -->
  <div class="app-title">
    <h3>এজেন্ট–কোম্পানি চুক্তিপত্র</h3>
    <small>চুক্তি নং: __________________ | তারিখ: ____ / ____ / ______</small>
  </div>
  
  <!-- BODY (একাধিক পেজের জন্য কন্টেন্ট পুনরাবৃত্তি করা হয়েছে) -->
  <div class="section">
    
    <!-- কন্টেন্ট ব্লক ১ (অরিজিনাল টেক্সট) -->
    <div class="content-block">
        <p>পক্ষ–১: কোম্পানি<br>
          Shahriar Worldwide Venture<br>
          ঠিকানা: 7th Floor, 18/3 Ring Road, Block F, Mohammadpur, Dhaka<br>
          ফোন/হোয়াটসঅ্যাপ: +855 96 822 5091<br>
          ইমেইল: shahriar.agroup@gmail.com<br>
          ওয়েবসাইট: www.shahriar-worldwide.com
        </p>
        <p>পক্ষ–২: এজেন্ট / প্রতিনিধি<br>
          নাম: __________________________<br>
          পাসপোর্ট/এনআইডি: __________________<br>
          ঠিকানা: __________________________<br>
          মোবাইল: __________________________
        </p>
        <p>১. চুক্তির উদ্দেশ্য<br>
          এই চুক্তির উদ্দেশ্য হলো পক্ষ–২ (এজেন্ট)কে কোম্পানির অনুমোদিত প্রতিনিধি হিসেবে কাজ করার সুযোগ প্রদান করা, যাতে তিনি গ্রাহক সংগ্রহ, ডকুমেন্ট সংগ্রহ এবং ভিসা–সম্পর্কিত সেবা প্রদানের ক্ষেত্রে কোম্পানিকে সহযোগিতা করতে পারেন।
        </p>
        <p>২. এজেন্টের দায়িত্ব<br>
          • গ্রাহক/ক্লায়েন্ট সংগ্রহ করা<br>
          • সঠিক ডকুমেন্ট সংগ্রহ করে কোম্পানিতে জমা দেওয়া<br>
          • গ্রাহকের সাথে ভিসা প্রসেস সম্পর্কে সঠিক তথ্য শেয়ার করা<br>
          • কোম্পানির নির্ধারিত ফি বা চার্জ গ্রাহকের কাছ থেকে সংগ্রহ করা<br>
          • মিথ্যা তথ্য বা ভুয়া ডকুমেন্ট জমা না দেওয়া
        </p>
        <p>৩. কোম্পানির দায়িত্ব<br>
          • গ্রাহকের ভিসা প্রসেসিং সঠিক এবং আইন অনুযায়ী সম্পন্ন করা<br>
          • এজেন্টকে প্রয়োজনীয় নির্দেশনা, গাইডলাইন এবং আপডেট প্রদান<br>
          • গ্রাহকের ডাটা ও তথ্য নিরাপদ রাখা<br>
          • নির্ধারিত কমিশন এজেন্টকে সময়মতো প্রদান করা
        </p>
        <p>৪. কমিশন ও পেমেন্ট<br>
          প্রতি ভিসা/প্রসেস অনুযায়ী এজেন্ট কমিশন পাবেন: ______% / ______ টাকা।<br>
          ক্লায়েন্ট ফাইল কোম্পানিতে জমা দেওয়ার পর কমিশন নির্ধারিত হবে। ভিসা অনুমোদনের পর কমিশন পেমেন্ট প্রদান করা হবে।
        </p>
        <p>৫. গোপনীয়তা (Confidentiality)<br>
          এজেন্ট কোম্পানির কোনো গোপন তথ্য, গ্রাহক ডাটা বা ব্যবসায়িক কৌশল তৃতীয় পক্ষের কাছে প্রকাশ করতে পারবেন না।
        </p>
        <p>৬. নিষিদ্ধ কার্যকলাপ<br>
          • অন্য প্রতিদ্বন্দ্বী কোম্পানির হয়ে একই সেবা প্রদান<br>
          • ভিসা গ্যারান্টি দেওয়া<br>
          • অতিরিক্ত টাকা আদায় করা<br>
          • কোম্পানির নাম ব্যবহার করে প্রতারণা করা
        </p>
        <p>৭. চুক্তির মেয়াদ<br>
          এই চুক্তি ____ বছর/মাস পর্যন্ত কার্যকর থাকবে। উভয় পক্ষ নোটিশ প্রদান করে চুক্তি বাতিল করতে পারবেন।
        </p>
        <p>৮. আইনগত বিষয়<br>
          এই চুক্তি বাংলাদেশের প্রযোজ্য আইনের আওতায় পরিচালিত হবে। কোনো বিরোধ হলে আলোচনার মাধ্যমে সমাধান করা হবে।
        </p>
        <div class="signs">
          <div class="sign-box">
            পক্ষ–১ (কোম্পানি):<br>
            স্বাক্ষর: __________________<br>
            নাম: ______________________<br>
            Designation: _______________
          </div>
          <div class="sign-box">
            পক্ষ–২ (এজেন্ট):<br>
            স্বাক্ষর: __________________<br>
            নাম: ______________________
          </div>
          <div class="sign-box">
            সাক্ষী–১: __________________<br>
            সাক্ষী–২: __________________
          </div>
        </div>
    </div>
    
    <!-- কন্টেন্ট ব্লক ২ (নকল) - মাল্টিপেজ নিশ্চিত করার জন্য -->
    <div class="content-block">
        <h4>দ্বিতীয় সেকশনের চুক্তি বিবরণী (শুধুমাত্র ডেমো কন্টেন্টের জন্য)</h4>
        <p>১. চুক্তির উদ্দেশ্য<br>
          এই চুক্তির উদ্দেশ্য হলো পক্ষ–২ (এজেন্ট)কে কোম্পানির অনুমোদিত প্রতিনিধি হিসেবে কাজ করার সুযোগ প্রদান করা, যাতে তিনি গ্রাহক সংগ্রহ, ডকুমেন্ট সংগ্রহ এবং ভিসা–সম্পর্কিত সেবা প্রদানের ক্ষেত্রে কোম্পানিকে সহযোগিতা করতে পারেন।
        </p>
        <p>২. এজেন্টের দায়িত্ব<br>
          • গ্রাহক/ক্লায়েন্ট সংগ্রহ করা<br>
          • সঠিক ডকুমেন্ট সংগ্রহ করে কোম্পানিতে জমা দেওয়া<br>
          • গ্রাহকের সাথে ভিসা প্রসেস সম্পর্কে সঠিক তথ্য শেয়ার করা<br>
          • কোম্পানির নির্ধারিত ফি বা চার্জ গ্রাহকের কাছ থেকে সংগ্রহ করা<br>
          • মিথ্যা তথ্য বা ভুয়া ডকুমেন্ট জমা না দেওয়া
        </p>
        <p>৩. কোম্পানির দায়িত্ব<br>
          • গ্রাহকের ভিসা প্রসেসিং সঠিক এবং আইন অনুযায়ী সম্পন্ন করা<br>
          • এজেন্টকে প্রয়োজনীয় নির্দেশনা, গাইডলাইন এবং আপডেট প্রদান<br>
          • গ্রাহকের ডাটা ও তথ্য নিরাপদ রাখা<br>
          • নির্ধারিত কমিশন এজেন্টকে সময়মতো প্রদান করা
        </p>
        <p>৪. কমিশন ও পেমেন্ট<br>
          প্রতি ভিসা/প্রসেস অনুযায়ী এজেন্ট কমিশন পাবেন: ______% / ______ টাকা।<br>
          ক্লায়েন্ট ফাইল কোম্পানিতে জমা দেওয়ার পর কমিশন নির্ধারিত হবে। ভিসা অনুমোদনের পর কমিশন পেমেন্ট প্রদান করা হবে।
        </p>
        <p>৫. গোপনীয়তা (Confidentiality)<br>
          এজেন্ট কোম্পানির কোনো গোপন তথ্য, গ্রাহক ডাটা বা ব্যবসায়িক কৌশল তৃতীয় পক্ষের কাছে প্রকাশ করতে পারবেন না।
        </p>
        <p>৬. নিষিদ্ধ কার্যকলাপ<br>
          • অন্য প্রতিদ্বন্দ্বী কোম্পানির হয়ে একই সেবা প্রদান<br>
          • ভিসা গ্যারান্টি দেওয়া<br>
          • অতিরিক্ত টাকা আদায় করা<br>
          • কোম্পানির নাম ব্যবহার করে প্রতারণা করা
        </p>
        <p>৭. চুক্তির মেয়াদ<br>
          এই চুক্তি ____ বছর/মাস পর্যন্ত কার্যকর থাকবে। উভয় পক্ষ নোটিশ প্রদান করে চুক্তি বাতিল করতে পারবেন।
        </p>
        <p>৮. আইনগত বিষয়<br>
          এই চুক্তি বাংলাদেশের প্রযোজ্য আইনের আওতায় পরিচালিত হবে। কোনো বিরোধ হলে আলোচনার মাধ্যমে সমাধান করা হবে।
        </p>
    </div>

    <!-- কন্টেন্ট ব্লক ৩ (নকল) - মাল্টিপেজ নিশ্চিত করার জন্য -->
    <div class="content-block">
        <h4>তৃতীয় সেকশনের চুক্তি বিবরণী (শুধুমাত্র ডেমো কন্টেন্টের জন্য)</h4>
        <p>১. চুক্তির উদ্দেশ্য<br>
          এই চুক্তির উদ্দেশ্য হলো পক্ষ–২ (এজেন্ট)কে কোম্পানির অনুমোদিত প্রতিনিধি হিসেবে কাজ করার সুযোগ প্রদান করা, যাতে তিনি গ্রাহক সংগ্রহ, ডকুমেন্ট সংগ্রহ এবং ভিসা–সম্পর্কিত সেবা প্রদানের ক্ষেত্রে কোম্পানিকে সহযোগিতা করতে পারেন।
        </p>
        <p>২. এজেন্টের দায়িত্ব<br>
          • গ্রাহক/ক্লায়েন্ট সংগ্রহ করা<br>
          • সঠিক ডকুমেন্ট সংগ্রহ করে কোম্পানিতে জমা দেওয়া<br>
          • গ্রাহকের সাথে ভিসা প্রসেস সম্পর্কে সঠিক তথ্য শেয়ার করা<br>
          • কোম্পানির নির্ধারিত ফি বা চার্জ গ্রাহকের কাছ থেকে সংগ্রহ করা<br>
          • মিথ্যা তথ্য বা ভুয়া ডকুমেন্ট জমা না দেওয়া
        </p>
        <p>৩. কোম্পানির দায়িত্ব<br>
          • গ্রাহকের ভিসা প্রসেসিং সঠিক এবং আইন অনুযায়ী সম্পন্ন করা<br>
          • এজেন্টকে প্রয়োজনীয় নির্দেশনা, গাইডলাইন এবং আপডেট প্রদান<br>
          • গ্রাহকের ডাটা ও তথ্য নিরাপদ রাখা<br>
          • নির্ধারিত কমিশন এজেন্টকে সময়মতো প্রদান করা
        </p>
        <p>৪. কমিশন ও পেমেন্ট<br>
          প্রতি ভিসা/প্রসেস অনুযায়ী এজেন্ট কমিশন পাবেন: ______% / ______ টাকা।<br>
          ক্লায়েন্ট ফাইল কোম্পানিতে জমা দেওয়ার পর কমিশন নির্ধারিত হবে। ভিসা অনুমোদনের পর কমিশন পেমেন্ট প্রদান করা হবে।
        </p>
        <p>৫. গোপনীয়তা (Confidentiality)<br>
          এজেন্ট কোম্পানির কোনো গোপন তথ্য, গ্রাহক ডাটা বা ব্যবসায়িক কৌশল তৃতীয় পক্ষের কাছে প্রকাশ করতে পারবেন না।
        </p>
        <p>৬. নিষিদ্ধ কার্যকলাপ<br>
          • অন্য প্রতিদ্বন্দ্বী কোম্পানির হয়ে একই সেবা প্রদান<br>
          • ভিসা গ্যারান্টি দেওয়া<br>
          • অতিরিক্ত টাকা আদায় করা<br>
          • কোম্পানির নাম ব্যবহার করে প্রতারণা করা
        </p>
        <p>৭. চুক্তির মেয়াদ<br>
          এই চুক্তি ____ বছর/মাস পর্যন্ত কার্যকর থাকবে। উভয় পক্ষ নোটিশ প্রদান করে চুক্তি বাতিল করতে পারবেন।
        </p>
        <p>৮. আইনগত বিষয়<br>
          এই চুক্তি বাংলাদেশের প্রযোজ্য আইনের আওতায় পরিচালিত হবে। কোনো বিরোধ হলে আলোচনার মাধ্যমে সমাধান করা হবে।
        </p>
        <div class="signs">
          <div class="sign-box">
            পক্ষ–১ (কোম্পানি):<br>
            স্বাক্ষর: __________________<br>
            নাম: ______________________<br>
            Designation: _______________
          </div>
          <div class="sign-box">
            পক্ষ–২ (এজেন্ট):<br>
            স্বাক্ষর: __________________<br>
            নাম: ______________________
          </div>
          <div class="sign-box">
            সাক্ষী–১: __________________<br>
            সাক্ষী–২: __________________
          </div>
        </div>
    </div>
    
  </div>
  <!-- FOOTER - এই অংশটি প্রিন্টে অদৃশ্য থাকবে -->
  <div class="letterfoot">
    <div class="band"></div>
    <div class="w1"></div>
    <div class="w2"></div>
    <div class="foot-text">
      <strong>Since 1998, Shahriar Group has been shaping industries with innovation, integrity, and global standards.</strong><br>
      Our journey is built on a foundation of trust, visionary leadership, and a relentless pursuit of excellence.
    </div>
  </div>
</div>
</body>
</html>