<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Halaman Daftar - MI Assalafiyah Batujajar</title>

    <style>
        * {
            box-sizing: border-box;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
        }

        .left {
            flex: 1;
            position: relative;
            overflow: hidden;
        }

        .left-bg {
            position: absolute;
            inset: 0;
            background: url("{{ asset('images/bg-sekolah.jpg') }}") center center / cover no-repeat;
            filter: brightness(0.85);
            opacity: .35;
        }

        .left-logo-wrapper {
            position: relative;
            z-index: 1;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .left-logo-wrapper img {
            max-width: 260px;
            width: 60%;
            height: auto;
        }

        .right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            background: linear-gradient(to bottom, #ffffff, #e6f8ec);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
        }

        .login-title {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-title h1 {
            margin: 0;
            font-size: 26px;
            color: #00933F;
            font-weight: 700;
        }

        .login-title h2 {
            margin: 4px 0 0;
            font-size: 18px;
            color: #1b5e20;
            font-weight: 600;
        }

        .form-label {
            font-weight: 600;
            color: #00933F;
            margin-bottom: 6px;
            display: block;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid #b7e1c5;
            margin-bottom: 18px;
            font-size: 14px;
            background-color: #e1f5e7;
        }

        .btn-primary {
            width: 100%;
            background-color: #00933F;
            border: none;
            padding: 12px;
            border-radius: 999px;
            font-weight: 600;
            color: #ffffff;
            font-size: 15px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #007a34;
        }

        .subtitle {
            margin-top: 10px;
            text-align: center;
            font-size: 13px;
        }

        .subtitle a {
            color: #00933F;
            font-weight: 600;
            text-decoration: none;
        }

        .alert {
            background-color: #ffe5e5;
            color: #d32f2f;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 13px;
        }

        @media (max-width: 900px) {
            body {
                flex-direction: column;
            }

            .left {
                height: 220px;
            }

            .left-logo-wrapper img {
                max-width: 180px;
                width: 40%;
            }
        }
    </style>
</head>
<body>
    <div class="left">
        <div class="left-bg"></div>
        <div class="left-logo-wrapper">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Assafund">
        </div>
    </div>

    <div class="right">
        <div class="login-card">
            <div class="login-title">
                <h1>MI Assalafiyah Batujajar</h1>
                <h2>Halaman Daftar</h2>
            </div>

            @if ($errors->any())
                <div class="alert">
                    <ul style="margin: 0; padding-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ url('/register') }}">
                @csrf

                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" required>

                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>

                <label for="user_group" class="form-label">Jabatan</label>
                <select id="user_group" name="user_group" class="form-control" required>
                    <option value="" disabled {{ old('user_group') ? '' : 'selected' }}>Pilih jabatan</option>

                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id_jabatan }}">
                            {{ $jabatan->nama_jabatan }}
                        </option>
                    @endforeach
                </select>

                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control" id="password" name="password" required>

                <button type="submit" class="btn-primary">Daftar</button>

                <div class="subtitle">
                    Sudah ada akun? <a href="{{ route('login') }}">Masuk</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
