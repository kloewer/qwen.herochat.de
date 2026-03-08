# Version History

## v1.0.42 (09.03.2026 - 03:00)
- Activity feed: All timestamps in GREY (not blue)
- Consistent layout: Name → Content → Timestamp (all on separate lines)
- Comment boxes: Name on top, comment in middle, timestamp below

## v1.0.41 (09.03.2026 - 02:30)
- Activity feed: ALL items now have timestamps (grey, below each item)
- Comments AND checklist activities sorted together by time
- Newest activity at TOP, oldest at BOTTOM
- Added checked_at timestamp to track when tasks are completed
- All activities show: Avatar + Name + Action + Timestamp

## v1.0.40 (09.03.2026 - 01:30)
- Task action menu (3-dot) on hover in modal
- Set due date for tasks (with emoji indicator 📅)
- Assign member to tasks (shows avatar)
- Convert task to linked card (shows link icon 🔗)
- Delete task (unlinks, keeps card if linked)
- Activity feed sorted: Newest on TOP, oldest at bottom
- Task badges: due date, assignee avatar, link icon
- Overdue tasks show red calendar icon

## v1.0.39 (09.03.2026 - 00:30)
- Checklist enhancements: due_date, assigned_user, linked_card_id fields
- New API endpoints: convert_checklist_to_card, set_checklist_due_date, assign_checklist
- Comment input: 1 line by default, expands on focus
- Save button hidden until comment has content
- Comment input collapses back when empty on blur
- Migration file for checklist enhancements

## v1.0.38 (08.03.2026 - 23:30)
- User avatar + Logout moved to RIGHT side of header
- Checklist checkbox sync: Card ↔ Modal now in sync
- Activity feed: Consistent styling for ALL items
- All activity items: Avatar + Name + Action + Timestamp
- Same format: "Christian created this card", "Christian made a comment", "Christian checked off..."
- German datetime format (DD.MM.YYYY - HH:MM) on all items

## v1.0.37 (08.03.2026 - 23:00)
- Activity section redesigned (Trello style)
- Comment input moved to TOP of right sidebar
- Activity feed below with scroll
- Full user details: avatar, name, timestamp
- German date/time format (DD.MM.YYYY - HH:MM)
- Comments show in white boxes with border
- Delete button appears on hover
- Newest comments shown first

## v1.0.36 (08.03.2026 - 22:30)
- User avatar moved to far left in header
- Removed user name from header (avatar + Logout only)
- Version info moved to bottom-right footer
- Version timestamp now uses live Unix time (auto-updates)
- Footer in sans-serif gray text

## v1.0.35 (08.03.2026 - 22:15)
- Auto-delete empty tasks when clicking away (blur event)
- Clean up empty checklist items automatically

## v1.0.34 (08.03.2026 - 22:00)
- Moved X (close button) to top-right corner of modal header
- New tasks start with empty input + auto-focus (no "New task" text)
- ENTER key in task input creates new task and focuses it
- Improved task creation workflow

## v1.0.33 (08.03.2026 - 21:30)
- Show member avatars on cards (overlapping circles)
- Show member avatars + names in modal
- Improved member display in assigned members section
- Avatar fallback to default user icon

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
