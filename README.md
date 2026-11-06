# Portal de Notícias Nova Aliança - Gerador de Folders e Boletins Diários

Sistema completo desenvolvido em **Laravel**, **Laravel Breeze** e **MySQL** para criação, gerenciamento e exportação de folders informativos diários para o **Portal de Notícias Nova Aliança ("Aconteceu virou notícia. 24 horas no ar.")**.

---

## 🚀 Principais Funcionalidades

1. **Layout Fiel e Moderno**:
   - Inspirado no formato de boletins informativos populares (como o modelo de referência anexado), porém 100% repaginado para a identidade visual do **Portal Nova Aliança** (azul marinho, vermelho vivo, branco e dourado).
   - Dimensões padrão em alta definição (**1080 x 1080 px**, formato quadrado ideal para Instagram Feed, WhatsApp Status, Telegram e redes sociais).
   - Suporte a impressão direta e exportação para PDF.

2. **⚡ Puxador Rápido de Notícias por Link (Scraper Inteligente)**:
   - Cole o link de qualquer notícia (G1, UOL, CNN, Folha, portais locais, etc.).
   - O sistema extrai automaticamente:
     - Manchete / Título (com limpeza de sufixos de portais)
     - Resumo / Lead da matéria
     - Foto de capa (OpenGraph/Twitter Cards)
     - Download automático da foto para armazenamento local (`storage/app/public/news`) para **eliminar problemas de CORS ao gerar imagens pelo navegador**
     - Nome da fonte e data
   - Você pode ajustar o texto, trocar a foto ou preencher manualmente se preferir.

3. **Criação e Gestão de Edições**:
   - Criação de novos folders com escolha de data (formatação automática em português: *"06 DE NOVEMBRO DE 2026 | SEXTA-FEIRA"*).
   - Divisão em duas colunas/seções com cores e títulos personalizáveis (ex: *"CIDADE & METROPOLITANA"* e *"GOIÁS & BRASIL"*).
   - Até 8 notícias completas por edição (4 por coluna).
   - Rodapé completo do Portal Nova Aliança com WhatsApp oficial, Instagram, site e slogan.
   - **Duplicação com 1 Clique**: Duplique a edição de ontem para criar a de hoje mantendo configurações e contatos.

4. **Exportação de Imagem em Alta Resolução**:
   - Botão **"Baixar Imagem (PNG)"** renderiza o flyer em escala 2x (retina, 2160x2160) com fontes nítidas e exporta direto para a máquina do usuário.
   - **Link Público**: Cada folder possui uma URL pública exclusiva (ex: `/folder/boletim-portal-nova-alianca-...`) para compartilhamento nas redes sociais e leitura online.

---

## 🛠️ Stack Tecnológica

- **Backend**: PHP 8.3+, Laravel 12/13
- **Autenticação**: Laravel Breeze (Blade + Tailwind)
- **Banco de Dados**: MySQL (banco `nova_alianca_noticias`)
- **Scraper**: GuzzleHttp + Symfony DomCrawler & CssSelector
- **Frontend**: Tailwind CSS, Alpine.js, Vite
- **Exportação Gráfica**: HTML5 Canvas / `html2canvas`

---

## 🔑 Acesso Padrão ao Sistema

- **URL Local**: [http://localhost:8000](http://localhost:8000)
- **E-mail**: `admin@novaalianca.com`
- **Senha**: `password`

*(O sistema já possui um botão facilitador na tela de login para preenchimento rápido das credenciais).*

---

## ⚙️ Configuração do Banco de Dados (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3308
DB_DATABASE=nova_alianca_noticias
DB_USERNAME=root
DB_PASSWORD=
```

---

## 🏃 Como Rodar o Projeto

Caso precise reiniciar os serviços:

1. **Iniciar Servidor Laravel**:
   ```bash
   php artisan serve --port=8000
   ```

2. **Compilar Assets (já compilados)**:
   ```bash
   npm run build
   ```

3. **Executar Testes Automatizados**:
   ```bash
   php artisan test
   ```
