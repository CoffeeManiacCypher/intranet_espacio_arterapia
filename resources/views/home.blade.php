@extends('layouts.home')

@section('title', 'Inicio')

@section('content')
<div class="home-container">
    <!-- Título principal -->
    <h1 class="home-title">¿Qué herramienta deseas usar hoy?</h1>

    <div class="swiper-container-wrapper">
        <div class="gradient-left"></div>
        <div class="gradient-right"></div>
        <!-- Swiper Container -->
        <div class="swiper-container">

            <div class="swiper-wrapper">

                <!-- Módulo Pacientes -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Registro de pacientes: Administra tus pacientes y sus datos."
                        data-icon="bi bi-person-circle" data-bg-color="#e2efee">
                        <a href="{{ route('pacientes.index') }}">
                            <img src="{{ asset('img/icons/paciente.jpg') }}" alt="Pacientes">
                        </a>
                    </div>
                </div>
                <!-- Módulo Pacientes -->

                <!-- Módulo Giftcards -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Editor de Giftcard: Gestiona giftcards y sus configuraciones."
                        data-icon="bi bi-card-text" data-bg-color="#f9f5ed">
                        <a href="{{ route('giftcards.index') }}">
                            <img src="{{ asset('img/icons/giftcard.jpg') }}" alt="Giftcards">
                        </a>
                    </div>
                </div>

                <!-- Módulo Pago QR -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Gestor de finanzas: Procesa pagos rápidos mediante códigos QR."
                        data-icon="bi bi-qr-code" data-bg-color="#e0e1f7">
                        <a href="{{ route('pagoqr.index') }}">
                            <img src="{{ asset('img/icons/pagoqr.jpg') }}" alt="Pago QR">
                        </a>
                    </div>
                </div>

                <!-- Módulo Valoraciones -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Valoraciones: Consulta y administra las valoraciones de tus servicios."
                        data-icon="bi bi-star" data-bg-color="#ffe8ef">
                        <a href="{{ route('valoraciones.index') }}">
                            <img src="{{ asset('img/icons/valoracion.jpg') }}" alt="Valoraciones">
                        </a>
                    </div>
                </div>

                <!-- Módulo Servicios -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Servicios: Accede a la gestión de tus servicios."
                        data-icon="bi bi-briefcase" data-bg-color="#ded0e6">
                        <a href="{{ route('servicios.index') }}">
                            <img src="{{ asset('img/icons/servicios.jpg') }}" alt="Servicios">
                        </a>
                    </div>
                </div>

                <!-- Módulo Servicios -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Proximamente: "
                        data-icon="" data-bg-color="#e3e3e3">
                        <a href="#">
                            <img src="{{ asset('img/icons/default.jpg') }}" alt="proximamente">
                        </a>
                    </div>
                </div>

                <!-- Módulo Servicios -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Proximamente: "
                        data-icon="" data-bg-color="#e3e3e3">
                        <a href="#">
                            <img src="{{ asset('img/icons/default.jpg') }}" alt="proximamente">
                        </a>
                    </div>
                </div>

                <!-- Módulo Servicios -->
                <div class="swiper-slide">
                    <div class="icon-bubble" data-description="Proximamente: "
                        data-icon="" data-bg-color="#e3e3e3">
                        <a href="#">
                            <img src="{{ asset('img/icons/default.jpg') }}" alt="proximamente">
                        </a>
                    </div>
                </div>

              

            </div>

            <!-- Swiper Controls -->
            <div class="slider-control">
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-pagination"></div>
            </div>

        </div>

        <!-- Contenedor de la descripción -->
        <div class="module-description hide">
            <div class="description-header">
                <i class="bi"></i>
                <span class="description-title"></span>
            </div>
            <div class="description-content"></div>
        </div>

    </div>
</div>
@endsection
