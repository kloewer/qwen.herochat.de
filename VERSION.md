# Version History

## v1.0.32 (08.03.2026 - 20:30)
- Added user authentication system (2 users: Christian & Alvar)
- Login page with email/password
- Session-based authentication
- Logout button in header
- Current user display with avatar
- User filter in header (All Cards / Christian's / Alvar's)
- Card assignment to multiple users
- Members section in modal with dropdown
- Comments attributed to users
- New database tables: card_assignments, comments.user_email
- API updated with auth endpoints

## v1.0.31 (08.03.2026 - 19:30)
- Redesigned modal to match Trello design
- X button moved to top-left corner
- Status dropdown moved to header (top-left)
- Removed Attachments and menu buttons
- Activity section moved to top of right sidebar
- Added comments feature with MySQL storage
- New `comments` table in database
- Comments sync to database on post
- Delete comments with hover-to-reveal delete button

## v1.0.30 (08.03.2026 - 18:45)
- Trello-style inline card creation
- Click outside to cancel card creation
- ENTER to create, SHIFT+ENTER for new line
- ESC to cancel
- Added QWEN.md project instructions
- Auto-close all forms when clicking elsewhere

## v1.0.29 (08.03.2026 - 17:30)
- Added header with title and version number
- Fixed modal closing/reopening issue
- Improved modal layout with activity sidebar

## v1.0.28 (08.03.2026 - 16:48)
- Redesigned modal Trello-style with left/right sidebar
- Added activity feed showing card creation and checklist completions
- Added comment input functionality

## v1.0.27 (08.03.2026)
- Fixed URL constructor bug in API calls
- Added error handling and alerts

## v1.0.26 (08.03.2026)
- Connected frontend to MySQL database
- Added PHP API for CRUD operations
- Implemented drag-and-drop with database sync

## v1.0.25 (08.03.2026)
- Initial Kanban board with static data
- Basic checklist functionality
- Modal editor
