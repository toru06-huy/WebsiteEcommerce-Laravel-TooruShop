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

<style>
/* ── FLOATING BUTTON ── */
.minigame-float-btn {
  position: fixed;
  right: 24px;
  bottom: 24px;
  z-index: 999;
  width: 64px;
  height: 64px;
  border-radius: 50%;
  border: none;
  cursor: pointer;
  background: linear-gradient(135deg, var(--gold, #c9a84c) 0%, #a08233 100%);
  box-shadow: 0 10px 30px rgba(201,168,76,.45);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: #fff;
  animation: minigameFloat 2.4s ease-in-out infinite;
}
.minigame-float-btn:hover { box-shadow: 0 14px 36px rgba(201,168,76,.6); }
@keyframes minigameFloat {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-8px); }
}
.minigame-float-btn .mg-badge {
  position: absolute;
  top: -4px; right: -4px;
  width: 18px; height: 18px;
  border-radius: 50%;
  background: #e14b4b;
  border: 2px solid #fff;
}

/* ── MODAL OVERLAY ── */
.minigame-overlay {
  position: fixed; inset: 0;
  background: rgba(20,16,14,.6);
  backdrop-filter: blur(4px);
  z-index: 1000;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 20px;
}
.minigame-overlay.active { display: flex; }
.minigame-modal {
  background: #fffdfb;
  border-radius: 24px;
  width: min(480px, 100%);
  max-height: 92vh;
  overflow-y: auto;
  position: relative;
  padding: 32px 28px 28px;
  text-align: center;
  box-shadow: 0 30px 80px rgba(0,0,0,.35);
}
.minigame-close {
  position: absolute;
  top: 16px; right: 16px;
  width: 34px; height: 34px;
  border-radius: 50%;
  border: none;
  background: #f2ece4;
  cursor: pointer;
  font-size: 16px;
  color: #4a4243;
}
.minigame-modal h3 {
  font-family: var(--font-display, serif);
  font-size: 24px;
  font-weight: 500;
  margin-bottom: 4px;
  color: #231f20;
}
.minigame-modal p.mg-sub {
  font-size: 13px;
  color: #7a726f;
  margin-bottom: 20px;
}

/* ── WHEEL ── */
.mg-wheel-wrap {
  position: relative;
  width: 280px;
  height: 280px;
  margin: 0 auto 24px;
}
.mg-pointer {
  position: absolute;
  top: -6px; left: 50%;
  transform: translateX(-50%);
  width: 0; height: 0;
  border-left: 14px solid transparent;
  border-right: 14px solid transparent;
  border-top: 24px solid #231f20;
  z-index: 5;
  filter: drop-shadow(0 2px 3px rgba(0,0,0,.3));
}
.mg-wheel {
  width: 100%; height: 100%;
  border-radius: 50%;
  position: relative;
  border: 6px solid #fff;
  box-shadow: 0 8px 30px rgba(0,0,0,.25), inset 0 0 0 2px rgba(0,0,0,.06);
  transition: transform 4.2s cubic-bezier(.17,.67,.16,1);
  overflow: hidden;
}
/* Nhãn chữ: mỗi nhãn là 1 "kim" từ tâm ra mép, xoay đúng góc giữa ô rồi lùi ra gần mép */
.mg-seg-label {
  position: absolute;
  top: 50%; left: 50%;
  width: 0; height: 0;
  transform-origin: 0 0;
}
.mg-seg-label span {
  position: absolute;
  top: 18px; 
  left: -40px;
  width: 80px;
  display: block;
  font-size: 10.5px;
  font-weight: 600;
  color: #231f20;
  text-shadow: 0 1px 2px rgba(255,255,255,.5);
  text-align: center;
  line-height: 1.25;
  /* ĐẢM BẢO CÓ 2 DÒNG NÀY ĐỂ CHỮ XOAY TẠI CHỖ KHÔNG BỊ NHẢY LÊN XUỐNG */
  transform-origin: center center; 
  box-sizing: border-box;
}
.mg-hub {
  position: absolute;
  top: 50%; left: 50%;
  width: 56px; height: 56px;
  transform: translate(-50%, -50%);
  border-radius: 50%;
  background: #fff;
  box-shadow: 0 4px 14px rgba(0,0,0,.25);
  display: flex; align-items: center; justify-content: center;
  z-index: 4;
}

.mg-spin-btn {
  width: 100%;
  padding: 14px;
  border-radius: 999px;
  border: none;
  background: var(--gold, #c9a84c);
  color: #fff;
  font-size: 13px;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  cursor: pointer;
  transition: opacity .2s;
}
.mg-spin-btn:disabled { opacity: .5; cursor: not-allowed; }

.mg-limit-note {
  margin-top: 14px;
  font-size: 12.5px;
  color: #a08233;
  background: #fbf3e0;
  border-radius: 10px;
  padding: 10px 14px;
}

/* ── RESULT PANEL ── */
.mg-result {
  margin-top: 20px;
  display: none;
  border: 1.5px dashed #a08233;
  border-radius: 16px;
  padding: 20px;
  background: #fbf6ee;
}
.mg-result.active { display: block; }
.mg-result-code {
  font-family: var(--font-display, serif);
  font-size: 26px;
  font-weight: 600;
  letter-spacing: 2px;
  color: #a08233;
  margin-bottom: 6px;
}
.mg-result-label { font-size: 14px; color: #4a4243; margin-bottom: 16px; }
.mg-claim-btn {
  width: 100%;
  padding: 13px;
  border-radius: 999px;
  border: none;
  background: #231f20;
  color: #fff;
  font-size: 13px;
  letter-spacing: 1.2px;
  text-transform: uppercase;
  cursor: pointer;
}
.mg-claim-btn:disabled { opacity: .5; cursor: not-allowed; }
.mg-msg { margin-top: 12px; font-size: 13px; }
.mg-msg.success { color: #2e7d32; }
.mg-msg.error { color: #c0392b; }
</style>

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