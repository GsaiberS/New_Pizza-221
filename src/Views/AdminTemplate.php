<?php
namespace App\Views;

class AdminTemplate extends BaseTemplate
{
    public static function renderDashboard(array $users, array $stats = array()): string
    {
        $rows = '';
        foreach ($users as $user) {
            // Проверяем и устанавливаем значения по умолчанию для всех полей
            $userId = isset($user['id']) ? $user['id'] : 'N/A';
            $username = isset($user['username']) ? $user['username'] : 'Неизвестно';
            $email = isset($user['email']) ? $user['email'] : 'Нет email';
            $role = isset($user['role']) ? $user['role'] : 'user';
            $isVerified = isset($user['is_verified']) ? $user['is_verified'] : 0;
            $avatar = isset($user['avatar']) ? $user['avatar'] : '/assets/image/default-avatar.png';
            $createdDate = isset($user['created']) ? date('d.m.Y H:i', strtotime($user['created'])) : 'Неизвестно';

            $verifiedYes = self::selected($isVerified, 1);
            $verifiedNo  = self::selected($isVerified, 0);

            $verified = <<<HTML
                <select name="is_verified" class="form-select form-select-sm admin-select">
                    <option value="1" $verifiedYes>✅ Да</option>
                    <option value="0" $verifiedNo>❌ Нет</option>
                </select>
HTML;

            $selectedUser = self::selected($role, 'user');
            $selectedAdmin = self::selected($role, 'admin');
            $selectedModerator = self::selected($role, 'moderator');

            $statusBadge = $isVerified ? 
                '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Подтвержден</span>' : 
                '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Ожидает</span>';

            $roleBadge = $role === 'admin' ? 
                '<span class="badge bg-gradient-admin"><i class="fas fa-crown me-1"></i>Админ</span>' : 
                '<span class="badge bg-gradient-user"><i class="fas fa-user me-1"></i>Пользователь</span>';

            $rows .= <<<HTML
                <tr class="admin-user-row">
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="{$avatar}" alt="{$username}" class="admin-avatar rounded-circle me-2">
                            <div>
                                <div class="fw-semibold">#{$userId}</div>
                                <small class="text-muted">{$createdDate}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="admin-username">{$username}</div>
                        <small class="text-muted">{$email}</small>
                    </td>
                    <td>
                        <div class="role-badge-container">
                            $roleBadge
                            $statusBadge
                        </div>
                    </td>
                    <td>
                        <form method="post" action="/admin/update_user" class="admin-quick-form">
                            <input type="hidden" name="id" value="{$userId}">
                            <div class="admin-form-row">
                                <select name="role" class="form-select form-select-sm admin-select">
                                    <option value="user" $selectedUser>👤 Пользователь</option>
                                    <option value="moderator" $selectedModerator>🛡️ Модератор</option>
                                    <option value="admin" $selectedAdmin>👑 Администратор</option>
                                </select>
                                $verified
                            </div>
                    </td>
                    <td>
                        <div class="admin-actions">
                            <button type="submit" class="btn btn-sm btn-success me-1 admin-btn" title="Сохранить">
                                <i class="fas fa-save"></i>
                            </button>
                        </form>
                            <a href="/admin/edit_user/{$userId}" class="btn btn-sm btn-primary me-1 admin-btn" title="Полное редактирование">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-info me-1 admin-btn" 
                                    onclick="showUserDetails({$userId})" title="Информация">
                                <i class="fas fa-info-circle"></i>
                            </button>
                            <a href="/admin/delete_user/{$userId}" class="btn btn-sm btn-danger admin-btn" 
                               onclick="return confirm('Удалить пользователя {$username}?')" title="Удалить">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
HTML;
        }

        // Исправляем использование ?? на тернарные операторы
        $activeUsers = isset($stats['verified']) ? $stats['verified'] : 0;
        $adminCount = isset($stats['admins']) ? $stats['admins'] : 0;
        $totalUsers = isset($stats['users']) ? $stats['users'] : count($users);
        $inactiveUsers = $totalUsers - $activeUsers;

        $statCards = <<<HTML
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="admin-stat-card stat-total">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{$totalUsers}</h3>
                            <p>Всего пользователей</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="admin-stat-card stat-active">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{$activeUsers}</h3>
                            <p>Активных</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="admin-stat-card stat-pending">
                        <div class="stat-icon">
                            <i class="fas fa-user-clock"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{$inactiveUsers}</h3>
                            <p>Ожидают подтверждения</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="admin-stat-card stat-admin">
                        <div class="stat-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{$adminCount}</h3>
                            <p>Администраторов</p>
                        </div>
                    </div>
                </div>
            </div>
HTML;

        // Быстрые действия
        $quickActions = <<<HTML
            <div class="admin-quick-actions mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <button class="quick-action-btn btn-export" onclick="exportUsers()">
                            <i class="fas fa-file-export"></i>
                            <span>Экспорт данных</span>
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="quick-action-btn btn-bulk" onclick="showBulkActions()">
                            <i class="fas fa-tasks"></i>
                            <span>Массовые действия</span>
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="quick-action-btn btn-search" onclick="toggleSearch()">
                            <i class="fas fa-search"></i>
                            <span>Расширенный поиск</span>
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="/admin?refresh=1" class="quick-action-btn btn-refresh">
                            <i class="fas fa-sync-alt"></i>
                            <span>Обновить данные</span>
                        </a>
                    </div>
                </div>
            </div>
HTML;

        $lastUpdate = isset($stats['last_update']) ? $stats['last_update'] : date('d.m.Y H:i:s');

        $content = <<<HTML
            <div class="admin-header">
                <h1 class="admin-title">
                    <i class="fas fa-user-shield me-3"></i>
                    Панель управления
                    <small class="admin-subtitle">Bubble Pizza Administration</small>
                </h1>
                <div class="admin-breadcrumb">
                    <span class="breadcrumb-item active">Главная панель</span>
                    <span class="breadcrumb-divider">/</span>
                    <span>Управление пользователями</span>
                </div>
            </div>

            {$statCards}
            {$quickActions}

            <div class="admin-content-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-alt me-2"></i>
                        Список пользователей
                        <span class="badge bg-primary ms-2">{$totalUsers}</span>
                    </h3>
                    <div class="card-actions">
                        <div class="input-group search-group">
                            <input type="text" class="form-control admin-search" placeholder="Поиск пользователей..." id="userSearch">
                            <button class="btn btn-search" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table admin-table table-hover">
                            <thead class="admin-table-header">
                                <tr>
                                    <th width="15%">Пользователь</th>
                                    <th width="25%">Контакты</th>
                                    <th width="20%">Статус</th>
                                    <th width="25%">Быстрое управление</th>
                                    <th width="15%">Действия</th>
                                </tr>
                            </thead>
                            <tbody id="userTableBody">
                                {$rows}
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Последнее обновление: {$lastUpdate}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="admin-pagination">
                                <button class="btn btn-sm btn-outline-primary me-1" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="pagination-info">Страница 1 из 1</span>
                                <button class="btn btn-sm btn-outline-primary ms-1" disabled>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Модальное окно деталей пользователя -->
            <div class="modal fade" id="userDetailsModal" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content admin-modal">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-circle me-2"></i>
                                Детальная информация
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body" id="userDetailsContent">
                            <!-- Контент будет загружен через AJAX -->
                        </div>
                    </div>
                </div>
            </div>
HTML;

        $template = BaseTemplate::getTemplate();
        
        // Добавляем стили для админ-панели
        $content .= self::getAdminStyles();
        
        // Добавляем JavaScript для админ-панели
        $content .= self::getAdminScripts();

        return sprintf($template, 'Админ-панель - Bubble Pizza', $content);
    }

