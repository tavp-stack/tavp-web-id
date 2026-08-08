# tavp.web.id

The official TAVP Stack website, built on TAVP CMS (dogfooding).

## Stack

- **Backend:** Phalcon PHP (C-extension)
- **CMS:** TAVP CMS (flat-file or database storage)
- **Template:** Volt (Phalcon's template engine)
- **Styling:** Tailwind CSS
- **Interactivity:** Alpine.js
- **Database:** MariaDB

## Quick Start

```bash
# Clone
git clone https://github.com/tavp-stack/tavp-web-id.git
cd tavp-web-id

# Start with Lando
lando start

# Run migrations
lando ssh -c "php bin/tavp migrate"

# Open
open https://tavp-web-id.lndo.site
```

## Admin Panel

- URL: `https://tavp-web-id.lndo.site/admin`
- Email: `admin@tavp.web.id`
- OTP via Mailpit: `http://localhost:8026`

## CLI Commands

```bash
tavp migrate          # Run migrations
tavp cache:clear      # Clear all cache
tavp deploy           # Deploy to production
tavp schedule:run     # Run scheduled tasks
```

## Project Structure

```
app/                    # Local application code
bootstrap/app.php       # Service registration
config/                 # Configuration files
content/                # CMS content (flat-file)
database/migrations/    # Database migrations
public/                 # Web root (index.php, assets)
routes/web.php          # Route definitions
themes/tavp/            # Volt templates
vendor/tavp/            # TAVP packages (core, cms, cli)
```

## Deployment

```bash
# Set env vars
DEPLOY_HOST=your-server
DEPLOY_USER=your-user
DEPLOY_PATH=/home/user/web/tavp.web.id

# Deploy
lando ssh -c "php bin/tavp deploy"
```

## License

MIT
