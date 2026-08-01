import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { Trend, Rate } from 'k6/metrics';

/* ============================================================
 * K6 LOAD TEST — LUỒNG MUA HÀNG CHO KHÁCH VÃNG LAI (Guest Checkout)
 * Không cần đăng nhập — session được giữ bằng cookie ẩn danh của Laravel
 * (k6 tự lưu cookie qua http.cookieJar() trong mỗi VU).
 *
 * Dựa trên routes.php:
 *   GET  /san-pham                  -> shop
 *   GET  /chi-tiet/{id}             -> product.show
 *   POST /gio-hang/them             -> cart.add
 *   GET  /gio-hang                  -> cart
 *   PATCH /gio-hang/{variantId}     -> cart.update
 *   POST /dat-hang                  -> checkout.proceed
 *   GET  /thanh-toan/thong-tin      -> checkout.shipping
 *   POST /thanh-toan/thong-tin      -> checkout.shipping.post
 *   GET  /thanh-toan/phuong-thuc    -> checkout.payment
 *   POST /thanh-toan/ma-giam-gia    -> checkout.discount.apply (tuỳ chọn)
 *   POST /thanh-toan/hoan-thanh     -> checkout.finalize
 *
 * Lưu ý: Laravel web routes dùng session + CSRF token (_token).
 * Script này tự động lấy token từ meta tag <meta name="csrf-token">
 * hoặc input hidden name="_token" trong HTML trả về.
 * ============================================================ */

// ------------------ CONFIG ------------------
const BASE_URL = __ENV.BASE_URL || 'http://localhost:8000';

// ID sản phẩm & variant dùng để test (nên có sẵn, còn hàng)
const PRODUCT_ID = __ENV.PRODUCT_ID || '1';
const VARIANT_ID = __ENV.VARIANT_ID || '1';

// Mã giảm giá test (optional) — để trống nếu không muốn test áp mã
const DISCOUNT_CODE = __ENV.DISCOUNT_CODE || '';

// Phương thức thanh toán — backend chỉ chấp nhận 'cod' hoặc 'bank'
const PAYMENT_METHOD = __ENV.PAYMENT_METHOD || 'cod';

export const options = __ENV.SMOKE
  ? {
      // Chế độ debug: 1 VU, chạy 1 lần, log rõ ràng không bị chen lẫn
      vus: 1,
      iterations: 1,
      thresholds: {},
    }
  : {
      scenarios: {
        purchase_flow: {
          executor: 'ramping-vus',
          startVUs: 0,
          stages: [
            { duration: '30s', target: 5 },
            { duration: '1m', target: 5 },
            { duration: '30s', target: 0 },
          ],
        },
      },
      thresholds: {
        http_req_failed: ['rate<0.05'],
        http_req_duration: ['p(95)<1500'],
        checkout_success: ['rate>0.9'],
      },
    };

const checkoutSuccess = new Rate('checkout_success');
const checkoutDuration = new Trend('checkout_full_flow_duration', true);

// ------------------ HELPERS ------------------

