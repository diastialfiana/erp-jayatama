@extends('layouts.app')

@section('content')
<style>
/* Modern Table Styles */
.card-container {
    background: #1e293b;
    border-radius: 12px;
    border: 1px solid rgba(255,255,255,0.05);
    padding: 20px;
    margin-top: 20px;
}
.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.search-input {
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(255,255,255,0.1);
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    outline: none;
    width: 250px;
}
.search-input:focus { border-color: #3b82f6; }

.btn-primary {
    background: #3b82f6;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 500;
    transition: 0.2s;
    display: flex; align-items: center; gap: 8px;
}
.btn-primary:hover { background: #2563eb; }

.table-modern {
    width: 100%;
    border-collapse: collapse;
}
.table-modern th, .table-modern td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    color: rgba(255,255,255,0.8);
    font-size: 0.85rem;
}
.table-modern th {
    font-weight: 600;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.05em;
}
.badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-super_admin { background: rgba(239,68,68,0.2); color: #ef4444; }
.badge-admin { background: rgba(245,158,11,0.2); color: #f59e0b; }
.badge-user { background: rgba(59,130,246,0.2); color: #3b82f6; }
.badge-active { background: rgba(16,185,129,0.2); color: #10b981; }
.badge-inactive { background: rgba(107,114,128,0.2); color: #9ca3af; }

.action-btns {
    display: flex; gap: 8px;
}
.btn-icon {
    background: transparent;
    border: none;
    color: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: 0.2s;
    padding: 4px;
}
.btn-icon:hover { color: white; }
.btn-icon.edit:hover { color: #3b82f6; }
.btn-icon.delete:hover { color: #ef4444; }
.btn-icon.reset:hover { color: #f59e0b; }

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex; justify-content: center; align-items: center;
    opacity: 0; pointer-events: none;
    transition: 0.3s;
    z-index: 1000;
}
.modal-overlay.show { opacity: 1; pointer-events: auto; }
.modal-content {
    background: #1e293b;
    width: 400px;
    border-radius: 12px;
    padding: 24px;
    transform: translateY(20px);
    transition: 0.3s;
    border: 1px solid rgba(255,255,255,0.1);
}
.modal-overlay.show .modal-content { transform: translateY(0); }
.modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
.modal-title { font-size: 1.1rem; font-weight: 600; color: white; margin: 0; }
.modal-close { background: none; border: none; color: rgba(255,255,255,0.5); cursor: pointer; }

.form-group { margin-bottom: 16px; }
.form-label { display: block; font-size: 0.8rem; color: rgba(255,255,255,0.7); margin-bottom: 6px; }
.form-control {
    width: 100%;
    background: rgba(0,0,0,0.2);
    border: 1px solid rgba(255,255,255,0.1);
    padding: 10px 12px;
    border-radius: 6px;
    color: white;
    font-size: 0.85rem;
    outline: none;
}
.form-control:focus { border-color: #3b82f6; }
.modal-footer { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; }
.btn-cancel { background: rgba(255,255,255,0.1); color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; }

/* Toast */
.toast {
    position: fixed; bottom: 20px; right: 20px;
    background: #10b981; color: white; padding: 12px 24px;
    border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    transform: translateY(100px); opacity: 0; transition: 0.3s; z-index: 9999;
}
.toast.show { transform: translateY(0); opacity: 1; }
.toast.error { background: #ef4444; }
</style>

<div class="container-fluid py-4">
    <h2 style="font-size: 1.5rem; font-weight: 700; color: #fff; margin-bottom: 5px;">User Management</h2>
    <p style="color: rgba(255,255,255,0.5); font-size: 0.85rem; margin-bottom: 20px;">Manage system users, roles, and access.</p>

    <div class="card-container">
        <div class="header-actions">
            <input type="text" id="searchInput" class="search-input" placeholder="Search user...">
            <button class="btn-primary" onclick="openModal()">
                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="17" y1="11" x2="23" y2="11"/></svg>
                Add User
            </button>
        </div>

        <table class="table-modern" id="usersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td class="u-name" style="font-weight: 500; color: white;">{{ $user->name }}</td>
                    <td>{{ $user->username }}</td>
                    <td><span class="badge badge-{{ $user->role }}">{{ str_replace('_', ' ', strtoupper($user->role)) }}</span></td>
                    <td><span class="badge badge-{{ $user->status }}">{{ ucfirst($user->status) }}</span></td>
                    <td>
                        <div class="action-btns">
                            @if($user->role !== 'super_admin' || auth()->user()->isSuperAdmin())
                                <button class="btn-icon edit" title="Edit" onclick="editUser({{ $user }})">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <button class="btn-icon reset" title="Reset Password" onclick="resetPassword({{ $user->id }})">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                </button>
                                @if(auth()->id() !== $user->id)
                                    <button class="btn-icon delete" title="Delete" onclick="deleteUser({{ $user->id }})">
                                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    </button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Form -->
<div class="modal-overlay" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Add User</h3>
            <button class="modal-close" onclick="closeModal()">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form id="userForm" onsubmit="saveUser(event)">
            <input type="hidden" id="userId">
            <div class="form-group">
                <label class="form-label">Name</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="username" required>
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                <select class="form-control" id="role" required>
                    @if(auth()->user()->isSuperAdmin())
                        <option value="super_admin">Super Admin</option>
                    @endif
                    <option value="admin">Admin</option>
                    <option value="user" selected>User</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select class="form-control" id="status" required>
                    <option value="active" selected>Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button type="submit" class="btn-primary" id="saveBtn">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="toast">Action successful</div>

<script>
    const modal = document.getElementById('userModal');
    const form = document.getElementById('userForm');
    const title = document.getElementById('modalTitle');
    let isEdit = false;

    // Search
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('#usersTable tbody tr').forEach(row => {
            const name = row.querySelector('.u-name').textContent.toLowerCase();
            row.style.display = name.includes(term) ? '' : 'none';
        });
    });

    function showToast(msg, isError = false) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast show ' + (isError ? 'error' : '');
        setTimeout(() => t.classList.remove('show'), 3000);
    }

    function openModal() {
        isEdit = false;
        title.textContent = 'Add User';
        form.reset();
        document.getElementById('userId').value = '';
        modal.classList.add('show');
        setTimeout(() => document.getElementById('name').focus(), 100);
    }

    function editUser(user) {
        isEdit = true;
        title.textContent = 'Edit User';
        document.getElementById('userId').value = user.id;
        document.getElementById('name').value = user.name;
        document.getElementById('username').value = user.username;
        
        // Handle role dropdown if super admin editing
        const roleSel = document.getElementById('role');
        if (user.role === 'super_admin' && !Array.from(roleSel.options).some(o => o.value === 'super_admin')) {
            const opt = document.createElement('option');
            opt.value = 'super_admin';
            opt.text = 'Super Admin';
            roleSel.add(opt, 0);
        }
        roleSel.value = user.role;
        document.getElementById('status').value = user.status;
        
        modal.classList.add('show');
        setTimeout(() => document.getElementById('name').focus(), 100);
    }

    function closeModal() {
        modal.classList.remove('show');
    }

    async function saveUser(e) {
        e.preventDefault();
        const btn = document.getElementById('saveBtn');
        btn.disabled = true;
        btn.textContent = 'Saving...';

        const id = document.getElementById('userId').value;
        const data = {
            name: document.getElementById('name').value,
            username: document.getElementById('username').value,
            role: document.getElementById('role').value,
            status: document.getElementById('status').value,
        };

        const url = isEdit ? `/administrator/users/${id}` : '/administrator/users';
        const method = isEdit ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                showToast(isEdit ? 'User updated successfully' : 'User created successfully');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                const err = await res.json();
                showToast(err.message || 'Error occurred', true);
            }
        } catch (e) {
            showToast('Network error', true);
        } finally {
            btn.disabled = false;
            btn.textContent = 'Save';
        }
    }

    async function deleteUser(id) {
        if (!confirm('Are you sure you want to delete this user?')) return;
        try {
            const res = await fetch(`/administrator/users/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) {
                showToast('User deleted');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                const err = await res.json();
                showToast(err.message || 'Error deleting', true);
            }
        } catch(e) {
            showToast('Network error', true);
        }
    }

    async function resetPassword(id) {
        if (!confirm('Reset password to default (jayatama)?')) return;
        try {
            const res = await fetch(`/administrator/users/${id}/reset-password`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            if (res.ok) {
                showToast('Password reset successful');
            } else {
                const err = await res.json();
                showToast(err.message || 'Error resetting', true);
            }
        } catch(e) {
            showToast('Network error', true);
        }
    }
</script>
@endsection
