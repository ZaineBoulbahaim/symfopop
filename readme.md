# 📘 SymfoPop - Mercat de Segona Mà

Aplicació web de mercat de segona mà desenvolupada amb **Symfony 7**, **Doctrine ORM**, **Twig** i **Bootstrap 5**.

---

## 🚀 Tecnologies Utilitzades

- **PHP 8.1+**
- **Symfony 7** (webapp skeleton)
- **Doctrine ORM** (base de dades i migracions)
- **Twig** (plantilles)
- **Bootstrap 5** (disseny responsive)
- **MariaDB / MySQL** (base de dades)
- **Faker** (dades de prova)

---

## ✅ Funcionalitats Implementades

| Funcionalitat | Descripció | Estat |
|---|---|---|
| 👤 Registre | Usuaris poden crear compte amb validacions | ✅ |
| 🔐 Login/Logout | Sistema d'autenticació amb remember me | ✅ |
| 🛒 Llistat públic | Veure tots els productes (accessible sense login) | ✅ |
| 👁️ Detall producte | Informació completa del producte | ✅ |
| ➕ Crear producte | Publicar nou producte (requereix login) | ✅ |
| ✏️ Editar producte | Modificar producte propi | ✅ |
| 🗑️ Esborrar producte | Eliminar producte propi amb confirmació | ✅ |
| 📦 Els meus productes | Llistat personal de productes | ✅ |
| 👤 Editar perfil | Modificar dades personals | ✅ |
| 🔒 Validació permisos | Només el propietari pot editar/esborrar | ✅ |
| 💬 Missatges flash | Feedback visual per totes les accions | ✅ |
| 🛡️ Protecció CSRF | Tokens CSRF en formularis sensibles | ✅ |

---

## 📁 Estructura del Projecte
```
symfopop/
├── config/
│   ├── packages/
│   │   ├── doctrine.yaml
│   │   ├── framework.yaml
│   │   ├── security.yaml
│   │   └── twig.yaml
│   └── routes.yaml
├── src/
│   ├── Controller/
│   │   ├── HomeController.php
│   │   ├── ProductController.php
│   │   ├── ProfileController.php
│   │   ├── RegistrationController.php
│   │   └── SecurityController.php
│   ├── DataFixtures/
│   │   └── AppFixtures.php
│   ├── Entity/
│   │   ├── Product.php
│   │   └── User.php
│   ├── Form/
│   │   ├── ProductType.php
│   │   └── RegistrationFormType.php
│   ├── Repository/
│   │   ├── ProductRepository.php
│   │   └── UserRepository.php
│   └── Security/
│       └── LoginFormAuthenticator.php
├── templates/
│   ├── _partials/
│   │   ├── _flash_messages.html.twig
│   │   ├── _product_card.html.twig
│   │   └── _product_form.html.twig
│   ├── base.html.twig
│   ├── product/
│   │   ├── index.html.twig
│   │   ├── show.html.twig
│   │   ├── new.html.twig
│   │   └── edit.html.twig
│   ├── registration/
│   │   └── register.html.twig
│   └── security/
│       └── login.html.twig
└── migrations/
```

---

## ⚙️ Instal·lació

### Requisits previs
- PHP >= 8.1
- Composer
- Symfony CLI
- MySQL / MariaDB (XAMPP recomanat)
- Git

### Pas 1 - Clonar el repositori
```bash
git clone https://github.com/EL_TEU_USUARI/symfopop.git
cd symfopop
```

### Pas 2 - Instal·lar dependències
```bash
composer install
```

### Pas 3 - Configurar la base de dades
Copia el fitxer d'exemple i edita les credencials:
```bash
cp .env.example .env
```

Edita el fitxer `.env` amb les teves credencials:
```env
APP_ENV=dev
APP_SECRET=a1b2c3d4e5f6a1b2c3d4e5f6a1b2c3d4
DATABASE_URL="mysql://root:@127.0.0.1:3306/symfopop?serverVersion=mariadb-10.4.32&charset=utf8mb4"
MESSENGER_TRANSPORT_DSN=sync://
MAILER_DSN=null://null
```

> ⚠️ Canvia `serverVersion` segons la teva versió de MariaDB/MySQL. Per saber-la: `mysql -u root -e "SELECT VERSION();"`

### Pas 4 - Crear la base de dades i executar migracions
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Pas 5 - Carregar les dades de prova
```bash
php bin/console doctrine:fixtures:load
```

Això crearà **5 usuaris** i **20 productes** de prova.

### Pas 6 - Iniciar el servidor
```bash
symfony serve
```

Obre el navegador a: **https://127.0.0.1:8000**

---

## 👥 Usuaris de Prova

Tots els usuaris de prova tenen la mateixa contrasenya:

| Nom | Email | Contrasenya |
|-----|-------|-------------|
| Marc Puig | marc.puig@gmail.com | password123 |
| Laura Vidal | laura.vidal@gmail.com | password123 |
| Jordi Mas | jordi.mas@gmail.com | password123 |
| Anna Soler | anna.soler@gmail.com | password123 |
| Pere Ferrer | pere.ferrer@gmail.com | password123 |

---

## 🔒 Seguretat Implementada

- **Contrasenyes hashejades** amb bcrypt/argon2
- **Tokens CSRF** en formularis d'esborrat i logout
- **Validació de permisos** al controlador (només el propietari pot editar/esborrar)
- **Protecció de rutes** amb `#[IsGranted('ROLE_USER')]`
- **Validació de dades** amb assertions de Symfony
- **Escapament automàtic** de variables a Twig (protecció XSS)

---

## 🎨 Principis de Disseny Aplicats

### DRY (Don't Repeat Yourself)
- `_product_form.html.twig` → reutilitzat per crear i editar productes
- `register.html.twig` → reutilitzat per registre i editar perfil
- `_product_card.html.twig` → reutilitzat al llistat públic i "Els meus productes"
- `index.html.twig` → una sola vista amb paràmetres per dos contextos
- `ProductType.php` → un sol formulari per crear i editar
- `RegistrationFormType.php` → un sol formulari per registre i editar perfil

---

## 🚀 Comandes Útils
```bash
# Servidor de desenvolupament
symfony serve

# Netejar caché
php bin/console cache:clear

# Recarregar fixtures (esborra i torna a crear les dades de prova)
php bin/console doctrine:fixtures:load

# Veure totes les rutes
php bin/console debug:router

# Veure usuaris registrats
mysql -u root -e "USE symfopop; SELECT id, name, email FROM user;"
```

---

## 📚 Documentació de Referència

- [Symfony Docs](https://symfony.com/doc/current/index.html)
- [Doctrine ORM](https://www.doctrine-project.org/projects/doctrine-orm/en/current/index.html)
- [Twig](https://twig.symfony.com/doc/3.x/)
- [Bootstrap 5](https://getbootstrap.com/docs/5.3/)
- [Security](https://symfony.com/doc/current/security.html)
- [Forms](https://symfony.com/doc/current/forms.html)

---

## 📎 Enllaços del Codi

| Secció | Enllaç |
|--------|--------|
| 📁 Entitats | `src/Entity/` |
| 🎮 Controladors | `src/Controller/` |
| 📝 Formularis | `src/Form/` |
| 🎨 Vistes | `templates/` |
| 🔒 Seguretat | `config/packages/security.yaml` |
| 📦 Fixtures | `src/DataFixtures/` |

---

## 🎓 Projecte desenvolupat per

**Zaine A. Boulbahaim** - Desenvolupament d'Aplicacions Web

> Projecte final de Symfony - SymfoPop Mercat de Segona Mà