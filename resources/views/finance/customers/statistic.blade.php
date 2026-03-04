@extends('layouts.app')
@section('title', 'Customer Statistic')
@section('content')
    <div class="bg-white rounded-[14px] shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Customer Statistic</h2>
        <p class="text-gray-500">Statistic feature is coming soon.</p>
        <a href="{{ route('finance.customers.detail.show', $customer->id) }}"
            class="text-blue-600 hover:underline mt-4 inline-block">Back to Detail</a>
    </div>
@endsection