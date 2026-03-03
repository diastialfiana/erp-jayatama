<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Jayatama - @yield('title', 'Dashboard')</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .wrapper {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background: #343a40;
            color: #fff;
            display: flex;
            flex-direction: column;
            transition: width 0.3s;
        }

        .content-area {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .top-navbar {
            height: 60px;
            background: #ffffff;
            border-bottom: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            overflow-y: auto;
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 4px;
        }

        .dropdown-content a,
        .dropdown-content form button {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            width: 100%;
            text-align: left;
            border: none;
            background: none;
            cursor: pointer;
        }

        .dropdown-content a:hover,
        .dropdown-content form button:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>

    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Content Area (Navbar + Main) -->
        <div class="content-area">
            <!-- Navbar -->
            @include('layouts.navbar')

            <!-- Main Content -->
            <div class="main-content">
                @if(session('success'))
                    <div
                        style="background: #e2e3e5; color: #383d41; padding: 15px; margin-bottom: 20px; border-radius: 4px;">
                        {{ session('success') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

</body>

</html>