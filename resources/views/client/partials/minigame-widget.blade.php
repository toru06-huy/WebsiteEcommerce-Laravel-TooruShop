{{--
  MINIGAME WIDGET — Vòng quay may mắn
  ------------------------------------
  Include partial này 1 lần trong layouts.client (ngay trước </body>), ví dụ:
      @include('client.partials.minigame-widget')
  Nút sẽ nổi lơ lửng ở góc dưới bên phải trên mọi trang client.
--}}
@php
    $segments = \App\Http\Controllers\Client\MinigameController::segments();
    $segCount = count($segments);
    $segAngle = 360 / $segCount;
@endphp
@vite(['resources/css/client/minigame.css'])

<button type="button" class="minigame-float-btn" id="minigameFloatBtn" aria-label="Vòng quay may mắn">
  🎁
  <span class="mg-badge"></span>
</button>

<div class="minigame-overlay" id="minigameOverlay">
  <div class="minigame-modal">
    <button type="button" class="minigame-close" id="minigameCloseBtn">&times;</button>
    <h3>Vòng quay may mắn</h3>
    <p class="mg-sub">Quay để nhận ngay mã giảm giá dành riêng cho bạn!</p>

    @include('client.minigame._wheel', ['segments' => $segments, 'segCount' => $segCount, 'segAngle' => $segAngle])

  </div>
</div>

<script>
(function () {
  const openBtn   = document.getElementById('minigameFloatBtn');
  const closeBtn  = document.getElementById('minigameCloseBtn');
  const overlay   = document.getElementById('minigameOverlay');

  openBtn?.addEventListener('click', () => overlay.classList.add('active'));
  closeBtn?.addEventListener('click', () => overlay.classList.remove('active'));
  overlay?.addEventListener('click', (e) => { if (e.target === overlay) overlay.classList.remove('active'); });
})();
</script>