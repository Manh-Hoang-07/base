# User Roles Fix Summary

## 🐛 Vấn đề
User API không trả về thông tin roles trong response, dẫn đến frontend không hiển thị được roles của users.

## 🔍 Nguyên nhân
1. **UserRepository** không load relationship `roles` mặc định
2. **UserService** các method không trả về user data với roles
3. **UserController** thiếu method `toggleStatus` (routes gọi nhưng không tồn tại)
4. **Response format** không consistent giữa các methods

## 🛠️ Giải pháp đã thực hiện

### 1. Cập nhật UserRepository - Auto load roles

**File**: `app/Repositories/Admin/Users/UserRepository.php`

```php
/**
 * Override getList để luôn load relationship roles
 */
public function getList(array $filters = [], array $options = []): LengthAwarePaginator
{
    // Thêm relationship roles vào options
    $options['relations'] = array_merge($options['relations'] ?? [], ['roles']);
    
    return parent::getList($filters, $options);
}

/**
 * Override getAll để luôn load relationship roles
 */
public function getAll(array $filters = [], array $options = []): Collection
{
    // Thêm relationship roles vào options
    $options['relations'] = array_merge($options['relations'] ?? [], ['roles']);
    
    return parent::getAll($filters, $options);
}

/**
 * Override findById để luôn load relationship roles
 */
public function findById($id, array $options = [])
{
    // Thêm relationship roles vào options
    $options['relations'] = array_merge($options['relations'] ?? [], ['roles']);
    
    return parent::findById($id, $options);
}
```

### 2. Cập nhật UserService - Trả về data với roles

**File**: `app/Services/Admin/Users/UserService.php`

#### Create method:
```php
public function create(array $data): array
{
    // ... validation & create user
    
    if ($user) {
        // Assign roles if provided
        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        
        $return['success'] = true;
        $return['message'] = 'Thêm mới tài khoản thành công';
        $return['data'] = $this->getRepository()->findById($user->id); // With roles
    }
    
    return $return;
}
```

#### Update method:
```php
public function update($id, array $data): array
{
    // ... validation & update user
    
    if ($this->getRepository()->update($user, $updateData)) {
        // Update roles if provided
        if (isset($data['roles']) && is_array($data['roles'])) {
            $user->syncRoles($data['roles']);
        }
        
        $return['success'] = true;
        $return['message'] = 'Cập nhật tài khoản thành công';
        $return['data'] = $this->getRepository()->findById($id); // With roles
    }
    
    return $return;
}
```

#### ChangeStatus method:
```php
public function changeStatus($id, int $status = 0): array
{
    // ... validation & update status
    
    if ($this->getRepository()->update($user, ['status' => $newStatus])) {
        $return['success'] = true;
        $return['message'] = 'Thay đổi trạng thái tài khoản thành công';
        // Trả về user data với roles
        $return['data'] = $this->getRepository()->findById($id);
    }
    
    return $return;
}
```

#### AssignRoles method:
```php
public function assignRoles($id, array $roles): array
{
    // ... validation
    
    try {
        $user->syncRoles($roles);
        $return['success'] = true;
        $return['message'] = 'Phân quyền thành công';
        $return['data'] = $this->getRepository()->findById($id); // With roles
    } catch (\Exception $e) {
        $return['message'] = 'Phân quyền thất bại: ' . $e->getMessage();
    }
    
    return $return;
}
```

### 3. Cập nhật UserController - Thêm toggleStatus

**File**: `app/Http/Controllers/Api/Admin/Users/UserController.php`

```php
/**
 * Toggle trạng thái tài khoản (API) - alias cho changeStatus
 * @param Request $request
 * @param int $id
 * @return JsonResponse
 */
public function toggleStatus(Request $request, int $id): JsonResponse
{
    return $this->changeStatus($request, $id);
}
```

### 4. Cập nhật AssignRoles Controller