    private static function getAdminStyles(): string
    {
        return <<<HTML
        <style>
            /* Стили админ-панели в стиле Bubble Pizza */
            .admin-header {
                background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
                border-radius: 20px;
                padding: 2rem;
                margin-bottom: 2rem;
                border: 1px solid rgba(255,255,255,0.2);
                backdrop-filter: blur(10px);
            }
            
            .admin-title {
                font-size: 2.5rem;
                font-weight: 800;
                background: linear-gradient(135deg, #667eea, #764ba2);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin: 0;
            }
            
            .admin-subtitle {
                font-size: 1rem;
                color: #6c757d;
                font-weight: 400;
                display: block;
                margin-top: 0.5rem;
            }
            
            .admin-breadcrumb {
                margin-top: 1rem;
                font-size: 0.9rem;
            }
            
            .breadcrumb-item {
                color: #667eea;
            }
            
            .breadcrumb-divider {
                color: #6c757d;
                margin: 0 0.5rem;
            }
            
            /* Статистические карточки */
            .admin-stat-card {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                padding: 1.5rem;
                border: 1px solid rgba(255,255,255,0.3);
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                display: flex;
                align-items: center;
                transition: all 0.3s ease;
                height: 100%;
            }
            
            .admin-stat-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 40px rgba(102,126,234,0.2);
            }
            
            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 1rem;
                font-size: 1.5rem;
                color: white;
            }
            
