(function() {
    // Determine path prefix to app root dynamically
    let prefix = './';
    const path = window.location.pathname;
    if (path.includes('/dashboard_pinautomate/') || 
        path.includes('/novo_lote_pinautomate/') || 
        path.includes('/hist_rico_de_lotes_pinautomate/') || 
        path.includes('/configura_es_pinautomate/')) {
        prefix = '../';
    }

    // Prevent layout flashing while checking authentication
    const style = document.createElement('style');
    style.id = 'auth-gate-style';
    style.innerHTML = 'html, body { display: none !important; }';
    document.head.appendChild(style);

    // Request session verification
    fetch(prefix + 'check_auth.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Not authenticated');
            }
            return response.json();
        })
        .then(data => {
            if (!data.logged_in) {
                window.location.href = prefix + 'login.html';
                return;
            }

            // Route protection: only super_admin can access admin.html
            const isAdminPage = path.endsWith('admin.html');
            if (isAdminPage && data.role !== 'super_admin') {
                window.location.href = prefix + 'dashboard_pinautomate/code.html';
                return;
            }

            // Authenticated: reveal page content
            const gate = document.getElementById('auth-gate-style');
            if (gate) gate.remove();
            window.userSession = data;

            // Populate UI dynamically (checking if DOM is already loaded to avoid race conditions)
            const initUI = () => {
                // Update profile avatar image if set
                if (data.avatar_path) {
                    const avatarSrc = prefix + data.avatar_path;
                    const profileImgs = document.querySelectorAll('img[alt="User Profile Avatar"], img[alt="User profile"], img[alt="Profile"]');
                    profileImgs.forEach(img => {
                        img.src = avatarSrc;
                    });
                }

                // Update username displays
                const userNames = document.querySelectorAll('.font-label-md, p.font-label-md');
                userNames.forEach(el => {
                    if (el.textContent.trim() === 'Alex Rivera' || el.id === 'user-display-name') {
                        el.textContent = data.name || data.username;
                    }
                });

                // Update roles and plan information
                const userRoles = document.querySelectorAll('.text-secondary, p.text-secondary, .text-\\[12px\\]');
                userRoles.forEach(el => {
                    const text = el.textContent.trim();
                    if (text === 'Pro Member' || text === 'Pro Creator') {
                        el.textContent = 'Plano: ' + data.plan_name;
                    } else if (text === 'alex.rivera@design.com') {
                        el.textContent = '@' + data.username;
                    }
                });

                // Inject dynamic elements in sidebar
                const nav = document.querySelector('nav');
                if (nav) {
                    // Check if links are already injected to prevent duplication
                    if (!document.getElementById('admin-nav-link') && data.role === 'super_admin') {
                        const adminLink = document.createElement('a');
                        adminLink.id = 'admin-nav-link';
                        adminLink.href = prefix + 'admin.html';
                        
                        if (isAdminPage) {
                            adminLink.className = 'flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-primary font-bold border-r-4 border-primary bg-primary-container/10';
                        } else {
                            adminLink.className = 'flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-secondary hover:text-primary hover:bg-surface-container-high dark:hover:bg-surface-container';
                        }
                        
                        adminLink.innerHTML = `
                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' ${isAdminPage ? 1 : 0}, 'wght' 400, 'GRAD' 0, 'opsz' 24;">admin_panel_settings</span>
                            <span class="font-body-md">Painel Admin</span>
                        `;
                        nav.appendChild(adminLink);
                    }

                    if (!document.getElementById('logout-nav-link')) {
                        // Append logout button to sidebar navigation
                        const logoutLink = document.createElement('a');
                        logoutLink.id = 'logout-nav-link';
                        logoutLink.href = prefix + 'logout.php';
                        logoutLink.className = 'flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 text-error hover:bg-error-container/10 font-label-md text-label-md mt-4 border border-dashed border-error/20';
                        logoutLink.innerHTML = `
                            <span class="material-symbols-outlined text-error" style="font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;">logout</span>
                            <span class="font-body-md">Sair / Logout</span>
                        `;
                        nav.appendChild(logoutLink);
                    }
                }

                // Inject Logout Button in the Header Top Bar (TopRight)
                const headerRight = document.querySelector('header .flex.items-center.gap-4');
                if (headerRight && !document.getElementById('header-logout-btn')) {
                    const logoutBtn = document.createElement('a');
                    logoutBtn.id = 'header-logout-btn';
                    logoutBtn.href = prefix + 'logout.php';
                    logoutBtn.className = 'p-2 rounded-full text-secondary hover:text-error hover:bg-error-container/10 active:scale-95 transition-all flex items-center justify-center shrink-0';
                    logoutBtn.title = 'Sair / Logout';
                    logoutBtn.innerHTML = '<span class="material-symbols-outlined text-xl">logout</span>';
                    
                    const profileImg = headerRight.querySelector('img, div.rounded-full');
                    if (profileImg) {
                        headerRight.insertBefore(logoutBtn, profileImg);
                        // Add separator line
                        const sep = document.createElement('div');
                        sep.id = 'header-logout-sep';
                        sep.className = 'h-8 w-px bg-outline-variant/30 mx-1';
                        headerRight.insertBefore(sep, profileImg);
                    } else {
                        headerRight.appendChild(logoutBtn);
                    }
                }

                // Connect sync/refresh header buttons dynamically (robust matching & inline-block fix)
                const syncIcons = Array.from(document.querySelectorAll('span.material-symbols-outlined')).filter(el => el.textContent.trim() === 'sync' || el.dataset.icon === 'sync');
                syncIcons.forEach(icon => {
                    const btn = icon.closest('button') || icon;
                    if (!btn.dataset.syncConnected) {
                        btn.dataset.syncConnected = 'true';
                        btn.style.cursor = 'pointer';
                        btn.addEventListener('click', (e) => {
                            e.preventDefault();
                            icon.style.display = 'inline-block';
                            icon.classList.add('animate-spin');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        });
                    }
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initUI);
            } else {
                initUI();
            }
        })
        .catch(err => {
            console.error('Session validation error:', err);
            window.location.href = prefix + 'login.html';
        });
})();
