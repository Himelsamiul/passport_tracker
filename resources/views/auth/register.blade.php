@extends('backend.master')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
{{-- ⚠️ Remove the extra Bootstrap include if backend.master already has it --}}
{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> --}}

<style>
  /* ===== Scoped ONLY to this page ===== */
  .register-scope {
    --primary: #054e1fff;
    --primary-light: #1f063dff;
    --secondary: #6C9BCF;
    --accent: #FF7E5F;
    --light: #F8F7FF;
    --text: #2D3748;
    --text-light: #718096;
    --form-bg: rgba(131, 138, 216, 0.83); /* your card bg */
    --header-h: 72px; /* adjust if your fixed header is taller/shorter */
  }

  /* Put the gradient on a wrapper, not body */
  .register-scope .page-bg {
    background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
    min-height: 100vh;
    padding: calc(var(--header-h) + 12px) 0 20px; /* push content below fixed header */
  }

  .register-scope .card-wrap {
    max-width: 720px;
    margin: 0 auto;
    animation: regFadeIn 0.8s ease-out;
  }

  .register-scope .card {
    border: none;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    background: var(--form-bg);
    backdrop-filter: blur(10px);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .register-scope .card:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,.15); }

  .register-scope .card-header {
    background: linear-gradient(120deg, var(--primary), var(--secondary));
    color: #fff;
    border-radius: 0;
    padding: 20px 30px;
    position: relative;
    overflow: hidden;
  }
  .register-scope .card-header::before{
    content:""; position:absolute; top:-50%; left:-50%; width:200%; height:200%;
    background:linear-gradient(45deg, transparent, rgba(255,255,255,.1), transparent);
    transform:rotate(45deg); animation: regShimmer 8s infinite linear;
  }

  .register-scope .card-body{ padding:2.5rem; }

  .register-scope .form-control, 
  .register-scope .form-select{
    height:50px;border-radius:12px;border:1px solid #e2e8f0;padding:0 15px;
    transition:all .3s ease;background-color:rgba(255,255,255,.8);
  }
  .register-scope .form-control:focus, 
  .register-scope .form-select:focus{
    border-color:var(--primary-light);
    box-shadow:0 0 0 3px rgba(139,95,191,.2);
    background-color:#fff;
  }

  .register-scope .form-label{font-weight:600;color:var(--text);margin-bottom:10px;font-size:.95rem;}

  .register-scope .password-container{ position:relative; }
  .register-scope .password-toggle{
    position:absolute; right:15px; top:50%; transform:translateY(-50%);
    background:none; border:none; color:var(--text-light); cursor:pointer; transition:color .3s ease;
  }
  .register-scope .password-toggle:hover{ color:var(--primary); }

  .register-scope .form-row{ display:flex; flex-wrap:wrap; margin:0 -12px; }
  .register-scope .form-col{ flex:1 0 50%; padding:0 12px; margin-bottom:24px; }

  .register-scope .btn-primary{
    background:linear-gradient(120deg, var(--primary), var(--primary-light));
    border:none; padding:12px 30px; border-radius:12px; font-weight:600; transition:all .3s ease;
    box-shadow:0 4px 15px rgba(139,95,191,.3);
  }
  .register-scope .btn-primary:hover{ transform:translateY(-2px); box-shadow:0 6px 20px rgba(139,95,191,.4); }
  .register-scope .btn-secondary{
    background:linear-gradient(120deg, #f41010ff, #718096);
    border:none; padding:12px 30px; border-radius:12px; font-weight:600; transition:all .3s ease;
  }
  .register-scope .btn-secondary:hover{ transform:translateY(-2px); box-shadow:0 4px 15px rgba(44, 88, 154, 0.3); }

  .register-scope .alert{ border-radius:12px; padding:16px 20px; border:none; box-shadow:0 4px 12px rgba(0,0,0,.05); }
  .register-scope .alert-success{ background:linear-gradient(120deg, #48BB78, #38A169); color:#fff; }
  .register-scope .alert-danger{ background:linear-gradient(120deg, #F56565, #E53E3E); color:#fff; }

  .register-scope .floating-icon{ position:absolute; opacity:.05; font-size:120px; z-index:0; }
  .register-scope .icon-1{ top:-30px; right:-30px; color:var(--primary); }
  .register-scope .icon-2{ bottom:-40px; left:-40px; color:var(--secondary); transform:rotate(45deg); }

  .register-scope .form-icon{
    position:absolute; left:15px; top:50%; transform:translateY(-50%); color:var(--text-light); z-index:5;
  }
  .register-scope .input-with-icon{ padding-left:45px !important; }

  @keyframes regFadeIn{ from{opacity:0; transform:translateY(20px)} to{opacity:1; transform:translateY(0)} }
  @keyframes regShimmer{ 0%{transform:translateX(-100%) translateY(-100%) rotate(45deg)} 100%{transform:translateX(100%) translateY(100%) rotate(45deg)} }
  @keyframes regPulse{ 0%{transform:scale(1)} 50%{transform:scale(1.05)} 100%{transform:scale(1)} }
  .register-scope .pulse{ animation: regPulse 2s infinite; }

  @media (max-width: 768px){
    .register-scope .form-col{ flex:1 0 100%; }
    .register-scope .card-body{ padding:1.5rem; }
  }
</style>

<div class="register-scope">
  <div class="page-bg">
    <div class="card-wrap">
      <div class="card">
        <div class="card-header p-3 d-flex align-items-center justify-content-between position-relative">
          <h5 class="m-0 fw-bold position-relative"><i class="fas fa-user-plus me-2"></i>Create Registration</h5>
          <a href="{{ route('register.list') }}" class="btn btn-light btn-sm position-relative">
            <i class="fas fa-list me-1"></i>View List
          </a>
          <i class="fas fa-user-circle floating-icon icon-1"></i>
          <i class="fas fa-id-card floating-icon icon-2"></i>
        </div>

        <div class="card-body position-relative">
          @if(session('success'))
            <div class="alert alert-success d-flex align-items-center">
              <i class="fas fa-check-circle me-2"></i>
              <div>{{ session('success') }}</div>
            </div>
          @endif
          @if($errors->any())
            <div class="alert alert-danger">
              <div class="d-flex align-items-center mb-2">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Please fix the following errors:</strong>
              </div>
              <ul class="m-0 ps-3">
                @foreach($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form action="{{ route('register.store') }}" method="POST">
            @csrf
            <div class="form-row">
              <div class="form-col">
                <label class="form-label">Full Name</label>
                <div class="position-relative">
                  <i class="fas fa-user form-icon"></i>
                  <select name="name" class="form-select input-with-icon" required>
                    <option value="">Select Employee</option>
                    @foreach($employees as $emp)
                      <option value="{{ $emp->name }}" {{ old('name') == $emp->name ? 'selected' : '' }}>
                        {{ $emp->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="form-col">
                <label class="form-label">Email</label>
                <div class="position-relative">
                  <i class="fas fa-envelope form-icon"></i>
                  <input type="email" name="email" class="form-control input-with-icon" value="{{ old('email') }}" placeholder="Enter email address" required>
                </div>
              </div>

              <div class="form-col">
                <label class="form-label">Password</label>
                <div class="password-container position-relative">
                  <i class="fas fa-lock form-icon"></i>
                  <input type="password" name="password" id="password" class="form-control input-with-icon" placeholder="Create a password" required>
                  <button type="button" class="password-toggle" id="togglePassword"><i class="fas fa-eye"></i></button>
                </div>
              </div>

              <div class="form-col">
                <label class="form-label">Confirm Password</label>
                <div class="password-container position-relative">
                  <i class="fas fa-lock form-icon"></i>
                  <input type="password" name="password_confirmation" id="confirmPassword" class="form-control input-with-icon" placeholder="Confirm your password" required>
                  <button type="button" class="password-toggle" id="toggleConfirmPassword"><i class="fas fa-eye"></i></button>
                </div>
              </div>

              <div class="form-col">
                <label class="form-label">Status</label>
                <div class="position-relative">
                  <i class="fas fa-toggle-on form-icon"></i>
                  <select name="status" class="form-select input-with-icon" required>
                    <option value="active" {{ old('status','active')==='active'?'selected':'' }}>Active</option>
                    <option value="inactive" {{ old('status')==='inactive'?'selected':'' }}>Inactive</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="text-end mt-4">
              <button class="btn btn-primary px-4 pulse" type="submit">
                <i class="fas fa-save me-1"></i>Save Registration
              </button>
              <a href="{{ route('dashboard') }}" class="btn btn-secondary px-4 ms-2">
                <i class="fas fa-times me-1"></i>Cancel
              </a>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');
  const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
  const confirmPassword = document.getElementById('confirmPassword');

  togglePassword.addEventListener('click', function() {
    const type = password.type === 'password' ? 'text' : 'password';
    password.type = type;
    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
  });

  toggleConfirmPassword.addEventListener('click', function() {
    const type = confirmPassword.type === 'password' ? 'text' : 'password';
    confirmPassword.type = type;
    this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
  });
});
</script>
@endsection
