{{--
  Markup vòng quay dùng chung. Biến truyền vào:
  - $segments : mảng cấu hình các ô (App\Http\Controllers\Client\MinigameController::segments())
  - $segCount : số ô
  - $segAngle : góc mỗi ô (360 / $segCount)
  - $alreadyClaimedToday : đã nhận mã hôm nay chưa (đã ghi DB)
  - $pendingResult : kết quả trúng thưởng hôm nay nhưng chưa lấy mã (mảng hoặc null)
  - $spunNoWinToday : đã quay hôm nay nhưng không trúng (chưa được quay lại)
--}}
@php

  $stops = [];
  foreach ($segments as $i => $seg) {
      $from = round($i * $segAngle, 4);
      $to   = round(($i + 1) * $segAngle, 4);
      $stops[] = "{$seg['color']} {$from}deg {$to}deg";
  }
  $conic = 'conic-gradient(' . implode(', ', $stops) . ')';

  // Chọn màu chữ tương phản (trắng hoặc đen) dựa trên độ sáng của màu nền từng ô
  $contrastColor = function (string $hex): string {
      $hex = ltrim($hex, '#');
      if (strlen($hex) === 3) {
          $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
      }
      [$r, $g, $b] = array_map(fn ($h) => hexdec($h) / 255, str_split($hex, 2));
      $lum = 0.299 * $r + 0.587 * $g + 0.114 * $b;
      return $lum > 0.6 ? '#231f20' : '#fff';
  };
@endphp
<div class="mg-wheel-wrap">
  <div class="mg-pointer"></div>
  <div class="mg-wheel" id="mgWheel-{{ $__mgUid ?? '' }}" style="background: {{ $conic }};">
    @foreach($segments as $i => $seg)
      @php
        $center = $segAngle * $i + $segAngle / 2;
        $rad = deg2rad($center);
        // Điểm neo đặt gần mép ngoài của ô (0° = trên cùng, xuôi theo chiều kim đồng hồ)
        $outerRadius = 126;
        $px = round(sin($rad) * $outerRadius, 2);
        $py = round(-cos($rad) * $outerRadius, 2);
        // Xoay đúng góc để chữ đọc theo hướng từ mép ngoài -> tâm vòng quay,
        // xoay thêm 90° theo chiều phải sang trái (ngược kim đồng hồ) theo yêu cầu
        $rotate = round($center, 4);
        $color = $contrastColor($seg['color']);
      @endphp
      <div class="mg-seg-label"
           style="left: calc(50% + {{ $px }}px); top: calc(50% + {{ $py }}px); transform: rotate({{ $rotate }}deg); color: {{ $color }};">
        <span>{{ $seg['label'] }}</span>
      </div>
    @endforeach
  </div>
  <div class="mg-hub">🎯</div>
</div>

@if(($alreadyClaimedToday ?? false))
  <div class="mg-limit-note">Bạn đã nhận mã giảm giá hôm nay rồi. Hãy quay lại vào ngày mai để nhận thêm nhé! 🌟</div>
@elseif(($spunNoWinToday ?? false))
  <div class="mg-limit-note">Bạn đã dùng lượt quay hôm nay rồi. Hãy quay lại vào ngày mai để có thêm lượt nhé! 🌟</div>
@elseif(!($pendingResult ?? false))
  {{-- Chưa quay lần nào hôm nay -> mới có nút Quay ngay. Khi đã có pendingResult
       (đã quay trúng, chưa lấy mã) thì nút này không được render ra DOM luôn,
       không chỉ ẩn bằng CSS, để chắc chắn không thể bấm/quay lại được. --}}
  <button type="button" class="mg-spin-btn" id="mgSpinBtn">Quay ngay</button>
@endif

{{-- Khối kết quả: nếu đã có pendingResult (quay trúng hôm nay, chưa lấy mã),
     in thẳng mã + nhãn ra HTML ngay từ server và bật sẵn class "active",
     không phụ thuộc JS để tránh trường hợp JS không chạy/không khớp phần tử. --}}
<div class="mg-result @if($pendingResult ?? false) active @endif" id="mgResult">
  <div class="mg-result-code" id="mgResultCode">{{ $pendingResult['code'] ?? '' }}</div>
  <div class="mg-result-label" id="mgResultLabel">{{ $pendingResult['label'] ?? '' }}</div>
  <button type="button" class="mg-claim-btn" id="mgClaimBtn"
          @if(!($pendingResult ?? false)) style="display:none;" @endif>Lấy mã</button>
  <div class="mg-msg" id="mgMsg"></div>
</div>

