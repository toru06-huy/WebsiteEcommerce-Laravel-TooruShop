@extends('layouts.client')
@section('title', 'Vòng quay may mắn – VELOUR')

@push('styles')
<style>
.mg-page-wrap {
  max-width: 480px;
  margin: 60px auto;
  padding: 40px 32px;
  background: #fffdfb;
  border-radius: 24px;
  box-shadow: 0 20px 60px rgba(0,0,0,.08);
  text-align: center;
}
.mg-page-wrap h1 {
  font-family: var(--font-display, serif);
  font-size: 28px;
  font-weight: 500;
  margin-bottom: 6px;
  color: #231f20;
}
.mg-page-wrap p.mg-sub {
  font-size: 13px;
  color: #7a726f;
  margin-bottom: 28px;
}
</style>
@endpush

@section('content')
<div class="mg-page-wrap">
  <h1>🎁 Vòng quay may mắn</h1>
  <p class="mg-sub">Quay để nhận ngay mã giảm giá dành riêng cho bạn!</p>

  @include('client.minigame._wheel', [
      'segments' => $segments,
      'segCount' => $segCount ?? count($segments),
      'segAngle' => 360 / count($segments),
      'alreadyClaimedToday' => $alreadyClaimedToday,
      'pendingResult' => $pendingResult,
    'spunNoWinToday' => $spunNoWinToday,
  ])
</div>
@endsection