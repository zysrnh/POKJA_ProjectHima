@extends('errors.layout')

@section('code', '419')
@section('label', 'SESSION EXPIRED')
@section('badge_class', 'bg-amber-200 text-amber-950')
@section('box_bg', 'bg-amber-50')
@section('code_color', 'text-amber-800')
@section('title', 'Sesi Keamanan Kedaluwarsa')
@section('message', 'Halaman telah dibiarkan terlalu lama sehingga token keamanan (CSRF) kedaluwarsa. Silakan muat ulang halaman dan coba kembali.')
