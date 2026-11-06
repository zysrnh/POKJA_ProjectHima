@extends('errors.layout')

@section('code', '429')
@section('label', 'RATE LIMIT / ANTI-DDOS')
@section('badge_class', 'bg-red-200 text-red-950')
@section('box_bg', 'bg-red-50')
@section('code_color', 'text-[#CA2C2A]')
@section('title', 'Terlalu Banyak Permintaan')
@section('message', 'Sistem mendeteksi pengiriman permintaan beruntun yang melebihi batas wajar dari alamat IP Anda. Silakan tunggu sekitar 1 menit sebelum mencoba kembali.')
