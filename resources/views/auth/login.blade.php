<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ERP Jayatama</title>
</head>

<body
    style="font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div
        style="background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <h2 style="text-align: center; margin-bottom: 1.5rem;">ERP JAYATAMA</h2>

        @if($errors->any())
            <div style="background: #ffcccc; color: red; padding: 10px; border-radius: 4px; margin-bottom: 15px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label for="nip" style="display: block; margin-bottom: 5px;">NIP</label>
                <input type="text" name="nip" id="nip" value="{{ old('nip') }}" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label for="password" style="display: block; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" id="password" required
                    style="width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label>
                    <input type="checkbox" name="remember"> Ingat Saya
                </label>
            </div>

            <button type="submit"
                style="width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
                Login
            </button>
        </form>
    </div>
</body>

</html>