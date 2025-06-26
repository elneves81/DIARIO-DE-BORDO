@extends('errors::minimal')

@section('title', __('Método não permitido'))
@section('code', '405')
@section('message', 'Ação não permitida. Para sair do sistema, utilize o botão de logout no menu.')
