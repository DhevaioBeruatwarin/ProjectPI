@extends('layouts.admin')

@section('content')
<h2 class="page-title">Monitoring Sistem</h2>

<div class="page-card">
    <table class="table admin-table">
        <tr><th>PHP Version</th><td>{{ $server['php_version'] }}</td></tr>
        <tr><th>Laravel Version</th><td>{{ $server['laravel_version'] }}</td></tr>
        <tr><th>Operating System</th><td>{{ $server['server_os'] }}</td></tr>
        <tr><th>Memory Usage</th><td>{{ $server['memory_usage'] }}</td></tr>
        <tr><th>Disk Free</th><td>{{ $server['disk_free'] }}</td></tr>
        <tr><th>Disk Total</th><td>{{ $server['disk_total'] }}</td></tr>
        <tr><th>Server Time</th><td>{{ $server['server_time'] }}</td></tr>
    </table>
</div>
@endsection
