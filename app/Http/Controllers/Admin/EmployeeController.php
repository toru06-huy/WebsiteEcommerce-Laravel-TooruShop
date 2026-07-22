<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $query = Employee::with(['user.addresses', 'position']);

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('fullName', 'like', '%' . $request->search . '%')
                    ->orWhere('email',  'like', '%' . $request->search . '%')
                    ->orWhere('phone',  'like', '%' . $request->search . '%');
            })->orWhere('employeeCode', 'like', '%' . $request->search . '%');
        }

        $employees = $query->paginate(15)->withQueryString();
        $positions = Position::orderBy('positionName')->get();

        return view('admin.employees.index', compact('employees', 'positions'));
    }
    public function detail($id)
    {
        $employee = Employee::with(['user', 'position'])->findOrFail($id);
        $user     = $employee->user;
        $address  = $user->addresses->first();

        $processedOrders = Order::where('processedBy', $employee->employeeID)
            ->selectRaw('status, COUNT(*) as cnt')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return response()->json([
            'employee' => [
                'employeeID'   => $employee->employeeID,
                'employeeCode' => $employee->employeeCode,
                'fullName'     => $user->fullName,
                'email'        => $user->email,
                'phone'        => $user->phone,
                'sex'          => $user->sex,
                'birthday'     => $user->birthday?->format('d/m/Y'),
                'IsActive'     => $user->IsActive,
                'position'     => $employee->position?->positionName,
                'salary'       => (float) $employee->salary,
                'hireDate'     => $employee->hireDate?->format('d/m/Y'),

                'city'          => $address?->city ?? '',
                'district'      => $address?->district ?? '',
                'ward'          => $address?->ward ?? '',
                'addressDetail' => $address?->addressDetail ?? '',
            ],
            'orderStats' => [
                'total'     => $processedOrders->sum('cnt'),
                'completed' => (int) ($processedOrders->get('Completed')?->cnt ?? 0),
                'cancelled' => (int) ($processedOrders->get('Cancelled')?->cnt ?? 0),
                'pending'   => (int) ($processedOrders->get('Pending')?->cnt ?? 0),
            ],
        ]);
    }
    public function store(Request $request)
    {
        $request->validate([

            'fullName'     => 'required|string|max:100',
            'email'        => 'nullable|email|max:100|unique:users,email',
            'phone'        => 'nullable|string|max:20|unique:users,phone',
            'password'     => 'required|string|min:8',
            'sex'          => 'nullable|in:Nam,Nữ,Khác',
            'birthday'     => 'nullable|date',

            'employeeCode' => 'required|string|max:50|unique:employees,employeeCode',
            'positionID'   => 'required|exists:positions,positionID',
            'salary'       => 'nullable|numeric|min:0',
            'hireDate'     => 'nullable|date',

            'city'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'ward'          => 'nullable|string|max:100',
            'addressDetail' => 'nullable|string|max:255',
        ], [
            'fullName.required'     => 'Họ tên không được để trống.',
            'email.unique'          => 'Email đã được sử dụng.',
            'phone.unique'          => 'Số điện thoại đã được sử dụng.',
            'password.min'          => 'Mật khẩu tối thiểu 8 ký tự.',
            'employeeCode.required' => 'Mã nhân viên không được để trống.',
            'employeeCode.unique'   => 'Mã nhân viên đã tồn tại.',
            'positionID.required'   => 'Vui lòng chọn chức vụ.',

            'city.string'          => 'Thành phố phải là một chuỗi ký tự.',
            'district.string'      => 'Quận/huyện phải là một chuỗi ký tự.',
            'ward.string'          => 'Phường/xã phải là một chuỗi ký tự.',
            'addressDetail.string' => 'Chi tiết địa chỉ phải là một chuỗi ký tự.',

            'city.required'          => 'Thành phố không được để trống.',
            'district.required'      => 'Quận/huyện không được để trống.',
            'ward.required'          => 'Phường/xã không được để trống.',
        ]);

        DB::transaction(function () use ($request) {
            // Tạo User với role = Employee
            $user = User::create([
                'fullName' => $request->fullName,
                'email'    => $request->email    ?: null,
                'phone'    => $request->phone    ?: null,
                'password' => Hash::make($request->password),
                'sex'      => $request->sex      ?: null,
                'birthday' => $request->birthday ?: null,
                'role'     => 'Employee',
                'IsActive' => true,
            ]);

            // Tạo Employee liên kết với User
            Employee::create([
                'userID'       => $user->userID,
                'employeeCode' => $request->employeeCode,
                'positionID'   => $request->positionID,
                'salary'       => $request->salary   ?: 0,
                'hireDate'     => $request->hireDate ?: now(),
            ]);
            if ($request->filled('city') || $request->filled('addressDetail')) {
                $user->addresses()->create([
                    'city'          => $request->city,
                    'district'      => $request->district,
                    'ward'          => $request->ward,
                    'addressDetail' => $request->addressDetail ?: null,
                ]);
            }
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Đã thêm nhân viên "' . $request->fullName . '".');
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::with('user')->findOrFail($id);

        $request->validate([
            'employeeCode' => 'required|string|max:50|unique:employees,employeeCode,' . $id . ',employeeID',
            'positionID'   => 'required|exists:positions,positionID',
            'salary'       => 'nullable|numeric|min:0',
            'hireDate'     => 'nullable|date',

            'fullName'      => 'required|string|max:100',
            'phone'         => 'nullable|string|max:20|unique:users,phone,' . $employee->userID . ',userID',
            'sex'           => 'nullable|in:Nam,Nữ,Khác',
            'birthday'      => 'nullable|date',

            'city'          => 'nullable|string|max:100',
            'district'      => 'nullable|string|max:100',
            'ward'          => 'nullable|string|max:100',
            'addressDetail' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $employee) {
            // 1. Cập nhật thông tin cơ bản của Employee
            $employee->update([
                'employeeCode' => $request->employeeCode,
                'positionID'   => $request->positionID,
                'salary'       => $request->salary   ?: 0,
                'hireDate'     => $request->hireDate ?: $employee->hireDate,
            ]);

            // 2. Cập nhật thông tin tài khoản User (Bỏ qua email)
            $user = $employee->user;
            if ($user) {
                $user->update([
                    'fullName' => $request->fullName,
                    'phone'    => $request->phone ?: null,
                    'sex'      => $request->sex ?: null,
                    'birthday' => $request->birthday ?: null,
                ]);

                // 3. Xử lý cập nhật hoặc thêm mới địa chỉ
                $address = $user->addresses()->first();
                $addressData = [
                    'city'          => $request->city,
                    'district'      => $request->district,
                    'ward'          => $request->ward,
                    'addressDetail' => $request->addressDetail,
                ];

                if ($address) {
                    $address->update($addressData);
                } else {
                    if ($request->filled('city') || $request->filled('addressDetail')) {
                        $user->addresses()->create($addressData);
                    }
                }
            }
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Đã cập nhật thông tin nhân viên.');
    }

    public function destroy($id)
    {
        $employee = Employee::with('user')->findOrFail($id);
        $name     = $employee->user->fullName;

        if ($employee->user->role !== 'Employee') {
            return redirect()->route('admin.employees.index')
                ->withErrors(['error' => 'Không thể xóa tài khoản quản trị viên.']);
        }
        DB::transaction(function () use ($employee) {
            $employee->user->update(['role' => 'Customer']);
            $employee->delete();
        });

        return redirect()->route('admin.employees.index')
            ->with('success', 'Đã xóa nhân viên "' . $name . '". Tài khoản được chuyển về Customer.');
    }
}
