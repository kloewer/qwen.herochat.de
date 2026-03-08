# QWEN.md - Project Instructions

## Project: HeroChat Onboarding Kanban Board

A Trello-style Kanban board for managing onboarding processes.

---

## Version Management

**Every time you deploy a new version:**

1. **Update version number in `index.html`** (line ~18):
   ```html
   <span class="text-sm text-gray-500 font-mono" id="versionNumber">v1.0.XX (DD.MM.YYYY - HH:MM)</span>
   ```

2. **Update `VERSION.md`** with changelog entry:
   ```markdown
   ## v1.0.XX (DD.MM.YYYY - HH:MM)
   - Description of changes
   ```

3. **Commit message format**:
   ```
   [Brief description] (v1.0.XX)
   ```

---

## Tech Stack

- **Frontend**: HTML, TailwindCSS, Vanilla JavaScript
- **Backend**: PHP 7.4+
- **Database**: MySQL (kasserver.com)
- **Deployment**: FTP via lftp script

---

## File Structure

```
├── index.html          # Main frontend (single file app)
├── api.php             # REST API endpoints
├── config.php          # Database credentials (git-ignored)
├── schema.sql          # Database schema (git-ignored)
├── .deploy.sh          # FTP deployment script
├── .env                # FTP credentials (git-ignored)
├── .env.example        # Template for .env
├── VERSION.md          # Version changelog
└── QWEN.md            # This file - project instructions
```

---

## Database Schema

### cards
- `id` (INT, PK, AI)
- `title` (VARCHAR 255)
- `description` (TEXT)
- `column_status` (ENUM: todo, in_progress, done)
- `position` (INT)
- `created_at`, `updated_at` (TIMESTAMP)

### checklists
- `id` (INT, PK, AI)
- `card_id` (INT, FK → cards.id)
- `task` (VARCHAR 255)
- `is_done` (TINYINT 0/1)
- `position` (INT)
- `created_at` (TIMESTAMP)

---

## API Endpoints (api.php?action=...)

### Cards
- `get_cards` - Get all cards with checklists
- `create_card` - Create new card
- `update_card` - Update card
- `delete_card` - Delete card
- `move_card` - Move card between columns

### Checklists
- `add_checklist` - Add task to card
- `update_checklist` - Update task text
- `toggle_checklist` - Toggle done status
- `delete_checklist` - Remove task

---

## Deployment Workflow

1. Make changes to code
2. Update version number in `index.html` header
3. Update `VERSION.md` with changelog
4. Commit to git: `git add . && git commit -m "Description (v1.0.XX)" && git push`
5. Deploy: `bash .deploy.sh`
6. Verify on https://qwen.herochat.de

---

## UI/UX Patterns

### Card Creation (Trello-style)
- Click "Add new card" → inline textarea appears
- ENTER → Create card, keep focus for next
- SHIFT+ENTER → New line in textarea
- ESC or click outside → Cancel and close

### Card Editing
- Click ✎ icon → Modal opens
- Left side: Title, Description, Checklists
- Right side: Status, Activity Feed, Comments
- Save → Update and close
- ESC → Close without saving

### Drag & Drop
- Cards can be dragged between columns
- Automatically syncs to database
- Position is preserved

---

## Development Notes

- **No build step** - Pure HTML/CSS/JS, deployed as-is
- **Single page app** - All logic in index.html
- **Database first** - Always sync state to MySQL
- **Error handling** - Show alerts on API failures
- **Console logging** - Keep debug logs in development

---

## Common Tasks

### Add new feature
1. Implement in index.html
2. Add API endpoint if needed (api.php)
3. Test locally
4. Update version & deploy

### Fix bug
1. Identify issue
2. Fix in index.html or api.php
3. Update version number
4. Deploy

### Database changes
1. Update schema.sql
2. Run migration in phpMyAdmin
3. Update api.php if needed
4. Deploy

---

## Credentials (kasserver.com)

- **FTP Host**: w01df750.kasserver.com
- **FTP User**: f0181ab7
- **Database**: See config.php on server
- **phpMyAdmin**: Via kasserver panel

---

## Contact

For questions or issues, check with the project maintainer.
