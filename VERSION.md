# Version History

## v1.0.57 (10.03.2026 - 18:30)
- Fixed datepicker using Flowbite's native datepicker-format attribute
- Datepicker now uses `datepicker-format="yyyy-mm-dd"` for correct format
- Simplified date handling: reads directly from datepicker input on save
- Date stored as `YYYY-MM-DD 00:00:00` (midnight, no time selection needed)
- Change event listener updates hidden field when date is selected

## v1.0.56 (10.03.2026 - 18:00)
- Fixed datepicker timezone/date format issues
- Date parsing: Now splits date string directly (YYYY-MM-DD) instead of using Date constructor
- Prevents timezone shifts (e.g., March 25 becoming March 22)
- Due date badge calculation: Uses local time with explicit date parsing
- All date comparisons now use start-of-day for accuracy

## v1.0.55 (10.03.2026 - 17:45)
- Bugfix: Fixed PDO::COLUMN typo (should be PDO::FETCH_COLUMN)
- This was causing API connection errors

## v1.0.54 (10.03.2026 - 17:30)
- Auto-cleanup of empty checklist items (two-layer protection)
- Layer 1: On modal close - deletes empty items via JavaScript before closing
- Layer 2: On card load (API) - deletes empty items from database when fetching cards
- Prevents accumulation of empty checklist items in database

## v1.0.53 (10.03.2026 - 17:00)
- Made entire pipeline a droppable area for cards
- Moved add-card-form inside kanban-list (at the bottom)
- Added filter to prevent add-card-form from being draggable
- Cards can now be dropped anywhere in the pipeline

## v1.0.52 (10.03.2026 - 16:30)
- Enabled card reordering within and between columns
- Cards can now be dropped at any position and maintain order
- API updated with proper position shifting logic
- Same column: shifts cards between old and new positions
- Different column: removes gap in old column, makes room in new column
- Frontend reloads cards after move to get updated positions
- renderAllCards now sorts cards by position before rendering

## v1.0.51 (10.03.2026 - 16:00)
- Updated three-dot menu icon: vertical dots (w-6 h-6), more visible
- Icon: stroke-based vertical dots instead of filled horizontal dots
- Fixed script breaking: added e.preventDefault() to dropdown click handler
- Global click handler for closing dropdowns moved to initModal (added only once)
- Removed duplicate click handler from renderModalTasks

## v1.0.50 (10.03.2026 - 15:30)
- Fixed dropdown positioning: absolute positioned, no longer expands checkbox item
- Dropdown menu: right-0 top-8 z-50, floats above content
- Fixed dropdown ID conflicts: using unique IDs with timestamp (task.id + Date.now())
- Changed from data-dropdown-toggle to data-dropdown-id attribute
- Close dropdowns when clicking outside (with event target check)

## v1.0.49 (10.03.2026 - 15:00)
- Fixed datepicker: using global Datepicker constructor instead of window.Datepicker
- Fixed task dropdown menus: added custom dropdown toggle handlers after render
- Dropdowns now properly open/close on button click
- All dropdowns close when clicking outside

## v1.0.48 (10.03.2026 - 14:30)
- Updated checklist checkbox styling to match Tailwind design system
- Checkboxes: w-4 h-4, border, rounded, bg-gray-100, focus:ring-2 focus:ring-blue-500
- Task dropdown menu now uses Flowbite dropdown component with proper styling
- New SVG icons for dropdown menu items (calendar, user, card, trash)
- Dropdown menu: white background, border, shadow, proper spacing and hover states
- Task items: proper text color (gray-800) and line-through styling for completed tasks

## v1.0.47 (10.03.2026 - 14:00)
- Replaced native datetime-local input with Flowbite datepicker
- Datepicker features: calendar popup, autohide, date format (YYYY-MM-DD)
- Added Flowbite library from CDN
- Due date stored as DATETIME (midnight on selected date)

## v1.0.46 (10.03.2026 - 13:30)
- Due date badge now updates correctly when cards are moved between columns
- Cards moved to "Done" column immediately show green "Done" badge
- Cards moved back from "Done" show their actual due date status
- Re-render triggered after drag-and-drop move operation

## v1.0.45 (10.03.2026 - 13:00)
- Fixed overdue cards: now shows "X days overdue" instead of "X days left"
- Cards in "Done" column always show green "Done" badge
- Due date badge function now takes column_status parameter

## v1.0.44 (10.03.2026 - 12:30)
- Due date badge aligned with members on same line (bottom of card)
- New color coding: <7 days = red, 7-14 days = orange, 14-30 days = gray, >30 days = date (gray)
- Removed icons from badges for cleaner look
- Due date badge positioned in lower right corner

## v1.0.43 (10.03.2026 - 12:00)
- Added due date feature to cards
- Due date displayed on card front with color-coded badge (red = overdue, orange = within 7 days, gray = later)
- Date picker in modal using native datetime-local input
- "Remove due date" button to clear due date
- Badge shows: "X days left", "Today", "Tomorrow", "X days overdue", or date
- Database schema updated with due_date column in cards table
- API updated to handle due_date on create, update, and move operations

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
