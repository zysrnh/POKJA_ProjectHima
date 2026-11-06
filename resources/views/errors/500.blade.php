@extends('errors.layout')

@section('code', '500')
@section('label', 'INTERNAL SERVER ERROR')
@section('badge_class', 'bg-red-200 text-red-950')
@section('box_bg', 'bg-red-50')
@section('code_color', 'text-[#CA2C2A]')
@section('title', 'Terjadi Gangguan pada Server')
@section('message', 'Maaf, terjadi kendala teknis saat memproses permintaan Anda. Detail kesalahan telah dicatat secara otomatis oleh sistem untuk ditangani tim teknis.')
