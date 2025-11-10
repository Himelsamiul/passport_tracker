@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  /* ===== Scoped Styling for Employee Form ===== */
  .employee-scope{
    --primary:#054e1f;
    --primary-light:#1f063d;
    --secondary:#6C9BCF;
    --text:#2D3748;
    --text-light:#718096;
    --form-bg:rgba(131,138,216,.83);
    --header-h:72px;

    --field-h:52px;       /* input height */
    --radius:12px;        /* corners */
    --gap:10px;           /* gap between icon and input text */
    --padX:14px;          /* horizontal padding inside input */
    --icon-size:18px;     /* icon font-size */
  }

  .employee-scope .page-bg{
    background:linear-gradient(135deg,#f5f7fa 0%,#e4edf5 100%);
    min-height:100vh;
    padding:calc(var(--header-h) + 12px) 0 20px;
  }

  .employee-scope .card-wrap{max-width:720px;margin:0 auto;animation:fade .6s ease}
  .employee-scope .card{
    border:none;border-radius:20px;background:var(--form-bg);backdrop-filter:blur(10px);
    box-shadow:0 15px 35px rgba(0,0,0,.1);overflow:hidden
  }
  .employee-scope .card-header{
    background:linear-gradient(120deg,var(--primary),var(--secondary));color:#fff;padding:20px 30px
  }
  .employee-scope .card-body{padding:2.5rem}

  .employee-scope .form-group{margin-bottom:22px}
  .employee-scope .form-label{font-weight:700;color:var(--text);margin-bottom:8px}
  .employee-scope .form-label.required::after{content:" *";color:#e11d48}

  /* === FLEX INPUT: icon + input perfectly centered === */
  .employee-scope .input-wrap{
    display:flex; align-items:center; height:var(--field-h);
    border:1px solid #e2e8f0; border-radius:var(--radius);
    padding:0 var(--padX); background:rgba(255,255,255,.95);
    transition:box-shadow .2s ease, border-color .2s ease, background .2s ease;
  }
  .employee-scope .input-wrap:focus-within{
    border-color:var(--primary-light);
    box-shadow:0 0 0 3px rgba(139,95,191,.2);
    background:#fff;
  }

  .employee-scope .input-icon{
    font-size:var(--icon-size); color:var(--text-light); margin-right:var(--gap); flex:0 0 auto;
    line-height:1; display:flex; align-items:center; justify-content:center;
  }

  .employee-scope .input-control{
    border:none; outline:none; background:transparent;
    height:100%; width:100%; flex:1 1 auto; padding:0; font-size:16px; color:var(--text);
  }
  .employee-scope .input-control::placeholder{color:#9aa5b1}

  /* buttons */
  .employee-scope .btn-primary{
    background:linear-gradient(120deg,var(--primary),var(--primary-light));
    border:none;border-radius:12px;padding:12px 28px;font-weight:700;
    box-shadow:0 4px 15px rgba(139,95,191,.3)
  }
  .employee-scope .btn-primary:hover{transform:translateY(-1px)}
  .employee-scope .btn-secondary{
    background:linear-gradient(120deg,#f41010,#718096);border:none;border-radius:12px;padding:12px 28px;font-weight:700
  }

  @keyframes fade{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
  @media (max-width:768px){.employee-scope .card-body{padding:1.5rem}}
</style>

<div class="employee-scope">
  <div class="page-bg">
    <div class="card-wrap">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="m-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Add New Employee</h5>
          <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-list me-1"></i>Employee List
          </a>
        </div>

        <div class="card-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <strong>Please fix the following errors:</strong>
              <ul class="mt-2 mb-0 ps-3">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('employees.store') }}" method="POST">
            @csrf

            <!-- Name (required) -->
            <div class="form-group">
              <label class="form-label required">Name</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-user"></i></span>
                <input type="text" name="name" class="input-control" placeholder="Enter full name" required>
              </div>
            </div>

            <!-- Designation (optional) -->
            <div class="form-group">
              <label class="form-label">Designation</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-briefcase"></i></span>
                <input type="text" name="designation" class="input-control" placeholder="Enter designation">
              </div>
            </div>

            <!-- Phone (required) -->
            <div class="form-group">
              <label class="form-label required">Phone</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-phone"></i></span>
                <input type="text" name="phone" class="input-control" placeholder="Enter phone number" required>
              </div>
            </div>

            <!-- Email (required) -->
            <div class="form-group">
              <label class="form-label required">Email</label>
              <div class="input-wrap">
                <span class="input-icon"><i class="fa-solid fa-envelope"></i></span>
                <input type="email" name="email" class="input-control" placeholder="Enter email address" required>
              </div>
            </div>

            <div class="text-end mt-4">
              <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i>Save
              </button>
              <a href="{{ route('employees.index') }}" class="btn btn-secondary px-4 ms-2">
                <i class="fas fa-times me-1"></i>Cancel
              </a>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
