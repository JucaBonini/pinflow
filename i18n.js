// i18n.js - Multilingual Support for PinAutomate (PT, EN, ES)

const translations = {
    pt: {
        // Sidebar Navigation
        nav_dashboard: "Dashboard",
        nav_new_batch: "Novo Lote",
        nav_batch_history: "Histórico de Lotes",
        nav_settings: "Configurações",
        nav_upgrade: "Melhorar Plano",
        nav_pro_creator: "Criador Pro",
        nav_pro_member: "Membro Pro",
        
        // Search
        search_placeholder: "Pesquisar...",
        search_assets: "Pesquisar imagens...",
        search_batches: "Pesquisar lotes...",

        // Dashboard
        db_title: "Visão Geral do Dashboard",
        db_subtitle: "Desempenho da sua automação nos últimos 30 dias.",
        db_date_range: "1 Mai - 31 Mai",
        db_total_pins: "Total de Pins",
        db_active_batches: "Lotes Totais",
        db_success_posts: "Posts Feitos",
        db_failed_posts: "Posts Falhados",
        db_hero_title: "Dimensione sua presença visual instantaneamente.",
        db_hero_subtitle: "Automatize sua estratégia no Pinterest com upload de imagens em massa, agendamento por IA e otimização de painéis em tempo real.",
        db_hero_btn: "Criar Novo Lote",
        db_recent_activity: "Atividade Recente",
        db_view_all: "Ver Todos",
        db_api_status: "Status da API: Operacional",
        db_server_load: "Carga do Servidor: 14%",
        db_last_sync: "Última Sincronização: 3 minutos atrás",
        db_no_activity: "Nenhuma atividade recente. Crie um novo lote para começar.",
        db_activity_completed: "Finalizado",
        db_activity_pins: "Pins",
        db_activity_board: "Pasta",

        // New Batch (Bulk Upload)
        nb_title: "Upload de Imagens em Massa",
        nb_subtitle: "Crie lotes visuais de alto desempenho para automação do Pinterest.",
        nb_drag_drop: "Arraste e solte arquivos visuais aqui",
        nb_formats: "Suporta JPG, PNG, WEBP e MP4 (Máx 50 arquivos por lote)",
        nb_browse: "Procurar Arquivos Locais",
        nb_queue_title: "Fila de Envios",
        nb_clear_queue: "Limpar Fila",
        nb_no_images: "Nenhuma imagem na fila. Faça upload acima para começar.",
        nb_settings_title: "Configurações do Lote",
        nb_batch_name: "Nome do Lote",
        nb_keyword: "Palavra-Chave (IA)",
        nb_keyword_help: "Orienta a IA na geração das variações de títulos.",
        nb_dest_url: "Link de Destino (Obrigatório)",
        nb_dest_url_help: "Todos os pins criados apontarão para este link.",
        nb_board: "Pasta do Pinterest (Board)",
        nb_base_url: "URL Base das Imagens (Contabo)",
        nb_btn_clear: "Limpar Fila",
        nb_btn_generate: "Gerar ZIP + CSV",
        nb_status_idle: "Aguardando Imagens",
        nb_status_processing: "Processando imagens e IA...",
        nb_status_ready: "Imagens Prontas para Exportar",
        nb_status_packing: "Empacotando arquivos...",
        nb_status_success: "Exportação concluída com sucesso!",
        csv_title: "Título",
        csv_description: "Descrição",
        csv_link: "Link",
        csv_media_url: "URL da mídia",
        csv_board: "Pasta do Pinterest",

        // Edit Modal
        modal_title: "Editar Detalhes do Pin",
        modal_top1: "Texto Topo Linha 1",
        modal_top2: "Texto Topo Linha 2",
        modal_card_title: "Título do Card Branco",
        modal_card_sub: "Subtítulo do Card Branco",
        modal_desc: "Descrição (Pinterest CSV)",
        modal_btn_cancel: "Cancelar",
        modal_btn_save: "Salvar",

        // Batch History
        hist_title: "Histórico de Lotes",
        hist_subtitle: "Monitore e gerencie seus fluxos visuais automatizados.",
        hist_total_batches: "Lotes Totais",
        hist_processed_images: "Imagens Geradas",
        hist_th_name: "Nome do Lote",
        hist_th_date: "Data de Criação",
        hist_th_count: "Total de Imagens",
        hist_th_dest: "Link de Destino",
        hist_th_status: "Status",
        hist_th_actions: "Ações",
        hist_status_saved: "Salvo",
        hist_btn_delete: "Excluir Lote",
        hist_showing: "Exibindo",
        hist_batches_count: "lote(s)",
        hist_no_batches: "Nenhum lote gerado até o momento.",

        // Settings
        settings_title: "Configurações do Sistema",
        settings_subtitle: "Gerencie suas preferências de perfil e ecossistema de automação do Pinterest.",
        settings_api_integration: "Integração do Pinterest",
        settings_live_conn: "Conexão Ativa",
        settings_disconnect: "Desconectar Conta",
        settings_api_status: "Status da API",
        settings_api_auth: "Autenticado (V5)",
        settings_last_sync: "Última Sincronização",
        settings_plan_status: "Status do Plano",
        settings_plan_desc: "Faturado mensalmente a $29.00/mês. Próximo ciclo em 12 de Nov.",
        settings_upgrade_agency: "Melhorar para Agência",
        settings_limit: "Limite do Lote",
        settings_api_defaults: "Padrões de Automação e API",
        settings_openai_key: "OpenAI API Key (ChatGPT)",
        settings_openai_desc: "Usada para gerar variações de títulos/subtítulos no navegador usando o GPT-4o-mini.",
        settings_dest_url_desc: "Link de destino padrão para Pins gerados.",
        settings_base_url_desc: "Adicionada antes dos nomes dos arquivos no CSV.",
        settings_board_desc: "Nome da pasta padrão no Pinterest.",
        settings_profile_security: "Segurança e Perfil",
        settings_password: "Senha Atual",
        settings_btn_password: "Alterar Senha",
        settings_danger_zone: "Zona de Perigo",
        settings_deactivate: "Desativar conta",
        settings_need_help: "Precisa de ajuda com as configurações?",
        settings_support_desc: "Nossos especialistas estão disponíveis 24/7 para clientes Enterprise.",
        settings_view_docs: "Ver Docs",
        settings_contact: "Falar com Suporte",
        settings_btn_discard: "Descartar Alterações",
        settings_btn_save: "Salvar Configuração",
        settings_saved_alert: "Configurações salvas com sucesso!",
        settings_discard_confirm: "Deseja descartar as alterações?",

        // JS Alerts & Messages
        alert_no_keyword: "Por favor, digite uma Palavra-Chave antes de fazer o upload para orientar a IA.",
        alert_limit_reached: "Limite máximo de 200 imagens atingido.",
        alert_openai_error: "Falha ao conectar com o OpenAI API (ChatGPT). Usando gerador local de fallback.",
        alert_empty_queue: "A fila está vazia. Adicione algumas imagens primeiro.",
        alert_invalid_dest: "Por favor, defina um Link de Destino válido.",
        alert_export_success: "Lote exportado! Lembre-se de subir as imagens geradas na Contabo e depois importar o arquivo CSV no Pinterest.",
        alert_clear_confirm: "Tem certeza que deseja limpar toda a fila?",
        alert_delete_batch_confirm: "Tem certeza que deseja excluir este lote do histórico?",
        nav_visual_automation: "Automação Visual",
        placeholder_batch_name: "Ex: Lote Quentão Verão",
        placeholder_batch_keyword: "Ex: Quentão Evangélico",
        placeholder_batch_board: "Ex: Receitas Festa Junina",
        placeholder_search_quick: "Busca rápida...",
        canvas_cta: "VER RECEITA PASSO A PASSO ➔",
        fallback_prefixes: ["LEGÍTIMO", "DELICIOSO", "NOVA RECEITA", "PASSO A PASSO", "FÁCIL E RÁPIDO", "RECEITA DE", "APRENDA JÁ", "INCRÍVEL", "CASEIRO"],
        fallback_subtitles: [
            "Receita Super Prática e Gostosa",
            "Fácil de Fazer e Super Encorpado",
            "A Melhor Receita para seu Dia",
            "Segredo Revelado Passo a Passo",
            "Como Fazer de Forma Simples",
            "Perfeito para Compartilhar",
            "Para Arrasar em Qualquer Ocasião",
            "Fórmula Tradicional Exclusiva",
            "Sem Complicação e Muito Rápido"
        ],
        fallback_descriptions: [
            "Veja como preparar {keyword} de maneira simples e deliciosa. O segredo completo revelado passo a passo para você arrasar. Acesse agora!",
            "Receita incrível de {keyword} que todo mundo vai amar. Ingredientes fáceis, preparo rápido e resultado perfeito. Confira leitor!",
            "Procurando a melhor receita de {keyword}? Você acaba de encontrar! Um guia prático e detalhado sem erro. Clique para ver os detalhes.",
            "Aprenda o passo a passo de como fazer {keyword} super gostoso e encorpado. Uma receita clássica que não falha. Acesse o post!",
            "Dicas valiosas e receita definitiva de {keyword} para você testar hoje mesmo. Muito prático de preparar. Confira no blog."
        ],
        update_available: "Nova versão disponível (v{version})!",
        update_btn: "Atualizar Agora",
        update_progress: "Atualizando sistema...",
        update_success: "Sistema atualizado com sucesso! Recarregando...",
        update_failed: "Falha na atualização: {error}",
        settings_git_title: "Versionamento e Atualização",
        settings_git_owner: "Proprietário do GitHub (User)",
        settings_git_repo: "Nome do Repositório",
        settings_git_branch: "Branch (Padrão: main)",
        settings_git_desc: "Configurações do repositório Git para verificar novas versões e atualizar o painel automaticamente."
    },
    en: {
        // Sidebar Navigation
        nav_dashboard: "Dashboard",
        nav_new_batch: "New Batch",
        nav_batch_history: "Batch History",
        nav_settings: "Settings",
        nav_upgrade: "Upgrade Plan",
        nav_pro_creator: "Pro Creator",
        nav_pro_member: "Pro Member",
        
        // Search
        search_placeholder: "Search...",
        search_assets: "Search assets...",
        search_batches: "Search batches...",

        // Dashboard
        db_title: "Dashboard Overview",
        db_subtitle: "Your automation performance for the last 30 days.",
        db_date_range: "May 1 - May 31",
        db_total_pins: "Total Pins",
        db_active_batches: "Total Batches",
        db_success_posts: "Successful Posts",
        db_failed_posts: "Failed Posts",
        db_hero_title: "Scale Your Visual Presence Instantly.",
        db_hero_subtitle: "Automate your Pinterest strategy with multi-image batch uploads, AI-driven scheduling, and real-time board optimization.",
        db_hero_btn: "Create New Batch",
        db_recent_activity: "Recent Activity",
        db_view_all: "View All",
        db_api_status: "API Status: Operational",
        db_server_load: "Server Load: 14%",
        db_last_sync: "Last Sync: 3 minutes ago",
        db_no_activity: "No recent activity. Create a new batch to get started.",
        db_activity_completed: "Completed",
        db_activity_pins: "Pins",
        db_activity_board: "Board",

        // New Batch (Bulk Upload)
        nb_title: "Bulk Image Upload",
        nb_subtitle: "Create high-performance visual batches for Pinterest automation.",
        nb_drag_drop: "Drag & Drop visual assets here",
        nb_formats: "Supports JPG, PNG, WEBP and MP4 (Max 50 files per batch)",
        nb_browse: "Browse Local Files",
        nb_queue_title: "Queue Preview",
        nb_clear_queue: "Clear Queue",
        nb_no_images: "No images in queue. Upload assets above to start.",
        nb_settings_title: "Batch Settings",
        nb_batch_name: "Batch Name",
        nb_keyword: "Keyword (AI)",
        nb_keyword_help: "Guides the AI in generating title variations.",
        nb_dest_url: "Destination URL (Required)",
        nb_dest_url_help: "All generated pins will point to this URL.",
        nb_board: "Pinterest Board",
        nb_base_url: "Image Base URL (Contabo)",
        nb_btn_clear: "Clear Queue",
        nb_btn_generate: "Generate ZIP + CSV",
        nb_status_idle: "Waiting for Images",
        nb_status_processing: "Processing images & AI...",
        nb_status_ready: "Images Ready to Export",
        nb_status_packing: "Packing files...",
        nb_status_success: "Export completed successfully!",
        csv_title: "Title",
        csv_description: "Description",
        csv_link: "Link",
        csv_media_url: "Media URL",
        csv_board: "Pinterest board",

        // Edit Modal
        modal_title: "Edit Pin Details",
        modal_top1: "Top Line 1 Text",
        modal_top2: "Top Line 2 Text",
        modal_card_title: "White Card Title",
        modal_card_sub: "White Card Subtitle",
        modal_desc: "Description (Pinterest CSV)",
        modal_btn_cancel: "Cancel",
        modal_btn_save: "Save",

        // Batch History
        hist_title: "Batch History",
        hist_subtitle: "Monitor and manage your automated visual workflows.",
        hist_total_batches: "Total Batches",
        hist_processed_images: "Images Generated",
        hist_th_name: "Batch Name",
        hist_th_date: "Date Created",
        hist_th_count: "Image Count",
        hist_th_dest: "Destination URL",
        hist_th_status: "Status",
        hist_th_actions: "Actions",
        hist_status_saved: "Saved",
        hist_btn_delete: "Delete Batch",
        hist_showing: "Showing",
        hist_batches_count: "batch(es)",
        hist_no_batches: "No batches generated yet.",

        // Settings
        settings_title: "System Settings",
        settings_subtitle: "Manage your Pinterest automation ecosystem and profile preferences.",
        settings_api_integration: "Pinterest Integration",
        settings_live_conn: "Live Connection",
        settings_disconnect: "Disconnect Account",
        settings_api_status: "API Status",
        settings_api_auth: "Authenticated (V5)",
        settings_last_sync: "Last Sync",
        settings_plan_status: "Plan Status",
        settings_plan_desc: "Billed monthly at $29.00/mo. Next billing cycle on Nov 12.",
        settings_upgrade_agency: "Upgrade to Agency",
        settings_limit: "Batch Limit",
        settings_api_defaults: "Automation & API Defaults",
        settings_openai_key: "OpenAI API Key (ChatGPT)",
        settings_openai_desc: "Used for generating creative title/subtitle variations client-side using GPT-4o-mini.",
        settings_dest_url_desc: "Default destination link for generated Pins.",
        settings_base_url_desc: "Prepended to filenames in the CSV.",
        settings_board_desc: "Default Board name in Pinterest.",
        settings_profile_security: "Security & Profile",
        settings_password: "Current Password",
        settings_btn_password: "Change Password",
        settings_danger_zone: "Danger Zone",
        settings_deactivate: "Deactivate account",
        settings_need_help: "Need help with your configurations?",
        settings_support_desc: "Our automation experts are available 24/7 for Enterprise customers.",
        settings_view_docs: "View Docs",
        settings_contact: "Contact Support",
        settings_btn_discard: "Discard Changes",
        settings_btn_save: "Save Configuration",
        settings_saved_alert: "Settings saved successfully!",
        settings_discard_confirm: "Do you want to discard your changes?",

        // JS Alerts & Messages
        alert_no_keyword: "Please enter a Keyword before uploading to guide the AI.",
        alert_limit_reached: "Maximum limit of 200 images reached.",
        alert_openai_error: "Failed to connect to OpenAI API (ChatGPT). Using local fallback generator.",
        alert_empty_queue: "The queue is empty. Add some images first.",
        alert_invalid_dest: "Please define a valid Destination Link.",
        alert_export_success: "Batch exported! Remember to upload the generated images to Contabo and then import the CSV file into Pinterest.",
        alert_clear_confirm: "Are you sure you want to clear the entire queue?",
        alert_delete_batch_confirm: "Are you sure you want to delete this batch from history?",
        nav_visual_automation: "Visual Automation",
        placeholder_batch_name: "e.g., Summer Batch",
        placeholder_batch_keyword: "e.g., Hot Mulled Wine",
        placeholder_batch_board: "e.g., Summer Recipes",
        placeholder_search_quick: "Quick search...",
        canvas_cta: "VIEW RECIPE STEP BY STEP ➔",
        fallback_prefixes: ["GENUINE", "DELICIOUS", "NEW RECIPE", "STEP BY STEP", "EASY & QUICK", "RECIPE FOR", "LEARN NOW", "AMAZING", "HOMEMADE"],
        fallback_subtitles: [
            "Super Practical and Tasty Recipe",
            "Easy to Make and Super Rich",
            "The Best Recipe for Your Day",
            "Secret Revealed Step by Step",
            "How to Make It Simple",
            "Perfect for Sharing",
            "To Rock Any Occasion",
            "Exclusive Traditional Formula",
            "Hassle-Free and Very Fast"
        ],
        fallback_descriptions: [
            "See how to prepare {keyword} in a simple and delicious way. The complete secret revealed step by step for you to succeed. Access now!",
            "Amazing {keyword} recipe that everyone will love. Easy ingredients, quick preparation, and perfect results. Check it out!",
            "Looking for the best {keyword} recipe? You just found it! A practical and detailed guide with no mistakes. Click to see details.",
            "Learn step by step how to make super tasty and rich {keyword}. A classic recipe that never fails. Access the post!",
            "Valuable tips and definitive recipe for {keyword} for you to try today. Very practical to prepare. Check it out on the blog."
        ],
        update_available: "New version available (v{version})!",
        update_btn: "Update Now",
        update_progress: "Updating system...",
        update_success: "System updated successfully! Reloading...",
        update_failed: "Update failed: {error}",
        settings_git_title: "Versioning & Update",
        settings_git_owner: "GitHub Owner",
        settings_git_repo: "Repository Name",
        settings_git_branch: "Branch (Default: main)",
        settings_git_desc: "Git repository settings to verify new versions and update the dashboard automatically."
    },
    es: {
        // Sidebar Navigation
        nav_dashboard: "Tablero",
        nav_new_batch: "Nuevo Lote",
        nav_batch_history: "Historial de Lotes",
        nav_settings: "Configuración",
        nav_upgrade: "Mejorar Plan",
        nav_pro_creator: "Creador Pro",
        nav_pro_member: "Miembro Pro",
        
        // Search
        search_placeholder: "Buscar...",
        search_assets: "Buscar imágenes...",
        search_batches: "Buscar lotes...",

        // Dashboard
        db_title: "Resumen del Tablero",
        db_subtitle: "Rendimiento de su automatización en los últimos 30 días.",
        db_date_range: "1 May - 31 May",
        db_total_pins: "Total de Pins",
        db_active_batches: "Lotes Totales",
        db_success_posts: "Publicaciones Exitosas",
        db_failed_posts: "Publicaciones Fallidas",
        db_hero_title: "Dimensione su presencia visual al instante.",
        db_hero_subtitle: "Automatice su estrategia de Pinterest con carga de imágenes en masa, programación por IA y optimización de tableros en tiempo real.",
        db_hero_btn: "Crear Nuevo Lote",
        db_recent_activity: "Actividad Reciente",
        db_view_all: "Ver Todos",
        db_api_status: "Estado de la API: Operacional",
        db_server_load: "Carga del Servidor: 14%",
        db_last_sync: "Última Sincronización: Hace 3 minutos",
        db_no_activity: "Ninguna actividad reciente. Cree un nuevo lote para comenzar.",
        db_activity_completed: "Completado",
        db_activity_pins: "Pins",
        db_activity_board: "Tablero",

        // New Batch (Bulk Upload)
        nb_title: "Carga de Imágenes en Masa",
        nb_subtitle: "Cree lotes visuales de alto rendimiento para la automatización de Pinterest.",
        nb_drag_drop: "Arrastre y suelte archivos visuales aquí",
        nb_formats: "Soporta JPG, PNG, WEBP y MP4 (Máx 50 archivos por lote)",
        nb_browse: "Buscar Archivos Locales",
        nb_queue_title: "Cola de Envíos",
        nb_clear_queue: "Limpar Cola",
        nb_no_images: "Ninguna imagen en cola. Cargue archivos arriba para comenzar.",
        nb_settings_title: "Configuraciones del Lote",
        nb_batch_name: "Nombre del Lote",
        nb_keyword: "Palabra Clave (IA)",
        nb_keyword_help: "Guía a la IA en la generación de variaciones de títulos.",
        nb_dest_url: "Enlace de Destino (Obligatorio)",
        nb_dest_url_help: "Todos los pines creados apuntarán a este enlace.",
        nb_board: "Tablero de Pinterest (Board)",
        nb_base_url: "URL Base de Imágenes (Contabo)",
        nb_btn_clear: "Limpiar Cola",
        nb_btn_generate: "Generar ZIP + CSV",
        nb_status_idle: "Esperando Imágenes",
        nb_status_processing: "Procesando imágenes e IA...",
        nb_status_ready: "Imágenes Listas para Exportar",
        nb_status_packing: "Empaquetando archivos...",
        nb_status_success: "¡Exportación completada con éxito!",
        csv_title: "Título",
        csv_description: "Descripción",
        csv_link: "Enlace",
        csv_media_url: "URL de la imagen",
        csv_board: "Tablero",

        // Edit Modal
        modal_title: "Editar Detalles del Pin",
        modal_top1: "Texto Superior Línea 1",
        modal_top2: "Texto Superior Línea 2",
        modal_card_title: "Título de Tarjeta Blanca",
        modal_card_sub: "Subtítulo de Tarjeta Blanca",
        modal_desc: "Descripción (Pinterest CSV)",
        modal_btn_cancel: "Cancelar",
        modal_btn_save: "Guardar",

        // Batch History
        hist_title: "Historial de Lotes",
        hist_subtitle: "Monitoree y gestione sus flujos visuales automatizados.",
        hist_total_batches: "Lotes Totales",
        hist_processed_images: "Imágenes Generadas",
        hist_th_name: "Nombre del Lote",
        hist_th_date: "Fecha de Creación",
        hist_th_count: "Total de Imágenes",
        hist_th_dest: "Enlace de Destino",
        hist_th_status: "Estado",
        hist_th_actions: "Acciones",
        hist_status_saved: "Guardado",
        hist_btn_delete: "Eliminar Lote",
        hist_showing: "Mostrando",
        hist_batches_count: "lote(s)",
        hist_no_batches: "Ningún lote generado hasta el momento.",

        // Settings
        settings_title: "Configuraciones del Sistema",
        settings_subtitle: "Gestione sus preferencias de perfil y ecosistema de automatización de Pinterest.",
        settings_api_integration: "Integración de Pinterest",
        settings_live_conn: "Conexión Activa",
        settings_disconnect: "Desconectar Cuenta",
        settings_api_status: "Estado de la API",
        settings_api_auth: "Autenticado (V5)",
        settings_last_sync: "Última Sincronización",
        settings_plan_status: "Estado del Plan",
        settings_plan_desc: "Facturado mensualmente a $29.00/mes. Siguiente ciclo el 12 de Nov.",
        settings_upgrade_agency: "Mejorar a Agencia",
        settings_limit: "Límite del Lote",
        settings_api_defaults: "Valores por Defecto de Automatización y API",
        settings_openai_key: "OpenAI API Key (ChatGPT)",
        settings_openai_desc: "Usada para generar variaciones de títulos/subtítulos en el navegador usando GPT-4o-mini.",
        settings_dest_url_desc: "Enlace de destino predeterminado para los Pins generados.",
        settings_base_url_desc: "Añadida antes de los nombres de archivos en el CSV.",
        settings_board_desc: "Nombre del tablero predeterminado en Pinterest.",
        settings_profile_security: "Seguridad y Perfil",
        settings_password: "Contraseña Actual",
        settings_btn_password: "Cambiar Contraseña",
        settings_danger_zone: "Zona de Peligro",
        settings_deactivate: "Desactivar cuenta",
        settings_need_help: "¿Necesita ayuda con la configuración?",
        settings_support_desc: "Nuestros expertos en automatización están disponibles 24/7 para clientes Enterprise.",
        settings_view_docs: "Ver Docs",
        settings_contact: "Hablar con Soporte",
        settings_btn_discard: "Descartar Cambios",
        settings_btn_save: "Guardar Configuración",
        settings_saved_alert: "¡Configuración guardada con éxito!",
        settings_discard_confirm: "¿Desea descartar los cambios?",

        // JS Alerts & Messages
        alert_no_keyword: "Por favor, introduzca una Palabra Clave antes de subir para guiar a la IA.",
        alert_limit_reached: "Límite máximo de 200 imágenes alcanzado.",
        alert_openai_error: "Error al conectar con la API de OpenAI (ChatGPT). Usando el generador local de respaldo.",
        alert_empty_queue: "La cola está vacía. Añada algunas imágenes primero.",
        alert_invalid_dest: "Por favor, defina un Enlace de Destino válido.",
        alert_export_success: "¡Lote exportado! Recuerde subir las imágenes generadas a Contabo y luego importar el archivo CSV en Pinterest.",
        alert_clear_confirm: "¿Está seguro de que desea limpiar toda la cola?",
        alert_delete_batch_confirm: "¿Está seguro de que desea eliminar este lote del historial?",
        nav_visual_automation: "Automatización Visual",
        placeholder_batch_name: "Ej: Lote Verano",
        placeholder_batch_keyword: "Ej: Vino Caliente",
        placeholder_batch_board: "Ej: Recetas de Fiesta",
        placeholder_search_quick: "Búsqueda rápida...",
        canvas_cta: "VER RECETA PASO A PASO ➔",
        fallback_prefixes: ["LEGÍTIMO", "DELICIOSO", "NUEVA RECETA", "PASO A PASO", "FÁCIL Y RÁPIDO", "RECETA DE", "APRENDE YA", "INCREÍBLE", "CASERO"],
        fallback_subtitles: [
            "Receta Súper Práctica y Sabrosa",
            "Fácil de Hacer y Súper Consistente",
            "La Mejor Receta para tu Día",
            "Secreto Revelado Paso a Paso",
            "Cómo Hacerlo de Forma Sencilla",
            "Perfecto para Compartir",
            "Para Triunfar en Cualquier Ocasión",
            "Fórmula Tradicional Exclusiva",
            "Sin Complicaciones y Muy Rápido"
        ],
        fallback_descriptions: [
            "Mira cómo preparar {keyword} de manera sencilla y deliciosa. El secreto completo revelado paso a paso para que triunfes. ¡Accede ahora!",
            "Receta increíble de {keyword} que a todos les encantará. Ingredientes fáciles, preparación rápida y resultado perfecto. ¡Pruébalo!",
            "¿Buscas la mejor receta de {keyword}? ¡La acabas de encontrar! Una guía práctica y detallada sin errores. Haz clic para ver los detalles.",
            "Aprende el paso a paso de cómo hacer {keyword} súper sabroso y consistente. Una receta clásica que no falla. ¡Accede al post!",
            "Consejos valiosos y receta definitiva de {keyword} para que la pruebes hoy mismo. Muy práctica de preparar. Compruébalo en el blog."
        ],
        update_available: "¡Nueva versión disponible (v{version})!",
        update_btn: "Actualizar Ahora",
        update_progress: "Actualizando sistema...",
        update_success: "¡Sistema actualizado con éxito! Recargando...",
        update_failed: "Error al actualizar: {error}",
        settings_git_title: "Versionamiento y Actualización",
        settings_git_owner: "Propietario de GitHub (User)",
        settings_git_repo: "Nombre del Repositorio",
        settings_git_branch: "Rama (Predeterminada: main)",
        settings_git_desc: "Configuración del repositorio Git para verificar nuevas versiones y actualizar el tablero automáticamente."
    }
};

