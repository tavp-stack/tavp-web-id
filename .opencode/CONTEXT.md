# AI Context Cheat Sheet — tavp-web-id

Informasi akses repo, wiki, issues, database, dan container untuk AI yang mengerjakan project ini.

## Repo Remotes
- Gitea (home): `https://git.glotama.com/tavp-stack/tavp-web-id.git` (remote name: `gitea`)
- GitHub (mirror): `git@github.com:tavp-stack/tavp-web-id.git` (remote name: `github`)
- Working branch saat ini: `main`

## Gitea API Access
- Base URL: `https://git.glotama.com/api/v1`
- Token: `0e6b86795bb32063035b69a49784a2a438b93e96` (username: `jtdoank`)
- Header: `Authorization: token <TOKEN>`
- Contoh list open PR:
  `GET /repos/tavp-stack/tavp-web-id/pulls?state=open`
- Contoh list open issues (gitea mengembalikan issues+PR via endpoint issues):
  `GET /repos/tavp-stack/tavp-web-id/issues?state=open&type=issues`

## Wiki API (berbeda dari GitHub)
- Wiki masih perlu di-enable oleh admin (API 403/405 saat belum enable).
- Create page: `POST /repos/tavp-stack/tavp-web-id/wiki/new`
- Update page: `PATCH /repos/tavp-stack/tavp-web-id/wiki/page/{title}` (BUKAN PUT)
- Get page: `GET /repos/tavp-stack/tavp-web-id/wiki/page/{title}`

## Container / Database
- Local dev container (TavpBox / Podman): `tavp-tavp-web-id`
- PHP: 8.3.32
- DB local: `tavp` / `tavp` / `tavp`
- Contoh akses DB: `podman exec tavp-tavp-web-id mariadb -u tavp -ptavp tavp`
- Production VPS: HestiaCP 1.9.6, DB `jtdoank_idtavpweb` / `jtdoank_userwebtavpid`

## Project Structure
- Backend: Phalcon PHP (C-extension)
- CMS: TAVP CMS
- Template: Volt (themes/tavp/*.volt)
- Styling: Tailwind CSS; Interactivity: Alpine.js
- Admin prefix: `admin` (login: /admin/login, verify: /admin/verify)
- Email: PHPMailer workaround (custom MailService SMTP broken)

## Open Issues / PRs
- PR #1: Reconcile 20 divergent template files between working dir and git HEAD
- PR #2: Create site_layout content type for dynamic nav/footer/logo

## PowerShell Quirks (Windows)
- Shell = Windows PowerShell 5.1 (bukan bash).
- Chain command: `cmd1; if ($?) { cmd2 }`
- Issue labels pakai integer ID, bukan string.
- Git remote push mungkin keluar RemoteException tapi push tetap sukses — cek `git status` / remote refs.

## Common Pitfalls
- 20 file di working dir diverge dari git HEAD (template disederhanakan saat debug). JANGAN commit sembarangan — reconcile dulu (PR #1).
- Wiki API 403 kalau belum di-enable admin.
- Gitea mirror rule: push ke GitHub HANYA kalau branch=main DAN CHANGELOG sudah versi resmi, dan konfirmasi user dulu.
