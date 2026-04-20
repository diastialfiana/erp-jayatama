@extends('layouts.app')
@section('title', 'Detail - Department Account')

@push('styles')
<style>
    :root { --b-pri: #1E3A8A; }
    .erp-container {
        background: #f3f3f3; font-size: 12px; min-height: calc(100vh - 62px);
        display: flex; flex-direction: column; color: #333;
    }
    .erp-header {
        display: flex; justify-content: space-between; align-items: center;
        padding: 8px 8px 0 8px; border-bottom: 1px solid #cfcfcf; background: #f3f3f3;
    }
    .erp-tabs { display: flex; gap: 2px; }
    .erp-tabs a { text-decoration: none; color: inherit; }
    .erp-tabs span, .erp-tabs a span {
        display: inline-block; padding: 6px 16px; background: #e2e8f0; color: #475569;
        font-size: 12px; font-weight: 600; text-transform: uppercase; cursor: pointer;
        border: 1px solid #cfcfcf; border-bottom: none; border-top-left-radius: 4px;
        border-top-right-radius: 4px; user-select: none;
    }
    .erp-tabs span.active { background: var(--b-pri); color: #fff; border-color: var(--b-pri); }
    .erp-tabs a:hover span:not(.active), .erp-tabs span:hover:not(.active) { background: #cbd5e1; }

    .erp-body {
        padding: 80px 20px; flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-start;
    }

    .erp-form { width: 500px; background: transparent; }

    .erp-form-table { width: 100%; border-spacing: 6px; }
    .erp-form-table td:first-child { width: 140px; text-align: right; padding-right: 15px; font-weight: bold; color: #444; }
    .erp-form-table input[type="text"] {
        width: 100%; padding: 5px 8px; border: 1px solid #ccc; font-size: 12px; background-color: #fff; outline: none; box-sizing: border-box;
    }
    .erp-form-table input[type="text"]:focus { border-color: var(--b-pri); box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.1); }

    .btn-save {
        padding: 6px 20px; background: var(--b-pri); border: 1px solid var(--b-pri);
        color: #fff; cursor: pointer; font-size: 12px; font-weight: bold; border-radius: 2px; margin-top: 15px;
    }
    .btn-save:hover { opacity: 0.9; }

    .erp-navigation {
        margin-top: 50px; font-size: 12px; color: #555; display: flex; gap: 8px; align-items: center; justify-content: center;
    }
    .nav-btn {
        background: transparent; border: 1px solid transparent; cursor: pointer; padding: 4px 10px; font-size: 13px; color: var(--b-pri); font-weight: bold; text-decoration: none; display: inline-block;
    }
    .nav-btn:hover { background: #e2e8f0; border-color: #ccc; border-radius: 2px; }
    .nav-btn.disabled { color: #aaa; cursor: default; pointer-events: none; }

    .error-text { color: red; font-size: 11px; margin-top: 2px; display: block; text-align: left; }
    .success-msg { color: green; font-weight: bold; text-align: center; margin-bottom: 25px; font-size: 13px;}
</style>
@endpush

@section('content')
<div class="erp-container">

    <div class="erp-header">
        <div class="erp-tabs">
            <span class="active">DETAIL</span>
            <a href="{{ route('accounting.dept-account.list') }}"><span>LIST ALL</span></a>
        </div>
    </div>

    <div class="erp-body">

        @if(session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif

        <div class="erp-form">
            <form action="{{ $dept ? route('accounting.dept-account.update', $dept->id) : route('accounting.dept-account.store') }}" method="POST">
                @csrf
                @if($dept) @method('PUT') @endif
                
                <table class="erp-form-table">
                    <tr>
                        <td>Code</td>
                        <td>
                            <input type="text" name="code" value="{{ old('code', $dept->code ?? '') }}" required>
                            @error('code') <span class="error-text">{{ $message }}</span> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td>Descriptions</td>
                        <td>
                            <input type="text" name="description" value="{{ old('description', $dept->description ?? '') }}">
                            @error('description') <span class="error-text">{{ $message }}</span> @enderror
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <button type="submit" class="btn-save">💾 Save</button>
                            @if($dept)
                            <a href="{{ route('accounting.dept-account.detail') }}" style="margin-left: 15px; font-size: 12px; color: var(--b-pri); text-decoration: none; font-weight: bold;">[+] Add New</a>
                            @endif
                        </td>
                    </tr>
                </table>
            </form>
        </div>

        <div class="erp-navigation">
            <a href="{{ $firstId ? route('accounting.dept-account.detail', $firstId) : '#' }}" class="nav-btn {{ !$firstId || ($dept && $firstId == $dept->id) ? 'disabled' : '' }}">&lt;&lt;</a>
            
            <a href="{{ $prevId ? route('accounting.dept-account.detail', $prevId) : '#' }}" class="nav-btn {{ !$prevId ? 'disabled' : '' }}">&lt;</a>
            
            <span style="font-style: italic; margin: 0 10px;">
                Record {{ $position }} of {{ $count }}
            </span>
            
            <a href="{{ $nextId ? route('accounting.dept-account.detail', $nextId) : '#' }}" class="nav-btn {{ !$nextId ? 'disabled' : '' }}">&gt;</a>
            
            <a href="{{ $lastId ? route('accounting.dept-account.detail', $lastId) : '#' }}" class="nav-btn {{ !$lastId || ($dept && $lastId == $dept->id) ? 'disabled' : '' }}">&gt;&gt;</a>
        </div>

    </div>

</div>
@endsection
