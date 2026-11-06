@extends('errors.layout')

@section('code', '403')
@section('label', 'FORBIDDEN / PROTECTED')
@section('badge_class', 'bg-red-200 text-red-950')
@section('box_bg', 'bg-red-50')
@section('code_color', 'text-[#CA2C2A]')
@section('title', 'Akses Ditolak oleh Sistem Keamanan')
@section('message', !empty($exception?->getMessage()) ? $exception->getMessage() : 'Maaf, Anda tidak memiliki izin untuk mengakses halaman ini atau sistem mendeteksi aktivitas yang tidak diizinkan.')