// Trích xuất CSRF token từ HTML (meta tag hoặc input hidden), không phụ thuộc
// thứ tự attribute hay loại dấu nháy (" hoặc ')
function extractCsrfToken(body) {
  if (!body) return null;
  let m = body.match(/<meta\s+name=["']csrf-token["']\s+content=["']([^"']+)["']/i);
  if (m) return m[1];
  m = body.match(/<input[^>]*name=["']_token["'][^>]*value=["']([^"']+)["']/i);
  if (m) return m[1];
  m = body.match(/<input[^>]*value=["']([^"']+)["'][^>]*name=["']_token["']/i);
  if (m) return m[1];
  return null;
}

// In ra thông tin debug khi 1 bước fail, để biết chính xác lỗi từ server
// (404 sai route, 419 CSRF mismatch, 422 validation, 500 lỗi server, ...)
function logFailure(label, res) {
  const bodyPreview = (res.body || '').toString().slice(0, 500);
  console.log(`[FAIL] ${label} | status=${res.status} | url=${res.url}`);
  console.log(`[FAIL] ${label} | body: ${bodyPreview}`);
}

function jar() {
  return http.cookieJar();
}

function commonHeaders(csrfToken, extra = {}) {
  return Object.assign(
    {
      'X-Requested-With': 'XMLHttpRequest',
      ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
    },
    extra
  );
}

// ------------------ MAIN FLOW ------------------
export default function () {
  const start = Date.now();
  let csrfToken = null;

  group('01. Trang chủ & lấy CSRF token (khởi tạo session khách)', function () {
    const res = http.get(`${BASE_URL}/`);
    check(res, { 'trang chủ trả về 200': (r) => r.status === 200 });
    csrfToken = extractCsrfToken(res.body);
  });

  sleep(1);

  group('02. Xem danh sách sản phẩm', function () {
    const res = http.get(`${BASE_URL}/san-pham`);
    check(res, { 'danh sách sản phẩm trả về 200': (r) => r.status === 200 });
    csrfToken = extractCsrfToken(res.body) || csrfToken;
  });

  sleep(1);

  group('03. Xem chi tiết sản phẩm', function () {
    const res = http.get(`${BASE_URL}/chi-tiet/${PRODUCT_ID}`);
    check(res, { 'chi tiết sản phẩm trả về 200': (r) => r.status === 200 });
    csrfToken = extractCsrfToken(res.body) || csrfToken;
  });

  sleep(1);

  group('04. Thêm vào giỏ hàng', function () {
    if (!csrfToken) {
      console.log('[WARN] Không lấy được CSRF token trước khi thêm giỏ hàng');
    }
    const res = http.post(
      `${BASE_URL}/gio-hang/them`,
      JSON.stringify({
        variantID: VARIANT_ID,
        quantity: 1,
      }),
      { headers: commonHeaders(csrfToken, { 'Content-Type': 'application/json' }) }
    );
    const ok = check(res, {
      'thêm giỏ hàng thành công': (r) => r.status === 200 || r.status === 201,
    });
    if (!ok) logFailure('04. Thêm vào giỏ hàng', res);
  });

  sleep(1);

  group('05. Xem giỏ hàng', function () {
    const res = http.get(`${BASE_URL}/gio-hang`);
    check(res, { 'giỏ hàng trả về 200': (r) => r.status === 200 });
    csrfToken = extractCsrfToken(res.body) || csrfToken;
  });

  group('06. Cập nhật số lượng giỏ hàng', function () {
    const res = http.patch(
      `${BASE_URL}/gio-hang/${VARIANT_ID}`,
      JSON.stringify({ quantity: 2 }),
      { headers: commonHeaders(csrfToken, { 'Content-Type': 'application/json' }) }
    );
    const ok = check(res, {
      'cập nhật giỏ hàng thành công': (r) => r.status === 200,
    });
    if (!ok) logFailure('06. Cập nhật số lượng giỏ hàng', res);
  });

  sleep(1);

  group('07. Tiến hành đặt hàng (proceed to shipping)', function () {
    const res = http.post(
      `${BASE_URL}/dat-hang`,
      { _token: csrfToken },
      { headers: commonHeaders(csrfToken), redirects: 3 }
    );
    const ok = check(res, {
      'chuyển sang bước giao hàng': (r) => r.status === 200 || r.status === 302,
    });
    if (!ok) logFailure('07. Tiến hành đặt hàng', res);
  });

  group('08. Trang thông tin giao hàng', function () {
    const res = http.get(`${BASE_URL}/thanh-toan/thong-tin`);
    check(res, { 'trang thông tin giao hàng trả về 200': (r) => r.status === 200 });
    csrfToken = extractCsrfToken(res.body) || csrfToken;
  });

  group('09. Gửi thông tin giao hàng', function () {
    const res = http.post(
      `${BASE_URL}/thanh-toan/thong-tin`,
      {
        _token: csrfToken,
        fullName: 'Nguyen Van Test',
        phone: '0900000000',
        city: 'Hồ Chí Minh',
        district: 'Quận 1',
        ward: 'Phường Bến Nghé',
        addressDetail: '123 Đường ABC',
      },
      { headers: commonHeaders(csrfToken), redirects: 3 }
    );
    const ok = check(res, {
      'gửi thông tin giao hàng thành công': (r) => r.status === 200 || r.status === 302,
    });
    if (!ok) logFailure('09. Gửi thông tin giao hàng', res);
  });

  if (DISCOUNT_CODE) {
    group('10. Áp dụng mã giảm giá', function () {
      const res = http.post(
        `${BASE_URL}/thanh-toan/ma-giam-gia`,
        JSON.stringify({ code: DISCOUNT_CODE }),
        { headers: commonHeaders(csrfToken, { 'Content-Type': 'application/json' }) }
      );
      const ok = check(res, {
        'áp dụng mã giảm giá thành công': (r) => r.status === 200 && JSON.parse(r.body || '{}').success === true,
      });
      if (!ok) logFailure('10. Áp dụng mã giảm giá', res);
    });
  }

  group('11. Trang chọn phương thức thanh toán', function () {
    const res = http.get(`${BASE_URL}/thanh-toan/phuong-thuc`);
    check(res, { 'trang thanh toán trả về 200': (r) => r.status === 200 });
    csrfToken = extractCsrfToken(res.body) || csrfToken;
  });

  group('12. Hoàn tất đơn hàng (finalize)', function () {
    const res = http.post(
      `${BASE_URL}/thanh-toan/hoan-thanh`,
      {
        _token: csrfToken,
        payment_method: PAYMENT_METHOD,
      },
      { headers: commonHeaders(csrfToken), redirects: 3 }
    );

    const ok = check(res, {
      'đặt hàng thành công (200/302)': (r) => r.status === 200 || r.status === 302,
    });
    if (!ok) logFailure('12. Hoàn tất đơn hàng', res);

    checkoutSuccess.add(ok);
  });

  checkoutDuration.add(Date.now() - start);

  sleep(2);
}