            .stat-total .stat-icon { background: linear-gradient(135deg, #667eea, #764ba2); }
            .stat-active .stat-icon { background: linear-gradient(135deg, #28a745, #20c997); }
            .stat-pending .stat-icon { background: linear-gradient(135deg, #ffc107, #fd7e14); }
            .stat-admin .stat-icon { background: linear-gradient(135deg, #dc3545, #e83e8c); }
            
            .stat-content h3 {
                font-size: 2rem;
                font-weight: 700;
                margin: 0;
                color: #343a40;
            }
            
            .stat-content p {
                margin: 0;
                color: #6c757d;
                font-weight: 500;
            }
            
            /* Быстрые действия */
            .admin-quick-actions {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                padding: 1.5rem;
                border: 1px solid rgba(255,255,255,0.3);
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
            }
            
            .quick-action-btn {
                width: 100%;
                background: rgba(102,126,234,0.1);
                border: 2px dashed rgba(102,126,234,0.3);
                border-radius: 15px;
                padding: 1rem;
                color: #667eea;
                text-decoration: none;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.3s ease;
                font-weight: 600;
            }
            
            .quick-action-btn:hover {
                background: rgba(102,126,234,0.2);
                border-color: rgba(102,126,234,0.5);
                transform: translateY(-2px);
                color: #667eea;
            }
            
            /* Основной контент */
            .admin-content-card {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(15px);
                border-radius: 20px;
                border: 1px solid rgba(255,255,255,0.3);
                box-shadow: 0 8px 32px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .card-header {
                background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
                border-bottom: 1px solid rgba(255,255,255,0.3);
                padding: 1.5rem;
            }
            
            .card-title {
                color: #343a40;
                font-weight: 700;
                margin: 0;
                display: flex;
                align-items: center;
            }
            
            .card-actions {
                display: flex;
                align-items: center;
                gap: 1rem;
            }
            
            .search-group {
                max-width: 300px;
            }
            
            .admin-search {
                border-radius: 25px;
                border: 1px solid rgba(102,126,234,0.3);
                padding: 0.5rem 1rem;
            }
            
            .btn-search {
                background: linear-gradient(135deg, #667eea, #764ba2);
                color: white;
                border-radius: 25px;
                border: none;
                padding: 0.5rem 1rem;
            }
            
            /* Таблица */
            .admin-table {
                margin: 0;
            }
            
            .admin-table-header {
                background: rgba(102,126,234,0.1);
            }
            
            .admin-table-header th {
                border: none;
                font-weight: 700;
                color: #343a40;
                padding: 1rem;
            }
            
            .admin-user-row {
                transition: all 0.3s ease;
                border-bottom: 1px solid rgba(0,0,0,0.05);
            }
            
            .admin-user-row:hover {
                background: rgba(102,126,234,0.05);
                transform: translateX(5px);
            }
            
            .admin-avatar {
                width: 40px;
                height: 40px;
                object-fit: cover;
            }
            
            .admin-username {
                font-weight: 600;
                color: #343a40;
            }
            
            .role-badge-container {
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .badge.bg-gradient-admin {
                background: linear-gradient(135deg, #dc3545, #e83e8c);
            }
            
            .badge.bg-gradient-user {
                background: linear-gradient(135deg, #667eea, #764ba2);
            }
            
            .admin-form-row {
                display: flex;
                gap: 0.5rem;
            }
            
            .admin-select {
                border-radius: 10px;
                border: 1px solid rgba(102,126,234,0.3);
                background: rgba(255,255,255,0.9);
            }
            
            .admin-actions {
                display: flex;
                gap: 0.25rem;
            }
            
            .admin-btn {
                border-radius: 10px;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border: none;
                transition: all 0.3s ease;
            }
            
            .admin-btn:hover {
                transform: translateY(-2px);
            }
            
            .card-footer {
                background: rgba(248,249,250,0.8);
                border-top: 1px solid rgba(0,0,0,0.05);
                padding: 1rem 1.5rem;
            }
            
            .admin-pagination {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            
            /* Модальное окно */
            .admin-modal .modal-content {
                border-radius: 20px;
                border: none;
                background: rgba(255,255,255,0.98);
                backdrop-filter: blur(20px);
            }
            
            .admin-modal .modal-header {
                background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
                border-bottom: 1px solid rgba(255,255,255,0.3);
            }
            
            @media (max-width: 768px) {
                .admin-title {
                    font-size: 2rem;
                }
                
                .admin-form-row {
                    flex-direction: column;
                }
                
                .admin-actions {
                    flex-wrap: wrap;
                }
                
                .card-actions {
                    margin-top: 1rem;
                }
                
                .search-group {
                    max-width: 100%;
                }
            }
        </style>
HTML;
    }

    private static function getAdminScripts(): string
    {
        return <<<HTML
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Поиск пользователей
                const searchInput = document.getElementById('userSearch');
                const userTableBody = document.getElementById('userTableBody');
                
                if (searchInput && userTableBody) {
                    searchInput.addEventListener('input', function(e) {
                        const searchTerm = e.target.value.toLowerCase();
                        const rows = userTableBody.getElementsByClassName('admin-user-row');
                        
                        Array.from(rows).forEach(function(row) {
                            const usernameElement = row.querySelector('.admin-username');
                            const emailElements = row.querySelectorAll('.text-muted');
                            
                            const username = usernameElement ? usernameElement.textContent.toLowerCase() : '';
                            const email = emailElements.length > 0 ? emailElements[0].textContent.toLowerCase() : '';
                            
                            if (username.includes(searchTerm) || email.includes(searchTerm)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                }
                
                // Анимация загрузки форм
                const forms = document.querySelectorAll('.admin-quick-form');
                forms.forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        const button = this.querySelector('button[type="submit"]');
                        if (button) {
                            const originalHTML = button.innerHTML;
                            
                            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                            button.disabled = true;
                            
                            setTimeout(function() {
                                button.innerHTML = originalHTML;
                                button.disabled = false;
                            }, 2000);
                        }
                    });
                });
            });
            
            function showUserDetails(userId) {
                // Загрузка деталей пользователя через AJAX
                fetch('/admin/user_details/' + userId)
                    .then(function(response) { return response.text(); })
                    .then(function(html) {
                        document.getElementById('userDetailsContent').innerHTML = html;
                        var modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                        modal.show();
                    })
                    .catch(function(error) {
                        console.error('Error loading user details:', error);
                        document.getElementById('userDetailsContent').innerHTML = 
                            '<div class="alert alert-danger">Ошибка загрузки данных</div>';
                        var modal = new bootstrap.Modal(document.getElementById('userDetailsModal'));
                        modal.show();
                    });
            }
            
            function exportUsers() {
                // Экспорт данных пользователей
                window.open('/admin/export_users', '_blank');
            }
            
            function showBulkActions() {
                alert('Функция массовых действий будет реализована в следующем обновлении!');
            }
            
            function toggleSearch() {
                const searchGroup = document.querySelector('.search-group');
                if (searchGroup) {
                    searchGroup.classList.toggle('d-none');
                }
            }
            
            // Анимация появления элементов
            if (typeof IntersectionObserver !== 'undefined') {
                const observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                        }
                    });
                }, { threshold: 0.1 });
                
                document.querySelectorAll('.admin-stat-card, .admin-user-row').forEach(function(el) {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = 'all 0.6s ease';
                    observer.observe(el);
                });
            }
        </script>
HTML;
    }

    private static function selected($val, $option): string
    {
        return (string)$val === (string)$option ? 'selected' : '';
    }
}