<script>
(function () {
  const segments  = @json($segments);
  const segCount  = segments.length;
  const segAngle  = 360 / segCount;

  // Nếu có nhiều vòng quay trên cùng trang (widget nổi có thể tồn tại song song
  // với trang /minigame), lấy phần tử gần nút bấm cuối cùng được thao tác.
  function initWheelBlock(root) {
    const wheel     = root.querySelector('.mg-wheel');
    const spinBtn   = root.querySelector('.mg-spin-btn'); // có thể không tồn tại trong DOM
    const result    = root.querySelector('.mg-result');
    const resultCode  = root.querySelector('.mg-result-code');
    const resultLabel = root.querySelector('.mg-result-label');
    const claimBtn  = root.querySelector('.mg-claim-btn');
    const msg       = root.querySelector('.mg-msg');
    if (!wheel) return;

    let currentRotation = 0;
    let spinning = false;

    // claimBtn luôn có mặt trong DOM (server render sẵn, chỉ ẩn/hiện bằng CSS),
    // nên luôn gắn sự kiện cho nó — dùng chung cho cả trường hợp vừa quay xong
    // (F5 rồi bấm) lẫn trường hợp vừa quay xong trong cùng lần tải trang.
    claimBtn?.addEventListener('click', function () {
      claimBtn.disabled = true;
      msg.textContent = '';
      msg.className = 'mg-msg';

      fetch('{{ route('client.minigame.claim') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          msg.textContent = data.message;
          msg.className = 'mg-msg success';
          claimBtn.style.display = 'none';
          if (spinBtn) spinBtn.style.display = 'none';
          setTimeout(() => { window.location.href = data.redirect; }, 1500);
          return;
        }

        if (data.requireLogin) {
          msg.textContent = data.message;
          msg.className = 'mg-msg error';
          result.classList.add('active');
          setTimeout(() => { window.location.href = data.loginUrl; }, 1600);
          return;
        }

        msg.textContent = data.message || 'Có lỗi xảy ra, vui lòng thử lại.';
        msg.className = 'mg-msg error';
        claimBtn.disabled = false;

        if (data.alreadyClaimed) {
          claimBtn.style.display = 'none';
          if (spinBtn) spinBtn.style.display = 'none';
        }
      })
      .catch(() => {
        claimBtn.disabled = false;
        msg.textContent = 'Có lỗi xảy ra, vui lòng thử lại.';
        msg.className = 'mg-msg error';
      });
    });

    // Nút "Quay ngay" chỉ tồn tại trong DOM khi thực sự chưa quay hôm nay
    // (xem điều kiện render ở phần Blade phía trên). Không có thì dừng ở đây.
    if (!spinBtn) return;

    spinBtn.addEventListener('click', function () {
      if (spinning) return;
      spinning = true;
      spinBtn.disabled = true;
      msg.textContent = '';
      msg.className = 'mg-msg';
      result.classList.remove('active');

      fetch('{{ route('client.minigame.spin') }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json',
        },
      })
      .then(r => r.json())
      .then(data => {
        if (data.requireLogin) {
          resultCode.textContent = '';
          resultLabel.textContent = '';
          claimBtn.style.display = 'none';
          msg.textContent = data.message;
          msg.className = 'mg-msg error';
          result.classList.add('active');
          spinBtn.disabled = false;
          spinning = false;
          setTimeout(() => { window.location.href = data.loginUrl; }, 1200);
          return;
        }

        if (data.alreadyClaimed) {
          msg.textContent = data.message;
          msg.className = 'mg-msg error';
          result.classList.add('active');
          resultCode.textContent = '';
          resultLabel.textContent = '';
          claimBtn.style.display = 'none';
          spinBtn.disabled = true;
          spinning = false;
          return;
        }

        // Tính góc để mũi tên dừng đúng ô trúng (mũi tên chỉ lên trên = 0deg)
        const targetIndex = data.index;
        const targetCenter = targetIndex * segAngle + segAngle / 2;
        const fullTurns = 5 * 360;
        // Quay sao cho tâm ô trúng nằm dưới mũi tên (phía trên, hướng 0deg)
        const newRotation = fullTurns - targetCenter + (currentRotation - (currentRotation % 360));

        currentRotation = newRotation;
        wheel.style.transform = `rotate(${currentRotation}deg)`;

        setTimeout(() => {
          spinning = false;

          if (data.won) {
            resultCode.textContent = data.code;
            resultLabel.textContent = data.label;
            claimBtn.style.display = 'block';
            claimBtn.disabled = false;
            result.classList.add('active');
            // Đã quay trúng và ghi nhận trong session hôm nay -> khoá nút quay,
            // tránh spin thêm cho tới khi lấy mã hoặc qua ngày mới.
            spinBtn.style.display = 'none';
          } else {
            resultCode.textContent = '';
            resultLabel.textContent = data.label || 'Chúc bạn may mắn lần sau!';
            claimBtn.style.display = 'none';
            result.classList.add('active');
            // Không trúng thì cũng đã dùng hết lượt quay hôm nay -> khoá luôn nút quay.
            spinBtn.disabled = true;
          }
        }, 4300);
      })
      .catch(() => {
        spinning = false;
        spinBtn.disabled = false;
        msg.textContent = 'Có lỗi xảy ra, vui lòng thử lại.';
        msg.className = 'mg-msg error';
      });
    });
  }

  // Khởi tạo cho mọi khối vòng quay hiện có trên trang tại thời điểm script này chạy
  document.querySelectorAll('.mg-wheel-wrap').forEach(function (wrap) {
    initWheelBlock(wrap.closest('.minigame-modal') || wrap.parentElement);
  });
})();
</script>