// Global Translate function
function t(key) {
    const lang = localStorage.getItem('pinautomate_lang') || 'pt';
    const langDict = translations[lang] || translations['pt'];
    return langDict[key] || key;
}

// Function to translate all marked DOM elements
function translateDOM() {
    const lang = localStorage.getItem('pinautomate_lang') || 'pt';
    const langDict = translations[lang] || translations['pt'];

    // Translate texts
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        if (langDict[key]) {
            el.innerText = langDict[key];
        }
    });

    // Translate inputs/textareas placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
        const key = el.getAttribute('data-i18n-placeholder');
        if (langDict[key]) {
            el.setAttribute('placeholder', langDict[key]);
        }
    });
}

// Function to inject language selector dropdown in the header dynamically
function injectLanguageSelector() {
    const headerActions = document.querySelector('header div.flex.items-center.gap-4');
    if (!headerActions) return;

    // Check if selector already exists
    if (document.getElementById('lang-selector-container')) return;

    const currentLang = localStorage.getItem('pinautomate_lang') || 'pt';

    const container = document.createElement('div');
    container.id = 'lang-selector-container';
    container.className = 'relative flex items-center';

    container.innerHTML = `
        <select id="lang-selector" class="bg-surface-container-low border border-outline-variant/30 text-on-surface rounded-lg px-2.5 py-1 text-[13px] font-semibold focus:ring-1 focus:ring-primary focus:border-primary outline-none cursor-pointer transition-all hover:bg-surface-container-high">
            <option value="pt" ${currentLang === 'pt' ? 'selected' : ''}>PT 🇧🇷</option>
            <option value="en" ${currentLang === 'en' ? 'selected' : ''}>EN 🇺🇸</option>
            <option value="es" ${currentLang === 'es' ? 'selected' : ''}>ES 🇪🇸</option>
        </select>
    `;

    // Inject before the user profile image or notification buttons
    headerActions.insertBefore(container, headerActions.firstChild);

    // Bind change event
    document.getElementById('lang-selector').addEventListener('change', (e) => {
        const newLang = e.target.value;
        localStorage.setItem('pinautomate_lang', newLang);
        
        // Re-translate page
        translateDOM();

        // Dispatch custom event if any page script needs to listen to language changes
        window.dispatchEvent(new CustomEvent('langChanged', { detail: newLang }));

        // Reload some components if necessary (like Canvas renders in Bulk Upload)
        if (typeof renderQueue === 'function') {
            // Re-render bulk upload canvas renders using translated text template
            renderQueue();
        }
    });
}

// Auto-run translation on load
document.addEventListener('DOMContentLoaded', () => {
    translateDOM();
    injectLanguageSelector();
});
