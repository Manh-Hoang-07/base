# User Search Fix Summary

## 🐛 Lỗi gặp phải
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'name' in 'where clause' 
(Connection: mysql, SQL: select count(*) as aggregate from `users` where (`name` like %hoangvm% or `description` like %hoangvm%))
```

## 🔍 Nguyên nhân
1. **BaseRepository::applyFilters()** có logic search hardcode tìm kiếm trong cột `name` và `description`
2. **Bảng `users`** chỉ có các cột: `id`, `email`, `password`, `google_id`, `status`, `timestamps`
3. **Không có cột `name` và `description`** trong bảng users
4. Cột `name` thực tế được lưu trong bảng `profiles` (relationship)

## 🛠️ Giải pháp đã thực hiện

### 1. Override applyFilters trong UserRepository

**File**: `app/Repositories/Admin/Users/UserRepository.php`

**Trước**:
```php
class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }
}
```

**Sau**:
```php
class UserRepository extends BaseRepository
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * Override applyFilters để xử lý search đúng cho bảng users
     */
    protected function applyFilters(Builder $query, array $filters): void
    {
        foreach ($filters as $column => $value) {
            if (!empty($value)) {
                if (is_array($value)) {
                    $query->whereIn($column, $value);
                } elseif ($column === 'search') {
                    // Search chỉ trong email cho users (vì bảng users chỉ có email)
                    $query->where('email', 'like', '%' . $value . '%');
                } elseif (is_string($value)) {
                    $query->where($column, 'like', '%' . $value . '%');
                } else {
                    $query->where($column, $value);
                }
            } elseif (is_null($value)) {
                $query->whereNull($column);
            }
        }
    }
}
```

### 2. Thêm import cần thiết

**File**: `app/Models/User.php`

Thêm import:
```php
use Spatie\Permission\Models\Permission;
```

### 3. Cấu trúc bảng users

**Migration**: `database/migrations/0001_01_01_000000_create_users_table.php`

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('google_id')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->rememberToken();
    $table->timestamps();
});
```

### 4. Relationship với Profile

**User Model** đã có relationship:
```php
public function profile(): HasOne
{
    return $this->hasOne(Profile::class);
}
```

**Profile Model** có cột `name`:
```php
protected $fillable = ['user_id', 'name', 'address', 'phone', 'birth_date', 'gender'];
```

## ✅ Kết quả

### Search hiện tại:
- ✅ Search theo `email` trong bảng users
- ✅ Không còn lỗi "Column not found"
- ✅ Query đúng: `SELECT * FROM users WHERE email LIKE '%search_term%'`

### UserService autocomplete:
- ✅ Đã cấu hình đúng: `column = 'email'`, `nameField = 'email'`
- ✅ Trả về format: `{id: user_id, name: user_email}`

## 🔄 Tùy chọn mở rộng (nếu cần)

### Option 1: Search cả email và profile.name
```php
elseif ($column === 'search') {
    $query->where(function($q) use ($value) {
        $q->where('email', 'like', '%' . $value . '%')
          ->orWhereHas('profile', function($profileQuery) use ($value) {
              $profileQuery->where('name', 'like', '%' . $value . '%');
          });
    });
}
```

### Option 2: Thêm virtual attribute name
```php
// Trong User Model
protected $appends = ['name'];

public function getNameAttribute()
{
    return $this->profile?->name ?? $this->email;
}
```

### Option 3: Join với profiles table
```php
public function getList(array $filters = [], array $options = []): LengthAwarePaginator
{
    $query = $this->getModel()->query()
        ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
        ->select('users.*', 'profiles.name as profile_name');
    
    // Apply filters...
    return $query->paginate($perPage);
}
```

## 📊 Performance Impact

### Hiện tại (Simple):
- ✅ Query đơn giản: `WHERE email LIKE '%term%'`
- ✅ Sử dụng index trên email column
- ✅ Performance tốt

### Nếu search cả profile.name:
- ⚠️ Cần subquery hoặc join
- ⚠️ Có thể chậm hơn với dataset lớn
- ✅ Trải nghiệm user tốt hơn

## 🎯 Khuyến nghị

1. **Hiện tại**: Giữ search đơn giản theo email (đã implement)
2. **Tương lai**: Nếu cần search theo name, implement Option 1 với proper indexing
3. **UX**: Cập nhật placeholder text: "Tìm theo email..." thay vì "Tìm theo tên, email..."

## 🔧 Files đã thay đổi

1. ✅ `app/Repositories/Admin/Users/UserRepository.php` - Override applyFilters
2. ✅ `app/Models/User.php` - Thêm import Permission
3. ✅ Verified relationship User -> Profile
4. ✅ Verified UserService autocomplete configuration

## 🧪 Test Cases

### Test search functionality:
```bash
# Test search by email
GET /api/v1/admin/users/list?search=admin@gmail.com

# Test search by partial email
GET /api/v1/admin/users/list?search=admin

# Test autocomplete
GET /api/v1/admin/users/autocomplete?search=admin
```

### Expected Results:
- ✅ No SQL errors
- ✅ Returns users matching email pattern
- ✅ Proper pagination response format