```php
public function assignRoles(AssignRequest $request, int $id): JsonResponse
{
    $result = $this->getService()->assignRoles($id, $request->roles ?? []);
    
    if ($result['success']) {
        return $this->successResponse(
            $result['data'] ?? null, // Now includes user with roles
            $result['message'] ?? 'Cập nhật vai trò thành công'
        );
    }
    
    return $this->errorResponse($result['message'] ?? 'Cập nhật vai trò thất bại');
}
```

## ✅ Kết quả

### API Response hiện tại:

#### GET /api/v1/admin/users/list
```json
{
  "data": [
    {
      "id": 1,
      "email": "admin@gmail.com",
      "status": "active",
      "roles": [
        {
          "id": 1,
          "name": "admin",
          "title": "Administrator",
          "guard_name": "web"
        }
      ],
      "created_at": "2024-01-01T00:00:00.000000Z"
    }
  ],
  "current_page": 1,
  "last_page": 1,
  "total": 1
}
```

#### GET /api/v1/admin/users/find/{id}
```json
{
  "success": true,
  "data": {
    "id": 1,
    "email": "admin@gmail.com",
    "status": "active",
    "roles": [
      {
        "id": 1,
        "name": "admin",
        "title": "Administrator"
      }
    ]
  }
}
```

#### PATCH /api/v1/admin/users/status/{id}
```json
{
  "success": true,
  "message": "Thay đổi trạng thái tài khoản thành công",
  "data": {
    "id": 1,
    "email": "admin@gmail.com",
    "status": "inactive",
    "roles": [...]
  }
}
```

#### POST /api/v1/admin/users/assign-roles/{id}
```json
{
  "success": true,
  "message": "Phân quyền thành công",
  "data": {
    "id": 1,
    "email": "admin@gmail.com",
    "roles": [
      {
        "id": 2,
        "name": "editor",
        "title": "Editor"
      }
    ]
  }
}
```

## 🔧 Cải tiến khác

### Response format consistency:
- ✅ Tất cả methods đều trả về `message` thay vì `messages`
- ✅ Tất cả methods đều trả về `data` khi thành công
- ✅ Status được chuẩn hóa thành `active`/`inactive` thay vì 0/1

### Password handling:
- ✅ Auto hash password trong create/update
- ✅ Không update password nếu field rỗng
- ✅ Secure password handling

### Role management:
- ✅ Auto sync roles trong create/update
- ✅ Proper error handling cho role assignment
- ✅ Consistent role data format

## 🎯 Frontend Impact

### Vue.js components sẽ nhận được:
```javascript
// User object with roles
const user = {
  id: 1,
  email: "admin@gmail.com",
  status: "active",
  roles: [
    { id: 1, name: "admin", title: "Administrator" }
  ]
}

// Display roles in template
<span v-for="role in user.roles" :key="role.id" class="badge bg-info">
  {{ role.title || role.name }}
</span>
```

### API calls sẽ hoạt động:
- ✅ `GET /users/list` - Shows roles in table
- ✅ `GET /users/find/{id}` - Shows roles in edit form
- ✅ `PATCH /users/status/{id}` - Updates status and returns updated user with roles
- ✅ `POST /users/assign-roles/{id}` - Updates roles and returns user with new roles

## 🧪 Test Cases

```bash
# Test list users with roles
GET /api/v1/admin/users/list

# Test get user with roles
GET /api/v1/admin/users/find/1

# Test toggle status (should return user with roles)
PATCH /api/v1/admin/users/status/1
Content-Type: application/json
{"status": 0}

# Test assign roles (should return user with new roles)
POST /api/v1/admin/users/assign-roles/1
Content-Type: application/json
{"roles": [1, 2]}
```

## 📋 Files Changed

1. ✅ `app/Repositories/Admin/Users/UserRepository.php` - Auto load roles
2. ✅ `app/Services/Admin/Users/UserService.php` - Return data with roles
3. ✅ `app/Http/Controllers/Api/Admin/Users/UserController.php` - Add toggleStatus method
4. ✅ Response format consistency across all methods

Bây giờ tất cả User API endpoints sẽ trả về thông tin roles đầy đủ!
