<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — mygympro Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            background: #f0f2f7;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-wrapper {
            width: 100%;
            max-width: 420px;
            padding: 24px;
        }
        .login-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #e53935 0%, #c62828 100%);
            padding: 32px 32px 28px;
            text-align: center;
        }
        .login-header .brand-icon {
            width: 52px; height: 52px;
            background: rgba(255,255,255,.2);
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            color: white;
            font-size: 1.6rem;
        }
        .login-header h1 {
            color: white;
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 4px;
        }
        .login-header p {
            color: rgba(255,255,255,.75);
            font-size: .82rem;
            margin: 0;
        }
        .login-body { padding: 32px; }
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #344054;
            margin-bottom: 6px;
        }
        .input-group .input-icon {
            background: #f9fafb;
            border: 1.5px solid #d0d5dd;
            border-right: none;
            color: #667085;
            padding: 0 14px;
            display: flex; align-items: center;
            border-radius: 8px 0 0 8px;
        }
        .form-control {
            border: 1.5px solid #d0d5dd;
            border-radius: 0 8px 8px 0;
            padding: 11px 14px;
            font-size: .875rem;
            transition: border-color .15s, box-shadow .15s;
        }
        .form-control:focus {
            border-color: #e53935;
            box-shadow: 0 0 0 3px rgba(229,57,53,.1);
            outline: none;
        }
        .btn-submit {
            width: 100%;
            background: #e53935;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 10px;
            font-size: .9rem;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s, transform .1s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover { background: #c62828; transform: translateY(-1px); }
        .alert {
            border: none;
            border-radius: 10px;
            font-size: .82rem;
            padding: 12px 16px;
            background: #fef3f2;
            color: #b42318;
            margin-bottom: 20px;
        }
        .toggle-password {
            background: #f9fafb;
            border: 1.5px solid #d0d5dd;
            border-left: none;
            color: #667085;
            padding: 0 12px;
            cursor: pointer;
            border-radius: 0 8px 8px 0;
            display: flex; align-items: center;
        }
        .toggle-password:hover { color: #344054; }
        .login-footer {
            padding: 16px 32px 24px;
            text-align: center;
        }
        .login-footer a {
            font-size: .8rem;
            color: #667085;
            text-decoration: none;
        }
        .login-footer a:hover { color: #e53935; }
    </style>
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <div class="login-header">
            <div class="brand-icon"><i class='bx bxs-dumbbell'></i></div>
            <h1>mygympro</h1>
            <p>Accès administration</p>
        </div>
        <div class="login-body">
            @if($errors->any())
                <div class="alert">
                    <i class='bx bx-error-circle me-2'></i>
                    {{ $errors->first() }}
                </div>
            @endif
            @if(session('status'))
                <div class="alert" style="background:#ecfdf3;color:#027a48">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Adresse email</label>
                    <div class="input-group">
                        <span class="input-icon"><i class='bx bx-envelope'></i></span>
                        <input type="email" name="email" class="form-control" placeholder="admin@mygympro.ma"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-icon"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="••••••••" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class='bx bx-show' id="eye-icon"></i>
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-submit">
                    <i class='bx bx-log-in'></i> Se connecter
                </button>
            </form>
        </div>
        <div class="login-footer">
            <a href="{{ route('admin.register') }}">Créer un compte administrateur</a>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('eye-icon');
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    icon.className = isText ? 'bx bx-show' : 'bx bx-hide';
}
</script>
</body>
</html>
